<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        $project->update($request->all()); 
        return response()->json($project); 
    } 

    public function destroy(Project $project) {
        $project->delete(); 
        
        return response()->json(null, 204); 
    }
}
