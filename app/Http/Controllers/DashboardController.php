<?php

namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $totalNotes    = $user->notes()->active()->count();
        $pinnedCount   = $user->notes()->active()->pinned()->count();
        $archivedCount = $user->notes()->archived()->count();
        $recentNotes   = $user->notes()->active()->latest()->take(6)->get();

        return view('dashboard', compact('totalNotes', 'pinnedCount', 'archivedCount', 'recentNotes'));
    }
}
