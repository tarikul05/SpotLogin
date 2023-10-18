<?php

namespace App\Http\Controllers;

use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends Controller
{

    public function index()
    {
        $taskCount = Alert::count();
        $alerts = Alert::all();
        return view('pages.admin.alerts', compact('taskCount', 'alerts'));
    }

    public function countAlerts()
    {
        $alertCount = Alert::count();
        return view('pages.admin.alerts', compact('alertCount'));
    }

    public function showCreateForm()
    {
        return view('pages.admin.add-alert');
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
            $task = Alert::create([
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
