<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tariff;
use App\Models\TariffLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TariffController extends Controller
{
    // GET /api/tariffs
    // Ambil semua tarif (bisa filter ?origin=Jakarta)
    public function index(Request $request)
    {
        //$query = Tariff::active();
        $query = Tariff::query();

        if ($request->filled('origin')) {
            $query->where('origin_city', $request->origin);
        }
        if ($request->filled('dest')) {
            $query->where('dest_city', $request->dest);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $query->orderBy('origin_city')->get(),
        ]);
    }

    // GET /api/tariffs/{id}
    public function show($id)
    {
        $tariff = Tariff::with('logs')->findOrFail($id);
        return response()->json(['status' => 'success', 'data' => $tariff]);
    }

    // POST /api/tariffs
    public function store(Request $request)
    {
        $validated = $request->validate([
            'origin_city'    => 'required|string|max:60',
            'dest_city'      => 'required|string|max:60',
            'price_per_kg'   => 'required|numeric|min:0',
            'min_weight_kg'  => 'nullable|numeric|min:0',
            'estimated_days' => 'nullable|integer|min:1',
        ]);

        // Cek duplikat
        $exists = Tariff::where('origin_city', $validated['origin_city'])
                        ->where('dest_city', $validated['dest_city'])
                        ->exists();

        if ($exists) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tarif untuk rute ini sudah ada.',
            ], 422);
        }

        $tariff = Tariff::create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Tarif berhasil dibuat.',
            'data'    => $tariff,
        ], 201);
    }

    // PUT /api/tariffs/{id}
    public function update(Request $request, $id)
    {
        $tariff = Tariff::findOrFail($id);

        $validated = $request->validate([
            'price_per_kg'   => 'sometimes|numeric|min:0',
            'min_weight_kg'  => 'sometimes|numeric|min:0',
            'estimated_days' => 'sometimes|integer|min:1',
            'is_active'      => 'sometimes|boolean',
            'changed_by'     => 'required|integer', // ID admin dari L1
        ]);

        // Catat log jika harga berubah
        if (isset($validated['price_per_kg'])
            && $validated['price_per_kg'] != $tariff->price_per_kg) {
            TariffLog::create([
                'tariff_id'  => $tariff->id,
                'old_price'  => $tariff->price_per_kg,
                'new_price'  => $validated['price_per_kg'],
                'changed_by' => $validated['changed_by'],
                'changed_at' => now(),
            ]);
        }

        $tariff->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Tarif berhasil diupdate.',
            'data'    => $tariff->fresh(),
        ]);
    }

    // DELETE /api/tariffs/{id}
    public function destroy($id)
    {
        $tariff = Tariff::findOrFail($id);
        //$tariff->update(['is_active' => false]); // soft deactivate, bukan hapus
		// TAMBAHAN NEW
		$tariff->delete();
		// END
        return response()->json(['status' => 'success', 'message' => 'Tarif dinonaktifkan.']);
    }

    // GET /api/tariffs/calculate?origin=Jakarta&dest=Bandung&weight=2.5
    public function calculate(Request $request)
    {
        $request->validate([
            'origin' => 'required|string',
            'dest'   => 'required|string',
            'weight' => 'required|numeric|min:0.1',
        ]);

        $tariff = Tariff::active()
                        ->where('origin_city', $request->origin)
                        ->where('dest_city', $request->dest)
                        ->first();

        if (!$tariff) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Tarif untuk rute '.$request->origin.' - '.$request->dest.' tidak ditemukan.',
            ], 404);
        }

        // Pakai berat aktual atau minimum, ambil yang lebih besar
        $chargeableWeight = max((float)$request->weight, $tariff->min_weight_kg);
        $totalPrice = $chargeableWeight * $tariff->price_per_kg;

        return response()->json([
            'status' => 'success',
            'data'   => [
                'origin'           => $request->origin,
                'destination'      => $request->dest,
                'actual_weight_kg' => (float)$request->weight,
                'chargeable_kg'    => $chargeableWeight,
                'price_per_kg'     => $tariff->price_per_kg,
                'total_price'      => round($totalPrice, 2),
                'estimated_days'   => $tariff->estimated_days,
                'currency'         => 'IDR',
            ],
        ]);
    }

    // GET /api/tariffs/{id}/logs — riwayat perubahan harga
    public function logs($id)
    {
        $tariff = Tariff::findOrFail($id);
        $logs = $tariff->logs()->orderByDesc('changed_at')->get();
        return response()->json(['status' => 'success', 'data' => $logs]);
    }
}