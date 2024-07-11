<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialProject;
use App\Models\Project;
use App\Models\User;
use App\Models\UserMaterial;
use App\Models\UserProject;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(){
        $projects = Project::all();

        return view('admin-manage-projects', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'description' => 'required|string'
        ]);

        $lastProject = Project::orderBy('id', 'desc')->first();

        $newProjectNumber = $lastProject ? $lastProject->id + 1 : 1;

        $project = new Project();
        $project->id = $newProjectNumber;
        $project->owner_id = 1;
        $project->description = $request->input('description');
        $project->save();

        $projects = Project::all();

        return view('admin-manage-projects', compact('projects'))->with('success_message', 'Novo projeto criado com sucesso.');
    }

    public function remove(Project $projectId)
    {
        $projectId->delete();

        $projects = Project::all();
        return view('admin-manage-projects', compact('projects'))->with('success_message', 'Projeto removido com sucesso.');
    }

    public function edit($projectId){
        $project = Project::findOrFail($projectId);
       // Get members
       $projectParticipants = UserProject::where('project_id', $projectId)
       ->with('users')
           ->get();

        // Get materials
        $projectMaterials = MaterialProject::where('project_id', $projectId)
            ->with('materials')
                ->get();
        
        // Obter os IDs dos materiais associados ao projeto
        $materialsProjectIds = MaterialProject::where('project_id', $projectId)
        ->pluck('material_id')
        ->toArray();
        // Obter os materiais que não estão associados ao projeto
        $materialsNonAssociate = Material::whereNotIn('id', $materialsProjectIds)
        ->get();

        // Get non owners
        $projectOwner = Project::where('id', $projectId)
        ->pluck('owner_id')
            ->toArray();
        $nonOwnerUsers = User::whereNotIn('id', $projectOwner)
            ->get();

        // Get members id's
        $projectUserIds = UserProject::where('project_id', $projectId)
            ->pluck('user_id')
                ->toArray();
   
        $projectOwnerId = $project->owner_id;
        $projectUserIds[] = $projectOwnerId;

        // Get non members
        $nonMemberUsers = User::whereNotIn('id', $projectUserIds)
        ->get();

        return view('admin-edit-projects', compact('project', 'projectParticipants', 'projectMaterials', 'nonOwnerUsers', 'nonMemberUsers', 'materialsNonAssociate'));
    }

    public function switchOwner(Request $request){
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'project_owner' => 'required|exists:users,id'
        ]);

        $projectId = $request->input('project_id');
        $newOwnerId = $request->input('project_owner');

        $project = Project::find($projectId);

        $project->owner_id = $newOwnerId;

        $project->save();

        // Get members
        $projectParticipants = UserProject::where('project_id', $projectId)
            ->with('users')
                ->get();

        // Get materials
        $projectMaterials = MaterialProject::where('project_id', $projectId)
            ->with('materials')
                ->get();
        
        // Obter os IDs dos materiais associados ao projeto
        $materialsProjectIds = MaterialProject::where('project_id', $projectId)
        ->pluck('material_id')
        ->toArray();
        // Obter os materiais que não estão associados ao projeto
        $materialsNonAssociate = Material::whereNotIn('id', $materialsProjectIds)
        ->get();

        // Get non owners
        $projectOwner = Project::where('id', $request->input('project_id'))
        ->pluck('owner_id')
            ->toArray();
        $nonOwnerUsers = User::whereNotIn('id', $projectOwner)
            ->get();

        // Get members id's
        $projectUserIds = UserProject::where('project_id', $projectId)
            ->pluck('user_id')
                ->toArray();
        
        $projectOwnerId = $project->owner_id;
        $projectUserIds[] = $projectOwnerId;

        // Get non members
        $nonMemberUsers = User::whereNotIn('id', $projectUserIds)
        ->get();

        return view('admin-edit-projects', compact('project','projectParticipants', 'projectMaterials', 'nonOwnerUsers', 'nonMemberUsers', 'materialsNonAssociate'))->with('success_message', 'Proprietario atualizado com sucesso.');
    }

    public function addMember(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);

        $request->validate([
            'project_member' => 'required|exists:users,id'
        ]);
    
        UserProject::create([
            'user_id' => $request->input('project_member'),
            'project_id' => $projectId
        ]);

         // Get members
         $projectParticipants = UserProject::where('project_id', $projectId)
         ->with('users')
             ->get();

        // Get materials
        $projectMaterials = MaterialProject::where('project_id', $projectId)
            ->with('materials')
                ->get();

        // Obter os IDs dos materiais associados ao projeto
        $materialsProjectIds = MaterialProject::where('project_id', $projectId)
        ->pluck('material_id')
        ->toArray();
        // Obter os materiais que não estão associados ao projeto
        $materialsNonAssociate = Material::whereNotIn('id', $materialsProjectIds)
        ->get();

        // Get non owners
        $projectOwner = Project::where('id', $request->input('project_id'))
        ->pluck('owner_id')
            ->toArray();
        $nonOwnerUsers = User::whereNotIn('id', $projectOwner)
            ->get();

        // Get members id's
        $projectUserIds = UserProject::where('project_id', $projectId)
            ->pluck('user_id')
                ->toArray();
        
        $projectOwnerId = $project->owner_id;
        $projectUserIds[] = $projectOwnerId;

        // Get non members
        $nonMemberUsers = User::whereNotIn('id', $projectUserIds)
        ->get();

        return view('admin-edit-projects', compact('project','projectParticipants', 'projectMaterials', 'nonOwnerUsers', 'nonMemberUsers', 'materialsNonAssociate'))->with('success_message', 'Membro adicionado com sucesso.');
    }

    public function removeMember(Request $request, $projectId, $userId)
    {
        $project = Project::findOrFail($projectId);
        UserProject::where('project_id', $projectId)->where('user_id', $userId)->delete();

        // Get members
        $projectParticipants = UserProject::where('project_id', $projectId)
            ->with('users')
                ->get();

        // Get materials
        $projectMaterials = MaterialProject::where('project_id', $projectId)
            ->with('materials')
                ->get();

        // Obter os IDs dos materiais associados ao projeto
        $materialsProjectIds = MaterialProject::where('project_id', $projectId)
        ->pluck('material_id')
        ->toArray();
        // Obter os materiais que não estão associados ao projeto
        $materialsNonAssociate = Material::whereNotIn('id', $materialsProjectIds)
        ->get();

        // Get non owners
        $projectOwner = Project::where('id', $request->input('project_id'))
        ->pluck('owner_id')
            ->toArray();
        $nonOwnerUsers = User::whereNotIn('id', $projectOwner)
            ->get();

        // Get members id's
        $projectUserIds = UserProject::where('project_id', $projectId)
            ->pluck('user_id')
                ->toArray();
        
        $projectOwnerId = $project->owner_id;
        $projectUserIds[] = $projectOwnerId;

        // Get non members
        $nonMemberUsers = User::whereNotIn('id', $projectUserIds)
        ->get();

        return view('admin-edit-projects', compact('project','projectParticipants', 'projectMaterials', 'nonOwnerUsers', 'nonMemberUsers', 'materialsNonAssociate'))->with('success_message', 'Membro removido com sucesso.');
    }

    public function addMaterial(Request $request, $projectId)
    {
        $project = Project::findOrFail($projectId);

        $request->validate([
            'project_material' => 'required|exists:users,id'
        ]);
    
        MaterialProject::create([
            'material_id' => $request->input('project_material'),
            'project_id' => $projectId
        ]);

         // Get members
         $projectParticipants = UserProject::where('project_id', $projectId)
         ->with('users')
             ->get();

        // Get materials
        $projectMaterials = MaterialProject::where('project_id', $projectId)
            ->with('materials')
                ->get();

        // Obter os IDs dos materiais associados ao projeto
        $materialsProjectIds = MaterialProject::where('project_id', $projectId)
        ->pluck('material_id')
        ->toArray();
        // Obter os materiais que não estão associados ao projeto
        $materialsNonAssociate = Material::whereNotIn('id', $materialsProjectIds)
        ->get();

        // Get non owners
        $projectOwner = Project::where('id', $request->input('project_id'))
        ->pluck('owner_id')
            ->toArray();
        $nonOwnerUsers = User::whereNotIn('id', $projectOwner)
            ->get();

        // Get members id's
        $projectUserIds = UserProject::where('project_id', $projectId)
            ->pluck('user_id')
                ->toArray();
        
        $projectOwnerId = $project->owner_id;
        $projectUserIds[] = $projectOwnerId;

        // Get non members
        $nonMemberUsers = User::whereNotIn('id', $projectUserIds)
        ->get();

        return view('admin-edit-projects', compact('project','projectParticipants', 'projectMaterials', 'nonOwnerUsers', 'nonMemberUsers', 'materialsNonAssociate'))->with('success_message', 'Membro adicionado com sucesso.');
    }

    public function removeMaterial(Request $request, $projectId, $materialId)
    {
        $project = Project::findOrFail($projectId);

        MaterialProject::where('project_id', $projectId)
        ->where('material_id', $materialId)
            ->delete();

        // Get members
        $projectParticipants = UserProject::where('project_id', $projectId)
            ->with('users')
                ->get();

        // Get materials
        $projectMaterials = MaterialProject::where('project_id', $projectId)
            ->with('materials')
                ->get();
        
        // Obter os IDs dos materiais associados ao projeto
        $materialsProjectIds = MaterialProject::where('project_id', $projectId)
        ->pluck('material_id')
        ->toArray();
        // Obter os materiais que não estão associados ao projeto
        $materialsNonAssociate = Material::whereNotIn('id', $materialsProjectIds)
        ->get();

        // Get non owners
        $projectOwner = Project::where('id', $request->input('project_id'))
        ->pluck('owner_id')
            ->toArray();
        $nonOwnerUsers = User::whereNotIn('id', $projectOwner)
            ->get();

        // Get members id's
        $projectUserIds = UserProject::where('project_id', $projectId)
            ->pluck('user_id')
                ->toArray();
        
        $projectOwnerId = $project->owner_id;
        $projectUserIds[] = $projectOwnerId;

        // Get non members
        $nonMemberUsers = User::whereNotIn('id', $projectUserIds)
        ->get();

        return view('admin-edit-projects', compact('project','projectParticipants', 'projectMaterials', 'nonOwnerUsers', 'nonMemberUsers', 'materialsNonAssociate'))->with('success_message', 'Membro removido com sucesso.');
    }
}
