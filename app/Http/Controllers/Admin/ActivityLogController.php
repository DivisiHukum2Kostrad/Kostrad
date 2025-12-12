<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user', 'loggable']);

        // Filter by log type
        if ($request->filled('log_type')) {
            $query->where('log_type', $request->log_type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $logs = $query->latest()->paginate(20)->withQueryString();

        $users = \App\Models\User::select('id', 'name')->get();

        return view('admin.activity-logs.index', compact('logs', 'users'));
    }

    public function show($id)
    {
        $log = ActivityLog::with(['user', 'loggable'])->findOrFail($id);
        return view('admin.activity-logs.show', compact('log'));
    }
}
