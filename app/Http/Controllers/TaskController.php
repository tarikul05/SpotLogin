<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $taskCount = Task::count();
        $tasks = Task::all();
        return view('pages.admin.tasks', compact('taskCount', 'tasks'));
    }

    public function countTasks()
    {
        $taskCount = Task::count();
        return view('pages.admin.tasks', compact('taskCount'));
    }

    public function showCreateForm()
    {
        return view('pages.admin.add-task');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'priority' => 'required|numeric',
        ]);

        try {
            //save task
            $task = Task::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'priority' => $data['priority'],
            ]);
        } catch (\Exception $e) {
            return redirect()->route('tasks.create')->with('error', 'Erreur lors de la création du coupon : ' . $e->getMessage());
        }

    return redirect()->route('task.index')->with('success', 'Task créée avec succès.');
    }
}
