<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index() { 
        return auth()->user()->projects()->withCount('tasks')->get(); 
    }
    
    public function store(Request $request) {
        $project = auth()->user()->projects()->create($request->all()); 
        
        return response()->json($project, 201); 
    } 

    public function update(Request $request, Project $project) {
        if ($project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $project->update($request->all()); 
        return response()->json($project); 
    } 

    public function destroy(Project $project) {
        if ($project->user_id !== auth()->id()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $project->delete(); 
        
        return response()->json(null, 204); 
    }
}
