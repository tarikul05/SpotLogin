<?php

namespace App\Http\Controllers;

use App\Models\Widget;
use App\Models\UserWidget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WidgetController extends Controller
{
    // Show all available widgets and user's selections
    public function index()
    {
        $widgets = Widget::all();
        $userWidgets = UserWidget::where('user_id', Auth::id())->pluck('is_active', 'widget_id')->toArray();

        return view('widgets.index', compact('widgets', 'userWidgets'));
    }

    // Store or update user's widget selections
    public function saveUserWidgets(Request $request)
    {
        $userId = Auth::id();
        $selectedWidgets = $request->input('widgets', []); // Array of widget IDs that are selected

        // Fetch all widget IDs currently associated with the user
        $currentWidgetIds = UserWidget::where('user_id', $userId)->pluck('widget_id')->toArray();

        // Handle selected widgets (update or create records)
        foreach ($selectedWidgets as $widgetId => $isActive) {
            UserWidget::updateOrCreate(
                ['user_id' => $userId, 'widget_id' => $widgetId],
                ['is_active' => $isActive]
            );
        }

        // Handle unchecked widgets (delete records)
        $selectedWidgetIds = array_keys($selectedWidgets);
        $uncheckedWidgetIds = array_diff($currentWidgetIds, $selectedWidgetIds);

        UserWidget::where('user_id', $userId)
            ->whereIn('widget_id', $uncheckedWidgetIds)
            ->delete();

        return redirect()->back()->with('success', 'Widgets updated successfully.');
    }
}