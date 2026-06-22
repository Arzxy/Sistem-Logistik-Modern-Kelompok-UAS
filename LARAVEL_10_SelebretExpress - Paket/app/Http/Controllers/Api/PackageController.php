<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Services\TrackingNotifier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PackageController extends Controller
{
    // GET /api/packages
    // Query params: status, courier_id, sender_id, limit, page
    public function index(Request $request)
    {
        $query = Package::query()
            ->byStatus($request->status)
            ->byCourier($request->courier_id);

        if ($request->filled('sender_id')) {
            $query->where('sender_id', $request->sender_id);
        }
        if ($request->filled('receiver_id')) {
            $query->where('receiver_id', $request->receiver_id);
        }
        if ($request->filled('origin_warehouse_id')) {
            $query->where('origin_warehouse_id', $request->origin_warehouse_id);
        }

        // Pencarian by resi
        if ($request->filled('search')) {
            $query->where('resi_number', 'like', '%' . $request->search . '%');
        }

        // Tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $packages = $query->orderByDesc('created_at')
            ->paginate($request->get('limit', 20));

        return response()->json([
            'status' => 'success',
            'data'   => $packages->items(),
            'meta'   => [
                'total'        => $packages->total(),
                'current_page' => $packages->currentPage(),
                'last_page'    => $packages->lastPage(),
            ],
        ]);
    }

    // GET /api/packages/{id}
    public function show($id)
    {
        $package = Package::findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $package]);
    }

    // GET /api/packages/resi/{resiNumber}
    public function showByResi(string $resi)
    {
        $package = Package::where('resi_number', $resi)->firstOrFail();
        return response()->json(['status' => 'success', 'data' => $package]);
    }

    // POST /api/packages
    public function store(Request $request)
    {
        $validated = $request->validate([
            'sender_id'           => 'required|integer',
            'receiver_id'         => 'required|integer',
            'origin_warehouse_id' => 'required|integer',
            'dest_warehouse_id'   => 'required|integer',
            'alamat_tujuan'         => 'nullable|string|max:500',
            'weight_kg'           => 'required|numeric|min:0.1',
            'length_cm'           => 'nullable|numeric|min:1',
            'width_cm'            => 'nullable|numeric|min:1',
            'height_cm'           => 'nullable|numeric|min:1',
            'description'         => 'nullable|string|max:500',
            'total_price'         => 'required|numeric|min:0',
            'service_type'        => 'nullable|in:reguler,express,cargo',
            'created_by'          => 'required|integer',
        ]);

        // Hitung volume weight jika dimensi ada
        $volumeWeight = ($validated['length_cm'] * $validated['width_cm'] * $validated['height_cm']) / 6000;
		/*
		$volumeWeight = Package::calculateVolumeWeight(
            $validated['length_cm'] ?? null,
            $validated['width_cm']  ?? null,
            $validated['height_cm'] ?? null,
        );
		*/

        $package = Package::create(array_merge($validated, [
            'resi_number'      => Package::generateResi(),
            'volume_weight_kg' => $volumeWeight,
            'status'           => 'pending_pickup',
        ]));

        // ── Notifikasi L5: log pertama "paket diterima di gudang" ──
        TrackingNotifier::log(
            packageId:   $package->id,
            resi:        $package->resi_number,
            status:      'pending',
            location:    'Kasir SelebetExpress',
            notes:       'Paket diterima, menunggu kurir.',
            warehouseId: $package->origin_warehouse_id,
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Resi berhasil dibuat.',
            'data'    => $package,
        ], 201);
    }

    // POST /api/packages/{id}/register-tracking
    public function registerTracking(int $id)
    {
        $package = Package::findOrFail($id);

        // ── Notifikasi L5: registrasi manual log pertama ──
        TrackingNotifier::log(
            packageId:   $package->id,
            resi:        $package->resi_number,
            status:      'pending',
            location:    'Kasir SelebetExpress',
            notes:       'Paket diterima, menunggu kurir.',
            warehouseId: $package->origin_warehouse_id,
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Paket berhasil didaftarkan ke sistem pelacakan secara manual.',
            'data'    => $package,
        ]);
    }

    // PATCH /api/packages/{id}/status
    // Dipanggil oleh L4 (Armada) saat kurir update status
    public function updateStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'status'       => 'required|string',
            'courier_id'   => 'nullable|integer',
            'delivery_id'  => 'nullable|integer',
            'location'     => 'nullable|string',
            'notes'        => 'nullable|string',
            'warehouse_id' => 'nullable|integer',
            'skip_tracking_log' => 'nullable|boolean',
        ]);

        $package = Package::findOrFail($id);
        $package->update([
            'status'      => $validated['status'],
            'courier_id'  => $validated['courier_id']  ?? $package->courier_id,
            'delivery_id' => $validated['delivery_id'] ?? $package->delivery_id,
        ]);

        // ── Notifikasi L5 setiap kali status berubah ──
        if (empty($validated['skip_tracking_log'])) {
            TrackingNotifier::log(
                packageId:   $package->id,
                resi:        $package->resi_number,
                status:      $validated['status'],
                location:    $validated['location']    ?? '-',
                notes:       $validated['notes']       ?? '',
                courierId:   $validated['courier_id']  ?? null,
                warehouseId: $validated['warehouse_id']?? null,
            );
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Status paket diperbarui.',
            'data'    => $package->fresh(),
        ]);
    }

    // GET /api/packages/stats
    // Untuk dashboard Frontend — ringkasan statistik
    public function stats()
    {
        $counts = Package::query()
            ->selectRaw("
                COUNT(*) as total,
                SUM(status = 'pending_pickup')                    as pending_pickup,
                SUM(status = 'picked_up')                         as picked_up,
                SUM(status = 'at_origin_warehouse')               as at_origin_warehouse,
                SUM(status = 'assigned')                          as assigned,
                SUM(status = 'in_transit')                        as in_transit,
                SUM(status = 'at_destination_warehouse')          as at_destination_warehouse,
                SUM(status = 'out_for_delivery')                  as out_for_delivery,
                SUM(status = 'delivered')                         as delivered,
                SUM(status = 'cancelled')                         as cancelled,
                SUM(status = 'returned')                          as returned
            ")
            ->first();

        $revenue = Package::where('status', 'delivered')
            ->sum('total_price');

        $todayCount = Package::whereDate('created_at', today())->count();
        $todayRevenue = Package::where('status', 'delivered')
            ->whereDate('updated_at', today())
            ->sum('total_price');

        return response()->json([
            'status' => 'success',
            'data'   => [
                'counts'        => $counts,
                'total_revenue' => $revenue,
                'today'         => [
                    'packages' => $todayCount,
                    'revenue'  => $todayRevenue,
                ],
            ],
        ]);
    }

}