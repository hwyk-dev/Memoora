<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'  => User::count(),
            'users_today'  => User::whereDate('created_at', today())->count(),
            'total_notes'  => Note::count(),
            'notes_today'  => Note::whereDate('created_at', today())->count(),
        ];

        $recentUsers = User::withCount('notes')->latest()->take(6)->get();
        $recentNotes = Note::with('user')->latest()->take(6)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentNotes'));
    }
}
