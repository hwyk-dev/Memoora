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
            ->when($request->status, function ($q, $status) {
                match ($status) {
                    'banned'    => $q->whereNotNull('banned_at'),
                    'suspended' => $q->whereNull('banned_at')->whereNotNull('suspended_until')->where('suspended_until', '>', now()),
                    'active'    => $q->whereNull('banned_at')->where(fn ($q) => $q->whereNull('suspended_until')->orWhere('suspended_until', '<=', now())),
                    default     => null,
                };
            })
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

    public function ban(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'You cannot ban your own account.');
        }

        $request->validate([
            'reason' => ['nullable', 'string', 'max:500'],
        ]);

        $user->update([
            'banned_at'       => now(),
            'suspended_until' => null,
            'ban_reason'      => $request->reason,
        ]);

        return back()->with('success', "{$user->name} has been banned.");
    }

    public function unban(User $user): RedirectResponse
    {
        $user->update(['banned_at' => null, 'ban_reason' => null]);

        return back()->with('success', "{$user->name} has been unbanned.");
    }

    public function suspend(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'You cannot suspend your own account.');
        }

        $request->validate([
            'days' => ['required', 'integer', 'min:1', 'max:365'],
        ]);

        $user->update([
            'suspended_until' => now()->addDays($request->days),
            'banned_at'       => null,
        ]);

        return back()->with('success', "{$user->name} has been suspended for {$request->days} day(s).");
    }

    public function unsuspend(User $user): RedirectResponse
    {
        $user->update(['suspended_until' => null]);

        return back()->with('success', "{$user->name}'s suspension has been lifted.");
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
