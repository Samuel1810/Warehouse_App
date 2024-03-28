<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\Cabinet;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CabinetController extends Controller
{
    public function edit($warehouse, $cabinet)
    {
        $cabinet = Cabinet::where(['id' => $cabinet, 'warehouse_id' => $warehouse])->first();

        if (!$cabinet) {
            abort(404);
        }

        $warehouse = Warehouse::find($warehouse);

        return view('admin-edit-shelves', compact('shelf', 'warehouse'));
    }

    public function create($warehouse)
    {
        return view('admin-create-shelves', compact('warehouse'));
    }

    public function store(Request $request, $warehouse)
    {
        $lastShelf = Cabinet::where('warehouse_id', $warehouse)->orderBy('id', 'desc')->first();
        $newShelfNumber = $lastShelf ? $lastShelf->id + 1 : 1;

        $request->validate([
            'front_view' => 'required|mimes:png,jpg,jpeg',
        ]);

        $shelfLayout = $request->file('front_view');
        $filename = null;

        if ($shelfLayout) {
            $filename = $shelfLayout->getClientOriginalName();
            $shelfLayout->storeAs('public', $filename);
        }

        Cabinet::create([
            'id' => $newShelfNumber,
        ]);

        return redirect()->route('admin.warehouses.edit', ['warehouse' => $warehouse])
            ->with('success_message', 'Prateleira criada com sucesso.');
    }

    public function remove($warehouse, $shelf)
    {
        try {
            $deleted = Cabinet::where('id', $shelf)
                ->where('warehouse_id', $warehouse)
                    ->forceDelete();

            if ($deleted) {
                return redirect()->route('admin.warehouses.edit', ['warehouse' => $warehouse])
                    ->with('success_message', 'Prateleira removida com sucesso.');
            } else {
                return redirect()->route('admin.warehouses.edit', ['warehouse' => $warehouse])
                    ->with('error_message', 'Falha ao remover a prateleira.');
            }
        } catch (\Exception $e) {
            \Log::error('Existe um material associado a essa prateleira, retire o material da prateleira antes de remove-lá ' . $e->getMessage());
            return redirect()->route('admin.warehouses.edit', ['warehouse' => $warehouse])
                ->with('error_message', 'Erro inesperado ao remover a prateleira.');
        }
    }

    public function updateLocation(Request $request, $shelfId, $warehouseId)
    {
        try {
            $request->validate([
                'top' => 'required|numeric',
                'left' => 'required|numeric',
            ]);

            $shelf = Cabinet::where(['id' => $shelfId, 'warehouse_id' => $warehouseId])->first();

            if (!$shelf) {
                Log::error('Prateleira não encontrada para id: ' . $shelfId . ' e warehouse_id: ' . $warehouseId);
                return abort(404);
            }


            $updated = $shelf->where('id', $shelfId)->where('warehouse_id', $warehouseId)->update([
                'top' => $request->input('top'),
                'left' => $request->input('left'),
            ]);

            if ($updated) {
                return response()->json(['message' => 'Posição da prateleira atualizada com sucesso.']);
            } else {
                return response()->json(['error' => 'Erro ao atualizar a posição da prateleira.'], 500);
            }
        } catch (\Exception $e) {
            Log::error('Erro durante o update: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

}
