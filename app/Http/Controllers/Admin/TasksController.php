<?php

namespace App\Http\Controllers\Admin;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TasksController extends Controller
{
    public function index()
    {
        $tasks = Task::paginate(15);

        return view('admin.tasks.index', compact('tasks'));
    }
}
