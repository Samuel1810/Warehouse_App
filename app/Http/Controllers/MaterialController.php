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

    public function index(Request $request)
    {
        $user = auth()->user()->id;
        $userProjects = UserProject::where('user_id', $user)
            ->get();

        $ownerProjects = Project::where('owner_id', $user)
            ->get();

        if(auth()->user()->id === 1) {
            return view('admin-projects', compact('userProjects', 'ownerProjects'));
        } else {
            return view('user-projects', compact('userProjects', 'ownerProjects'));
        }
    }
                    
    public function materialProjects($projectId){
        $project = Project::findOrFail($projectId);
        $materialProjects = $project->materials;

        // $qtdMaterial = WarehouseStockMovement::where('project_id', $project)->where('material_id', $request->input('material'));

        if(auth()->user()->id === 1){
            return view('admin-materials', compact('project', 'materialProjects'));
        } else {
            return view('user-materials', compact('project', 'materialProjects'));
        }
    }

    public function acquired(Request $request, $project, $material) {
        $material = Material::find($material);
    
        if (!$material) {
            return redirect()->route('material.acquisition', ['project' => $project, 'material' => $material->id])->with('error_message', 'Material não encontrado.');
        }
    
        $quantidadeDesejada = request('quantidade_desejada');
    
        if ($quantidadeDesejada == '' || $quantidadeDesejada == '0') {
            return redirect()->route('material.acquisition', ['project' => $project, 'material' => $material->id])->with('empty_string_error', 'Insira um valor válido!');
        }
    
        $request->validate([
            'quantidade_desejada' => 'required|numeric|min:0.1',
        ]);
        
        if($quantidadeDesejada <= $material->quantidade){
            $currentDatetime = now();

            try {
                \DB::beginTransaction();

                $material->quantidade -= $quantidadeDesejada;
                $material->save();

                Movement::create([
                    'user_id' => auth()->user()->id,
                    'material_id' => $material->id,
                    'quantidade' => $quantidadeDesejada,
                    'data_movimento' => $currentDatetime,
                    'tipo_movimento' => 1, // aquisição
                ]);

                \DB::commit();

                // Verifica se há um usuário autenticado
                $user = auth()->user();

                // Mail::to('samuel18timao@gmail.com')->send(new MaterialAcquisition($material, $quantidadeDesejada, 'Aquisição', now(), auth()->user()));

                // Mail::to($user->email)->send(new MaterialAcquisition($material, $quantidadeDesejada, 'Aquisição', now(), auth()->user()));

                return redirect()->route('material.acquisition', ['project' => $project, 'material' => $material->id])->with('success_message', 'Solicitação enviada com sucesso!');
            } catch (\Exception $e) {
                \DB::rollBack();
                \Log::error($e);
        
                return redirect()->route('material.acquisition', ['project' => $project, 'material' => $material->id])->with('error_message', 'Erro ao enviar solicitação.');
            }
        }

    }

    public function returnMaterial(Request $request, $material) {
        $material = Material::find($material);

        if (!$material) {
            return redirect()->route('material.acquisition')->with('error_message1', 'Material não encontrado.');
        }

        $quantidadeDevolucao = request('quantidade_devolucao');

        if ($quantidadeDevolucao == '' || $quantidadeDevolucao == '0') {
            return redirect()->route('material.acquisition', ['material' => $material->id])->with('empty_string_error1', 'Insira um valor válido!');
        }

        $request->validate([
            'quantidade_devolucao' => 'required|numeric|min:0.1',
        ]);

        $currentDatetime = now();

        try {
            // Iniciar uma transação para garantir consistência do banco de dados
            \DB::beginTransaction();

            // Atualize a quantidade disponível do material
            $material->quantidade += $quantidadeDevolucao;
            $material->save();

            // Crie um novo registro de movimento para a devolução
            Movement::create([
                'user_id' => auth()->user()->id,
                'material_id' => $material->id,
                'quantidade' => $quantidadeDevolucao,
                'data_movimento' => $currentDatetime,
                'tipo_movimento' => 0, // 0 para devolução
            ]);

            // Verificar se o usuário possui um registro na tabela user_materials para esse material
            $userMaterial = auth()->user()->materials()->find($material->id);
            $quantidadeEmPosse = $userMaterial->pivot->quantidade;

            if ($quantidadeDevolucao > $quantidadeEmPosse) {
                return redirect()->route('material.acquisition', ['material' => $material->id])->with('error_message1', 'Quantidade de devolução maior que a de posse.');
            }

            // Se existir, atualizar a quantidade
            if ($userMaterial) {
                $userMaterial->pivot->quantidade -= $quantidadeDevolucao;
                $userMaterial->pivot->save();
            }

            // Confirme a transação, salvando todas as alterações no banco de dados
            \DB::commit();

            // Verifica se há um usuário autenticado
            $user = auth()->user();

            Mail::to('samuel18timao@gmail.com')->send(new MaterialDevolution($material, $quantidadeDevolucao, 'Devolução', now(), auth()->user()));

            $material->owners->each(function ($owner) use ($material, $quantidadeDevolucao) {
                Mail::to($owner->email)->send(new MaterialDevolution($material, $quantidadeDevolucao, 'Devolução', now(), auth()->user()));
            });

            Mail::to($user->email)->send(new MaterialDevolution($material, $quantidadeDevolucao, 'Devolução', now(), auth()->user()));

            return redirect()->route('material.acquisition', ['material' => $material->id])->with('success_message1', 'Devolução realizada com sucesso!');
        } catch (\Exception $e) {
            // Em caso de erro, reverta todas as alterações no banco de dados
            \DB::rollBack();

            return redirect()->route('material.acquisition', ['material' => $material->id])->with('error_message1', 'Erro ao realizar a devolução do material.');
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

    public function purchases($project, $material){
        $project = Project::findOrFail($project);
        $material = Material::find($material);

        return view('material-acquisition',compact('material', 'project'));
    }
}