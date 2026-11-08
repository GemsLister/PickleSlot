<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EquipmentRequest;
use App\Models\Equipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AdminEquipmentController extends Controller
{
    /**
     * Display a listing of equipment for admin inventory.
     */
    public function index(): View
    {
        $equipment = Equipment::orderBy('category')->orderBy('name')->paginate(15);

        return view('admin.equipment.index', compact('equipment'));
    }

    /**
     * Show the form for creating new equipment.
     */
    public function create(): View
    {
        return view('admin.equipment.create');
    }

    /**
     * Store new equipment in storage.
     */
    public function store(EquipmentRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('equipment', 'public');
        }

        Equipment::create($validated);

        return redirect()->route('admin.equipment.index')
            ->with('status', 'Equipment item created successfully.');
    }

    /**
     * Show the form for editing equipment.
     */
    public function edit(Equipment $equipment): View
    {
        return view('admin.equipment.edit', compact('equipment'));
    }

    /**
     * Update equipment in storage.
     */
    public function update(EquipmentRequest $request, Equipment $equipment): RedirectResponse
    {
        $validated = $request->validated();
        $validated['is_active'] = $request->boolean('is_active');

        if ($request->boolean('remove_image')) {
            $validated['image_path'] = null;
        }

        if ($request->hasFile('image')) {
            $validated['image_path'] = $request->file('image')->store('equipment', 'public');
        }

        $equipment->update($validated);

        return redirect()->route('admin.equipment.index')
            ->with('status', 'Equipment item updated successfully.');
    }

    /**
     * Remove equipment from storage.
     */
    public function destroy(Equipment $equipment): RedirectResponse
    {
        if ($equipment->reservationEquipments()->exists()) {
            return back()->with('error', 'Cannot delete equipment that has existing reservation records. Deactivate it instead.');
        }

        $equipment->delete();

        return redirect()->route('admin.equipment.index')
            ->with('status', 'Equipment deleted successfully.');
    }
}
