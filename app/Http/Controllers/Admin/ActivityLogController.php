<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    /**
     * Tampilkan daftar activity log dengan filter & search.
     */
    public function index(Request $request): View
    {
        $query = ActivityLog::with('user')->latest();

        // Filter: level
        if ($level = $request->input('level')) {
            $query->where('level', $level);
        }

        // Filter: event group
        if ($event = $request->input('event')) {
            $query->where('event', 'like', $event . '%');
        }

        // Filter: user
        if ($userId = $request->input('user_id')) {
            $query->where('user_id', $userId);
        }

        // Filter: tanggal
        if ($date = $request->input('date')) {
            $query->whereDate('created_at', $date);
        }

        // Filter: hanya error
        if ($request->boolean('errors_only')) {
            $query->whereIn('level', ['error', 'critical']);
        }

        // Search deskripsi
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('description', 'ilike', "%{$search}%")
                  ->orWhere('exception_message', 'ilike', "%{$search}%")
                  ->orWhere('event', 'ilike', "%{$search}%");
            });
        }

        $logs = $query->paginate(50)->withQueryString();

        // Stats untuk summary bar
        $today          = now()->toDateString();
        $todayTotal     = ActivityLog::whereDate('created_at', $today)->count();
        $todayErrors    = ActivityLog::whereDate('created_at', $today)->whereIn('level', ['error', 'critical'])->count();
        $todayWarnings  = ActivityLog::whereDate('created_at', $today)->where('level', 'warning')->count();

        // Event groups untuk dropdown filter
        $eventGroups = ActivityLog::selectRaw("split_part(event, '.', 1) as event_group")
            ->distinct()
            ->orderBy('event_group')
            ->pluck('event_group');

        // Users untuk dropdown filter
        $users = \App\Models\User::orderBy('name')->get(['id', 'name', 'role']);

        return view('admin.activity-logs.index', compact(
            'logs',
            'todayTotal',
            'todayErrors',
            'todayWarnings',
            'eventGroups',
            'users',
        ));
    }

    /**
     * Tampilkan detail satu log entry.
     */
    public function show(ActivityLog $activityLog): View
    {
        return view('admin.activity-logs.show', compact('activityLog'));
    }

    /**
     * Hapus log lama (lebih dari N hari).
     */
    public function purge(Request $request)
    {
        $days    = max(1, (int) $request->input('days', 30));
        $deleted = ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

        return back()->with('success', "{$deleted} log yang lebih dari {$days} hari berhasil dihapus.");
    }
}
