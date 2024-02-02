<?php

namespace App\Http\Controllers;

use App\Models\Task;

class PlanningController extends Controller
{
    public function index()
{
     
    $tasks = Task::all(); 
    return view('planning.index', compact('tasks'));
}
 }
