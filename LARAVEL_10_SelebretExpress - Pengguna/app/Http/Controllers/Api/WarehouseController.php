<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Warehouse;
use App\Models\User;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    // GET /api/warehouses
    // ?city=Jakarta untuk filter kota
    public function index(Request $request)
    {
        //$query = Warehouse::with('agent:id,name,phone')->where('is_active', true);
		$query = Warehouse::with('agent:id,name,phone');

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }
        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->orderBy('city')->get(),
        ]);
    }

    // GET /api/warehouses/{id}
    // Dipanggil L2 saat butuh detail gudang asal/tujuan
    public function show($id)
    {
        //$warehouse = Warehouse::with('agent:id,name,phone,city')->where('is_active', true)->findOrFail($id);
        $warehouse = Warehouse::with('agent:id,name,phone,city')->findOrFail($id);

        return response()->json(['status' => 'success', 'data' => $warehouse]);
    }

    // POST /api/warehouses — hanya admin
    public function store(Request $request)
    {
        $validated = $request->validate([
            'agent_id' => 'required|exists:users,id',
            'name' => 'required|string|max:100',
            'city' => 'required|string|max:60',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
        ]);

        // Pastikan agent_id adalah user dengan role agen
        /*$agent = User::find($validated['agent_id']);
        if (!$agent || $agent->role !== 'agen') {
            return response()->json([
                'status' => 'error',
                'message' => 'agent_id harus merujuk ke user dengan role agen.',
            ], 422);
        }*/

        $warehouse = Warehouse::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Gudang berhasil didaftarkan.',
            'data' => $warehouse->load('agent:id,name'),
        ], 201);
    }

    // PUT /api/warehouses/{id}
    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'city' => 'sometimes|string|max:60',
            'address' => 'sometimes|string',
            'phone' => 'sometimes|nullable|string|max:20',
            'is_active'      => 'sometimes|boolean',
        ]);

        $warehouse->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data gudang berhasil diupdate.',
            'data' => $warehouse->fresh()->load('agent:id,name'),
        ]);
    }

    // DELETE /api/warehouses/{id}
    public function destroy($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        //$warehouse->update(['is_active' => false]);
		// TAMBAHAN NEW
		$warehouse->delete();
		// END
        return response()->json(['status' => 'success', 'message' => 'Gudang dinonaktifkan.']);
    }
}