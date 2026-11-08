<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CourtRequest;
use App\Models\Court;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminCourtController extends Controller
{
    /**
     * Display a listing of all courts for admin management.
     */
    public function index(): View
    {
        $courts = Court::withCount('reservations')
            ->orderBy('id')
            ->paginate(15);

        return view('admin.courts.index', compact('courts'));
    }

    /**
     * Show the form for creating a new court.
     */
    public function create(): View
    {
        return view('admin.courts.create');
    }

    /**
     * Store a newly created court in storage.
     */
    public function store(CourtRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('courts', 'public');
        }

        Court::create($validated);

        return redirect()->route('admin.courts.index')
            ->with('status', 'Court created successfully.');
    }

    /**
     * Show the form for editing the specified court.
     */
    public function edit(Court $court): View
    {
        return view('admin.courts.edit', compact('court'));
    }

    /**
     * Update the specified court in storage.
     */
    public function update(CourtRequest $request, Court $court): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->boolean('remove_image')) {
            $validated['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('courts', 'public');
        }

        $court->update($validated);

        return redirect()->route('admin.courts.index')
            ->with('status', 'Court updated successfully.');
    }

    /**
     * Toggle court active status.
     */
    public function toggleStatus(Court $court): RedirectResponse
    {
        $court->update(['is_active' => ! $court->is_active]);

        $status = $court->is_active ? 'activated' : 'deactivated';

        return back()->with('status', "Court {$court->name} has been {$status}.");
    }

    /**
     * Remove the specified court from storage.
     */
    public function destroy(Court $court): RedirectResponse
    {
        if ($court->reservations()->activeStatus()->exists()) {
            return back()->with('error', 'Cannot delete court with active or upcoming reservations.');
        }

        $court->delete();

        return redirect()->route('admin.courts.index')
            ->with('status', 'Court deleted successfully.');
    }
}
