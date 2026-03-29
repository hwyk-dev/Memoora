<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Models\Note;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = auth()->user()->notes()->active()->orderByDesc('is_pinned')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        $notes = $query->paginate(12)->withQueryString();

        return view('notes.index', compact('notes', 'search'));
    }

    public function create()
    {
        return view('notes.create');
    }

    public function store(StoreNoteRequest $request)
    {
        auth()->user()->notes()->create($request->validated());

        return redirect()->route('notes.index')->with('success', 'Note created successfully.');
    }

    public function edit(Note $note)
    {
        $this->authorize('update', $note);

        return view('notes.edit', compact('note'));
    }

    public function update(UpdateNoteRequest $request, Note $note)
    {
        $this->authorize('update', $note);

        $note->update($request->validated());

        return redirect()->route('notes.index')->with('success', 'Note updated successfully.');
    }

    public function destroy(Note $note)
    {
        $this->authorize('delete', $note);

        $note->delete();

        return back()->with('success', 'Note deleted.');
    }

    public function pin(Note $note)
    {
        $this->authorize('update', $note);

        $newValue = ! $note->is_pinned;
        $note->update(['is_pinned' => $newValue]);

        return back()->with('success', $newValue ? 'Note pinned.' : 'Note unpinned.');
    }

    public function archive(Note $note)
    {
        $this->authorize('update', $note);

        $newValue = ! $note->is_archived;
        $note->update([
            'is_archived' => $newValue,
            'is_pinned'   => $newValue ? false : $note->is_pinned,
        ]);

        return back()->with('success', $newValue ? 'Note archived.' : 'Note restored from archive.');
    }

    public function pinned()
    {
        $notes = auth()->user()->notes()->active()->pinned()->latest()->paginate(12);

        return view('notes.pinned', compact('notes'));
    }

    public function archived()
    {
        $notes = auth()->user()->notes()->archived()->latest()->paginate(12);

        return view('notes.archived', compact('notes'));
    }
}
