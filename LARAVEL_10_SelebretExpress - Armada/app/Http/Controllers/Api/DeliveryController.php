<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use App\Models\Delivery;
use App\Services\TrackingNotifier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DeliveryController extends Controller
{
    public function __construct(private TrackingNotifier $notifier) {}

    // GET /api/deliveries
    // Filter: ?courier_id=X&status=assigned|in_transit|delivered
    public function index(Request $request)
    {
        $query = Delivery::with('courier');

        if ($request->filled('courier_id')) {
            $query->where('courier_id', $request->courier_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('package_id')) {
            $query->where('package_id', $request->package_id);
        }

        $deliveries = $query->orderByDesc('created_at')->paginate(20);

        return response()->json([
            'status' => 'success',
            'data'   => $deliveries,
        ]);
    }

    // GET /api/deliveries/{id}
    public function show($id)
    {
        $delivery = Delivery::with('courier')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $delivery]);
    }

    // POST /api/deliveries
    // Agent/Admin assign kurir ke paket. Satu paket bisa punya banyak delivery
    // (pickup -> inter_warehouse -> last_mile), tapi tidak boleh ada delivery AKTIF
    // (belum delivered/failed/returned) untuk package yang sama pada saat bersamaan.
    public function store(Request $request)
    {
        $validated = $request->validate([
            'package_id' => [
                'required',
                'integer',
                // Cegah duplicate hanya jika ada delivery AKTIF (belum selesai)
                // Delivery yang sudah delivered/failed/returned boleh di-assign ulang
                Rule::unique('deliveries', 'package_id')->where(function ($query) {
                    return $query->whereNotIn('status', ['delivered', 'failed', 'returned']);
                }),
            ],
            'courier_id'          => 'required|integer|exists:couriers,id',
            'origin_warehouse_id' => 'required|integer',
            'dest_warehouse_id'   => 'required|integer',
            'delivery_type'       => 'required|string',
            'current_location'    => 'nullable|string|max:150',
            'notes'               => 'nullable|string',
        ]);

        $courier = Courier::findOrFail($validated['courier_id']);

        // Cek apakah kurir sedang on_duty penuh (opsional, tergantung kebijakan bisnis)
        // Untuk sekarang: biarkan assign meski on_duty (bisa punya > 1 delivery)

        $delivery = Delivery::create([
            ...$validated,
            'status'      => 'assigned',
            'assigned_at' => now(),
        ]);

        // Update status kurir menjadi on_duty
		/*
		$courier->update([
            'status'         => 'on_duty',
            'last_active_at' => now(),
        ]);
		*/

        // Kirim notifikasi ke L5 — WAJIB setiap ada perubahan status
        $this->notifier->notify(
            packageId: $delivery->package_id,
            courierId: $delivery->courier_id,
            status:    'assigned',
            location:  $validated['current_location'] ?? 'Gudang asal',
            notes:     'Kurir ' . $courier->name . ' ditugaskan.'
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Kurir berhasil ditugaskan ke paket.',
            'data'    => $delivery->load('courier'),
        ], 201);
    }

    // PATCH /api/deliveries/{id}/status
    // Kurir update status pengiriman (picked_up, in_transit, delivered, dll)
    public function updateStatus(Request $request, $id)
    {
        $delivery = Delivery::with('courier')->findOrFail($id);

        // Delivery yang sudah selesai tidak bisa diubah
        if ($delivery->isFinished()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Pengiriman sudah selesai, status tidak bisa diubah.',
            ], 422);
        }

        $validated = $request->validate([
            'delivery_type'   => 'required|string',
            'status'   => 'required|string',
            'location' => 'nullable|string|max:150',
            'notes'    => 'nullable|string',
        ]);

        // Isi timestamp sesuai status
        $timestamps = [];
        if ($validated['status'] === 'picked_up') {
            $timestamps['picked_up_at'] = now();
        } elseif ($validated['status'] === 'delivered') {
            $timestamps['delivered_at'] = now();
        }

        $delivery->update([
            'delivery_type'    => $validated['delivery_type'],
            'status'           => $validated['status'],
            'current_location' => $validated['location'] ?? $delivery->current_location,
            'notes'            => $validated['notes'] ?? $delivery->notes,
            ...$timestamps,
        ]);

        $delivery->courier->update([
            'status'         => $newCourierStatus,
            'last_active_at' => now(),
        ]);

        // Kirim notifikasi ke L5 — WAJIB
        $this->notifier->notify(
            packageId: $delivery->package_id,
            courierId: $delivery->courier_id,
            status:    $validated['status'],
            location:  $validated['location'] ?? $delivery->current_location ?? '-',
            notes:     $validated['notes']
        );

        return response()->json([
            'status'  => 'success',
            'message' => 'Status pengiriman diupdate.',
            'data'    => $delivery->fresh('courier'),
        ]);
    }

    // GET /api/deliveries/by-package/{packageId}
    // Frontend/L5 cek delivery berdasarkan package_id
    public function byPackage($packageId)
    {
        $delivery = Delivery::with('courier')
                            ->where('package_id', $packageId)
                            ->first();

        if (!$delivery) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Delivery untuk paket ini belum ada.',
            ], 404);
        }

        return response()->json(['status' => 'success', 'data' => $delivery]);
    }
	
    // GET /api/deliveries/package/{packageId}
    // Frontend/L5 cek delivery berdasarkan package_id
    public function getAllbyPackage($packageId)
    {
        $delivery = Delivery::with('courier')
                            ->where('package_id', $packageId)
                            ->get();

        if (!$delivery) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Delivery untuk paket ini belum ada.',
            ], 404);
        }

        return response()->json(['status' => 'success', 'data' => $delivery]);
    }
}