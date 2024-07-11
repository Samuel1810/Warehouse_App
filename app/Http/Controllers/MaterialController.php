<?php

namespace App\Http\Controllers;

use App\Helpers\SetaDataHelper;
use App\Mail\MaterialDevolution;
use App\Mail\MaterialAcquisition;
use App\Models\MaterialProjects;
use App\Models\Project;
use App\Models\UserMaterial;
use App\Models\UserProject;
use App\Models\WarehouseStockMovement;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\PurchaseMaterial;
use App\Models\Movement;
use App\Models\Material;
use App\Models\Warehouse;
use App\Models\Shelf;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class MaterialController extends Controller
{

    public function showHistoricoCompras(Material $material)
    {
        return view('purchased_materials', [
            'material' => $material,
        ]);
    }
                    
    public function showAllMaterials(){
        $materials = Material::all();


        if(auth()->user()->id === 1){
            return view('admin-materials', compact('materials'));
        } else {
            return view('user-materials', compact('materials'));
        }
    }

    public function search(Request $request){
        $materials = Material::where('nome', 'LIKE', '%'.$request['search'].'%')
            ->get();

        return view('admin-materials', compact('materials'));
    }

    public function showMaterialProjects($materialId){
        $material = Material::findOrFail($materialId);

        $materialsProjects = DB::table('warehouse_stock_movements')
            ->select([
                'material_id',
                'project_id',
                'warehouse_id',
                'cabinet_id',
                DB::raw('COUNT(*) as id'),
                DB::raw('SUM(quantity) as quantidadeInicial')
            ])
            ->groupBy(['material_id', 'project_id', 'warehouse_id', 'cabinet_id'])
                ->where('material_id', $materialId)
                    ->get();

                foreach ($materialsProjects as $project) {
                    $requisitionQuantity = DB::table('movements')
                        ->where('material_id', $project->material_id)
                            ->where('project_id', $project->project_id)
                                ->where('warehouse_id', $project->warehouse_id)
                                    ->where('cabinet_id', $project->cabinet_id)
                                        ->where('tipo_movimento', 1) // 1 para requisição
                                            ->sum('quantidade');
            
                    $devolutionQuantity = DB::table('movements')
                        ->where('material_id', $project->material_id)
                            ->where('project_id', $project->project_id)
                                ->where('warehouse_id', $project->warehouse_id)
                                    ->where('cabinet_id', $project->cabinet_id)
                                        ->where('tipo_movimento', 0) // 0 para devolução
                                            ->sum('quantidade');

                    $project->quantidade = $project->quantidadeInicial - $requisitionQuantity + $devolutionQuantity;
                }
                                
        if(auth()->user()->id === 1) {
            return view('admin-material-projects', compact('materialsProjects', 'material'));
        } else {
            return view('user-material-projects', compact('materialsProjects', 'material'));
        }
    }

    public function showMaterial($projectId, $materialId, $warehouseId, $cabinetId){
        $project = Project::findOrFail($projectId);
        $material = Material::findOrFail($materialId);

        $quantity = DB::table('warehouse_stock_movements')
        ->where('material_id', $materialId)
            ->where('project_id', $projectId)
                ->where('warehouse_id', $warehouseId)
                    ->where('cabinet_id', $cabinetId)
                        ->sum('quantity');
        
        $requisitionQuantity = DB::table('movements')
            ->where('material_id', $materialId)
                ->where('project_id', $projectId)
                    ->where('warehouse_id', $warehouseId)
                        ->where('cabinet_id', $cabinetId)
                            ->where('tipo_movimento', 1)
                                ->sum('quantidade');

        $devolutionQuantity = DB::table('movements')
            ->where('material_id', $materialId)
                ->where('project_id', $projectId)
                    ->where('warehouse_id', $warehouseId)
                        ->where('cabinet_id', $cabinetId)
                            ->where('tipo_movimento', 0)
                                ->sum('quantidade');

        $material->quantidade = $quantity - $requisitionQuantity + $devolutionQuantity;

        $materialProject = WarehouseStockMovement::where('material_id', $materialId)
            ->where('project_id', $projectId)
                ->where('warehouse_id', $warehouseId)
                    ->where('cabinet_id', $cabinetId)
                        ->firstOrFail();

        return view('material-info', compact('project', 'material', 'materialProject'));
    }

    public function acquire(Request $request, $projectId, $materialId, $warehouseId, $cabinetId) {
        $request->validate([
            'materialQuantity' => 'required|integer|min:1',
            'quantidade_desejada' => 'required|integer|min:1',
        ]);

        $material = Material::find($materialId);
        $matQtd = $request->input('materialQuantity');
        $quantidadeDesejada = $request->input('quantidade_desejada');
    
        if ($quantidadeDesejada > $matQtd) {
            return redirect()->route('material.show', [
                'projectId' => $projectId,
                'materialId' => $material->id,
                'warehouseId' => $warehouseId,
                'cabinetId' => $cabinetId
            ])->with('error_message', 'Valor de requisição ultrapassa o valor existente!');
        }

        DB::beginTransaction();

        try {
            Movement::create([
                'user_id' => auth()->user()->id,
                'material_id' => $material->id,
                'project_id' => $projectId,
                'warehouse_id' => $warehouseId,
                'cabinet_id' => $cabinetId,
                'quantidade' => $quantidadeDesejada,
                'data_movimento' => now(),
                'tipo_movimento' => 1, // requisição
            ]);

            $material->quantidade -= $quantidadeDesejada;
            $material->save();

            DB::commit();

            // Mail::to('samuel18timao@gmail.com')->send(new MaterialAcquisition($material, $quantidadeDesejada, 'Aquisição', now(), auth()->user()));

            // Mail::to($user->email)->send(new MaterialAcquisition($material, $quantidadeDesejada, 'Aquisição', now(), auth()->user()));

            return redirect()->route('material.show', [
                'projectId' => $projectId,
                'materialId' => $material->id,
                'warehouseId' => $warehouseId,
                'cabinetId' => $cabinetId
            ])->with('success_message', 'Aquisição realizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao enviar solicitação: ' . $e->getMessage(), [
                'projectId' => $projectId,
                'materialId' => $material->id,
                'warehouseId' => $warehouseId,
                'cabinetId' => $cabinetId,
                'exception' => $e,
            ]);
            return redirect()->route('material.show', [
                'projectId' => $projectId,
                'materialId' => $material->id,
                'warehouseId' => $warehouseId,
                'cabinetId' => $cabinetId
            ])->with('error_message', 'Erro ao realizar aquisição.');
        }
    }

    public function return(Request $request, $projectId, $materialId, $warehouseId, $cabinetId) {
        $request->validate([
            'materialQuantity' => 'required|integer|min:1',
            'quantidade_devolucao' => 'required|integer|min:1',
        ]);

        $material = Material::find($materialId);
        $matQtd = $request->input('materialQuantity');
        $quantidadeDevolucao = $request->input('quantidade_devolucao');

        if ($quantidadeDevolucao > $matQtd) {
            return redirect()->route('material.show', [
                'projectId' => $projectId,
                'materialId' => $material->id,
                'warehouseId' => $warehouseId,
                'cabinetId' => $cabinetId
            ])->with('error_message1', 'Valor de devolução ultrapassa o valor existente!');
        }

        DB::beginTransaction();

        try {
            Movement::create([
                'user_id' => auth()->user()->id,
                'material_id' => $material->id,
                'project_id' => $projectId,
                'warehouse_id' => $warehouseId,
                'cabinet_id' => $cabinetId,
                'quantidade' => $quantidadeDevolucao,
                'data_movimento' => now(),
                'tipo_movimento' => 0, // 0 para devolução
            ]);

            $material->quantidade += $quantidadeDevolucao;
            $material->save();

            DB::commit();

            // Mail::to('samuel18timao@gmail.com')->send(new MaterialDevolution($material, $quantidadeDevolucao, 'Devolução', now(), auth()->user()));

            // $material->owners->each(function ($owner) use ($material, $quantidadeDevolucao) {
            //     Mail::to($owner->email)->send(new MaterialDevolution($material, $quantidadeDevolucao, 'Devolução', now(), auth()->user()));
            // });

            // Mail::to($user->email)->send(new MaterialDevolution($material, $quantidadeDevolucao, 'Devolução', now(), auth()->user()));

            return redirect()->route('material.show', [
                'projectId' => $projectId,
                'materialId' => $material->id,
                'warehouseId' => $warehouseId,
                'cabinetId' => $cabinetId
            ])->with('success_message1', 'Devolução realizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao enviar solicitação: ' . $e->getMessage(), [
                'projectId' => $projectId,
                'materialId' => $material->id,
                'warehouseId' => $warehouseId,
                'cabinetId' => $cabinetId,
                'exception' => $e,
            ]);
            return redirect()->route('material.show', [
                'projectId' => $projectId,
                'materialId' => $material->id,
                'warehouseId' => $warehouseId,
                'cabinetId' => $cabinetId
            ])->with('error_message1', 'Erro ao realizar devolução.');
        }
    }

    public function stock(){
        $materials = Material::all();
        return view('stock', compact('materials'));
    }

    public function storePurchase(Request $request, $material)
{
    try {
        $request->validate([
            'date' => 'required|date',
            'quantity' => 'required|numeric',
            'supplier' => 'required|numeric',
            'manufacturer' => 'required|numeric',
            'payment_proof' => 'nullable|mimes:png,jpg,jpeg,pdf',
            'movement_type' => 'required|numeric',
        ]);

        $paymentProof = $request->file('payment_proof');
        $filename = null;

        if ($paymentProof) {
            $filename = $paymentProof->getClientOriginalName();
            $paymentProof->storeAs('public', $filename);
        }

        PurchaseMaterial::create([
            'date' => $request->input('date'),
            'quantity' => $request->input('quantity'),
            'supplier_id' => $request->input('supplier'),
            'manufacturer_id' => $request->input('manufacturer'),
            'material_id' => $material,
            'user_id' => auth()->user()->id,
            'payment_proof' => $filename,
            'movement_type' => $request->input('movement_type'),
        ]);

        $materialInstance = Material::find($material);
        $materialInstance->quantidade += $request->input('quantity');
        $materialInstance->save();

        return redirect()->route('material.historico-compras', compact('material'))->with('success_message', 'Compra adicionada com sucesso!');
    } catch (ValidationException $e) {
        return response()->json(['error' => $e->errors()], 422);
    } catch (QueryException $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

    public function storeDevolution(Request $request, $material)
    {
        try {
            $request->validate([
                'date' => 'required|date',
                'quantity' => 'required|numeric',
                'supplier' => 'required|numeric',
                'manufacturer' => 'required|numeric',
                'movement_type' => 'required|numeric',
            ]);

            PurchaseMaterial::create([
                'date' => $request->input('date'),
                'quantity' => $request->input('quantity'),
                'supplier_id' => $request->input('supplier'),
                'manufacturer_id' => $request->input('manufacturer'),
                'material_id' => $material,
                'user_id' => auth()->user()->id,
                'movement_type' => $request->input('movement_type'),
            ]);

            $materialInstance = Material::find($material);
            $materialInstance->quantidade -= $request->input('quantity');
            $materialInstance->save();

            return redirect()->route('material.historico-compras',compact('material'))->with('success_message', 'Devolução realizada com sucesso!');
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors()], 422);
        } catch (QueryException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function editMaterial(Material $material) {
        return view('admin-edit-material', compact('material'));
    }
    
    public function updateMaterial(Request $request, Material $material) {
        $request->validate([
            'quantidade' => 'required|numeric|min:0.1',
            'ratio' => 'required|in:adicionar,remover',
        ], [
            'quantidade.required' => 'O campo quantidade é obrigatório!',
            'ratio.required' => 'Você precisa selecionar se deseja adicionar ou remover quantidade!'
        ]);
    
        $quantidadeForm = $request->quantidade;
    
        if ($request->ratio == 'adicionar') {
            $material->quantidade += $quantidadeForm;
        } else if ($request->ratio == 'remover') {
            if ($material->quantidade >= $quantidadeForm) {
                $material->quantidade -= $quantidadeForm;
            } else {
                return redirect()->route('admin.stock.edit', ['material' => $material->id])->with('error_message', 'A quantidade a ser removida é maior do que a quantidade disponível no stock!');
            }
        }
    
        $material->save();
    
        return redirect()->route('admin.stock.edit', ['material' => $material->id])->with('success_message', 'Quantidade do material atualizada com sucesso!');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($request->has('redirectToRejectionForm')) {
            return redirect()->route('material.movement.reject.form');
        }

        return redirect()->intended($this->redirectPath());
    }


    public function showRejectionForm(Request $request, Material $material, $movementId, $token)
    {
        // Decodifica o token para obter o ID do usuário
        $userId = decrypt($token);

        // Autentica o usuário usando o ID obtido do token
        $user = User::find($userId);

        if (!$user) {
            // Lidar com o caso em que o usuário não foi encontrado
            abort(404, 'Usuário não encontrado.');
        }

        // Autentica o usuário, se aplicável
        auth()->login($user);

        // Recupera a movimentação específica pelo ID
        $movement = Movement::where('user_id', $userId)
            ->where('material_id', $material->id)
            ->where('id', $movementId)
            ->first();

        if (!$movement) {
            // Lidar com o caso em que a movimentação não foi encontrada
            abort(404, 'Movimentação não encontrada.');
        }

        // Carrega a view com os detalhes da movimentação
        return view('material-rejection-form', [
            'user' => auth()->user(),
            'material' => $material,
            'tipoMovimento' => $movement->tipo_movimento,
            'quantidadeMovimentada' => $movement->quantidade,
            'token' => $token,
        ]);
    }


    public function rejectMovement(Request $request, Material $material, $token)
    {
        $motivo = $request->input('motivo');

        return 'funcionou';
    }
}