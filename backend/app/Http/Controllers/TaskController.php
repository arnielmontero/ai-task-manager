<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;

class TaskController extends Controller
{
    // In Project model — add tasks relationship 
    public function tasks() { 
        return $this->hasMany(Task::class); 
    } 
    // TaskController methods 
    public function index(Project $project) {
        
        return $project->tasks()->orderBy('due_date')->get(); 
    }
    public function store(Request $request, Project $project) {
        $task = $project->tasks()->create($request->all());
        
        return response()->json($task, 201); 
    }
    public function update(Request $request, Project $project, Task $task) {
        $task->update($request->all()); 
        
        return response()->json($task); 
    } 
    public function destroy(Project $project, Task $task) {
        $task->delete(); 
        
        return response()->json(null, 204); 
    }
}
