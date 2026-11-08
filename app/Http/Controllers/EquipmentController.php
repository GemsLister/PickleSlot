<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipmentController extends Controller
{
    /**
     * Display a listing of available rental equipment.
     */
    public function index(Request $request): View
    {
        $category = $request->query('category');

        $query = Equipment::query()->active();

        if ($category && in_array($category, ['paddle', 'balls'], true)) {
            $query->where('category', $category);
        }

        $paddles = Equipment::active()->paddles()->get();
        $balls = Equipment::active()->balls()->get();

        return view('equipment.index', compact('paddles', 'balls', 'category'));
    }
}
