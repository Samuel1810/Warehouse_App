<?php

namespace App\Http\Controllers;

use App\Models\Cabinet;
use App\Models\Material;
use App\Models\Project;
use App\Models\Warehouse;
use App\Models\WarehouseStockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class WarehouseController extends Controller
{
    public function index()
    {
        $warehouses = Warehouse::all();

        return view('admin-warehouses', compact('warehouses'));
    }

    public function create()
    {
        return view('admin-create-warehouses');
    }

    public function store(Request $request)
    {
        $lastWarehouse = Warehouse::orderBy('id', 'desc')->first();

        $newWarehouseNumber = $lastWarehouse ? $lastWarehouse->id + 1 : 1;

        $newWarehouse = new Warehouse();
        $newWarehouse->id = $newWarehouseNumber;

        // $newWarehouse->top = 85;
        // $newWarehouse->left = 10;

        $request->validate([
            'layout' => 'required|mimes:png,jpg,jpeg',
        ]);

        $warehouseLayout = $request->file('layout');
        $filename = null;

        if($warehouseLayout){
            $filename = $warehouseLayout->getClientOriginalName();
            $warehouseLayout->storeAs('public', $filename);
        }
    
        $newWarehouse->layout = $filename;

        $newWarehouse->save();

        return redirect()->route('admin.warehouses.index')->with('success_message', 'Novo armazém criado com sucesso.');
    }

    public function edit(Warehouse $warehouse)
    {
        $materials = Material::all();
        $cabinets = Cabinet::all();
        $projects = Project::all();
        return view('admin-edit-warehouses', compact('warehouse', 'materials', 'cabinets', 'projects'));
    }

    public function remove(Warehouse $warehouse)
    {
        $warehouse->delete();

        return redirect()->route('admin.warehouses.index')->with('success_message', 'Armazém removido com sucesso.');
    }

    public function updateLocation(Request $request, Warehouse $warehouse)
    {
        $request->validate([
            'top' => 'required|numeric',
            'left' => 'required|numeric',
        ]);

        $warehouse->update([
            'top' => $request->top,
            'left' => $request->left,
        ]);

        return response()->json(['message' => 'Posição do armazém atualizada com sucesso.']);
    }

    public function warehouseStockMovement(Request $request){
        $warehouse = Warehouse::find($request->input('warehouse'));
        $material = Material::find($request->input('material'));
        $cabinets = Cabinet::all();
        $materials = Material::all();
        $projects = Material::all();
        
        WarehouseStockMovement::create([
            'warehouse_id' => $request->input('warehouse'),
            'cabinet_id' => $request->input('cabinet'),
            'material_id' => $request->input('material'),
            'project_id' => $request->input('project'),
            'quantity' => $request->input('quantity'),
            'top' => 85,
            'left' => 10,
        ]);

        $material->quantidade -= $request->input('quantity'); 
        $material->save();

        return view('admin-edit-warehouses', compact('warehouse', 'cabinets', 'materials', 'projects'));
    }
}
