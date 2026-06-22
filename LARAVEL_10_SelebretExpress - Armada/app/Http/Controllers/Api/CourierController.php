<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    // GET /api/couriers
    // Query params: ?status=available|on_duty|off_duty
    public function index(Request $request)
    {
        $query = Courier::withCount([
            'deliveries as total_deliveries',
            'activeDeliveries as active_deliveries_count',
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by nama atau nomor HP
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('phone', 'like', '%'.$request->search.'%');
            });
        }

        $couriers = $query->orderBy('name')->get();

        return response()->json([
            'status' => 'success',
            'data'   => $couriers,
        ]);
    }

    // GET /api/couriers/{id}
    public function show($id)
    {
        $courier = Courier::with([
            'deliveries' => fn($q) => $q->latest()->limit(10),
        ])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data'   => $courier,
        ]);
    }

    // POST /api/couriers
    // Dipanggil Admin saat mendaftarkan kurir baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'       => 'required|integer|unique:couriers,user_id',
            'warehouse_id'  => 'required|integer',
            'name'          => 'required|string|max:100',
            'phone'         => 'required|string|max:20|unique:couriers,phone',
            'vehicle_type'  => 'nullable|in:motor,mobil,truck',
            'vehicle_plate' => 'nullable|string|max:15',
        ]);

        $courier = Courier::create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Kurir berhasil didaftarkan.',
            'data'    => $courier,
        ], 201);
    }

    // PUT /api/couriers/{id}
    // Update data kurir (vehicle, status, dll)
    public function update(Request $request, $id)
    {
        $courier = Courier::findOrFail($id);

        $validated = $request->validate([
            'warehouse_id'  => 'sometimes|integer',
            'vehicle_type'  => 'sometimes|in:motor,mobil,truck',
            'vehicle_plate' => 'sometimes|string|max:15',
            'status'        => 'sometimes|string',
        ]);

        $courier->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Data kurir diupdate.',
            'data'    => $courier->fresh(),
        ]);
    }

    // DELETE /api/couriers/{id}
    // Hard delete — hapus permanen dari DB (dipanggil saat user kurir dihapus dari sistem)
    public function destroy($id)
    {
        $courier = Courier::withTrashed()->findOrFail($id);

        // Cegah hapus jika masih ada delivery aktif
        if ($courier->activeDeliveries()->count() > 0) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Kurir masih memiliki pengiriman aktif. Selesaikan dulu sebelum menghapus.',
            ], 422);
        }

        $courier->forceDelete(); // Hard delete — hapus permanen

        return response()->json([
            'status'  => 'success',
            'message' => 'Data kurir berhasil dihapus permanen.',
        ]);
    }

    // GET /api/couriers/{id}/deliveries
    // Riwayat semua delivery seorang kurir
    public function deliveries(Request $request, $id)
    {
        $courier = Courier::findOrFail($id);

        $deliveries = $courier->deliveries()
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json([
            'status' => 'success',
            'data'   => $deliveries,
        ]);
    }
}