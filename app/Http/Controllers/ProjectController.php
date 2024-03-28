<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use App\Models\UserProject;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(){
        $projects = Project::all();

        return view('admin-manage-projects', compact('projects'));
    }

    public function edit($projectId){
        $project = Project::findOrFail($projectId);
        $projectParticipants = UserProject::where('project_id', $projectId)->with('users')
            ->get();


        // Catch non participants of the selected project
        $projectUserIds = UserProject::where('project_id', $projectId)
            ->pluck('user_id')
                ->toArray();

        $projectUserIds[] = $project->owner;
        
        $usersNotInProject = User::whereNotIn('id', $projectUserIds)
            ->get();

        return view('admin-edit-projects', compact('project', 'projectParticipants', 'usersNotInProject'));
    }
}
