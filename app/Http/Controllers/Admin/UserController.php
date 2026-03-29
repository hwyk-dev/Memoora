<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::withCount('notes')
            ->when($request->search, fn ($q, $search) =>
                $q->where(fn ($q) =>
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $stats = [
            'total'    => $user->notes()->count(),
            'pinned'   => $user->notes()->pinned()->count(),
            'archived' => $user->notes()->archived()->count(),
        ];

        $recentNotes = $user->notes()->latest()->take(10)->get();

        return view('admin.users.show', compact('user', 'stats', 'recentNotes'));
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Account \"{$name}\" has been deleted.");
    }
}
