<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrackingLog;
use App\Models\PackageSummary;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    // ── POST /api/tracking-logs ──────────────────────────────
    // ── POST /api/tracking/log  (alias dari L4 Armada & L2 Paket)
    // Dipanggil oleh L2 (saat paket dibuat) dan L4 (saat status berubah)
    // Wajib menyertakan header: X-Service-Key
    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id'     => 'required|integer',
            'resi_number'    => 'nullable|string|max:30',  // nullable: L4 tidak selalu kirim resi
            'courier_id'     => 'nullable|integer',
            'warehouse_id'   => 'nullable|integer',
            'status'         => 'required|string|max:100',
            'location'       => 'nullable|string|max:150',
            'notes'          => 'nullable|string',
            'source_service' => 'nullable|string|max:10',
            'logged_at'      => 'nullable|date',
        ]);

        // Simpan log baru
        $log = TrackingLog::create([
            ...$validated,
            'logged_at'      => $validated['logged_at'] ?? now(),
            'source_service' => $validated['source_service'] ?? 'UNKNOWN',
        ]);

        // Update / buat summary status terkini paket ini
        $resiNumber = $validated['resi_number'] ?? null;
        if (empty($resiNumber)) {
            // Cek di database lokal dulu
            $existingSummary = PackageSummary::where('package_id', $validated['package_id'])->first();
            if ($existingSummary) {
                $resiNumber = $existingSummary->resi_number;
            } else {
                // Jika tidak ada di lokal L5, tanya ke L2 (SERVICE_PAKET)
                try {
                    $packageServiceUrl = env('SERVICE_PAKET', 'http://127.0.0.1:8002');
                    $response = \Illuminate\Support\Facades\Http::withHeaders([
                        'X-Service-Key' => env('INTERNAL_SERVICE_KEY'),
                    ])->get($packageServiceUrl . '/api/packages/' . $validated['package_id']);
                    
                    if ($response->successful()) {
                        $pkgData = $response->json()['data'] ?? null;
                        if ($pkgData && !empty($pkgData['resi_number'])) {
                            $resiNumber = $pkgData['resi_number'];
                        }
                    }
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::error('TrackingController: Gagal mengambil data paket dari L2 Paket: ' . $e->getMessage());
                }
            }
        }

        if (!empty($resiNumber)) {
            PackageSummary::updateFromLog($log, $resiNumber);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Log tracking berhasil dicatat.',
            'data'    => $log,
        ], 201);
    }

    // ── GET /api/tracking/{resi} ─────────────────────────────
    // Dipanggil frontend (publik, tanpa auth) untuk lacak paket
    public function byResi(string $resi)
    {
        // Ambil summary status terkini
        $summary = PackageSummary::where('resi_number', $resi)->first();

        if (!$summary) {
            return response()->json([
                'status' => 'error',
                'message' => "Resi '{$resi}' tidak ditemukan.",
            ], 404);
        }

        // Ambil semua log untuk resi ini, urut dari awal
        $logs = TrackingLog::where('package_id', $summary->package_id)
            ->orderBy('logged_at', 'asc')
            ->get()
            ->map(fn($log) => [
                'id' => $log->id,
                'status' => $log->status,
                'location' => $log->location,
                'notes' => $log->notes,
                'source_service' => $log->source_service,
                'logged_at' => $log->logged_at,
                'formatted_time' => $log->formatted_time,
            ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'resi_number' => $resi,
                'package_id' => $summary->package_id,
                'last_status' => $summary->last_status,
                'last_location' => $summary->last_location,
                'last_updated' => $summary->last_updated,
                'logs' => $logs,
            ],
        ]);
    }

    // ── GET /api/tracking/package/{packageId} ────────────────
    // Dipanggil oleh layanan internal dengan package_id
    public function byPackageId(int $packageId)
    {
        $logs = TrackingLog::where('package_id', $packageId)
            ->orderBy('logged_at', 'asc')
            ->get();

        // Return array kosong (bukan 404) jika belum ada log
        // agar frontend tidak salah tangani sebagai error
        return response()->json([
            'status' => 'success',
            'data'   => $logs,
        ]);
    }

    // ── GET /api/tracking-logs ───────────────────────────────
    // Dipanggil admin dari frontend untuk melihat semua log hari ini
    // ?date=2024-01-15 untuk filter tanggal
    // ?source_service=L4 untuk filter per layanan
    public function index(Request $request)
    {
        $query = TrackingLog::orderByDesc('logged_at');

        if ($request->filled('date')) {
            $query->whereDate('logged_at', $request->date);
        }
        if ($request->filled('source_service')) {
            $query->where('source_service', $request->source_service);
        }
        if ($request->filled('package_id')) {
            $query->where('package_id', $request->package_id);
        }

        $logs = $query->limit(200)->get(); // batasi 200 untuk performa

        return response()->json([
            'status' => 'success',
            'total' => $logs->count(),
            'data' => $logs,
        ]);
    }

    // ── GET /api/tracking/summaries ──────────────────────────
    // Daftar status terkini semua paket — untuk dashboard admin
    public function summaries(Request $request)
    {
        $query = PackageSummary::orderByDesc('last_updated');

        if ($request->filled('status')) {
            $query->where('last_status', 'like', '%' . $request->status . '%');
        }

        $summaries = $query->limit(100)->get();

        return response()->json([
            'status' => 'success',
            'total' => $summaries->count(),
            'data' => $summaries,
        ]);
    }

    // ── DELETE /api/tracking-logs/{id} ───────────────────────
    // Hapus log (admin only — jarang dipakai)
    public function destroy(int $id)
    {
        $log = TrackingLog::findOrFail($id);
        $log->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Log berhasil dihapus.',
        ]);
    }
}