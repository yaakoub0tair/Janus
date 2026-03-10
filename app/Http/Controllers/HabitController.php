<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// use ill
class HabitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        // $habitss = Habit::where('user_id' == Auth::id());
        // $habitss->isActive;
        $habits = Auth::user()->habits->where('is_active', true);
        return response()->json($habits);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validate = $request->validate([
            'title' => 'required',
            'description' => 'string',
            'frequency' => 'in:daily,weekly,monthly',
            'target_days' => 'min:1|max:7',
            // 'color_code' => 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'
            'color_code' => 'string'
        ]);
        $user = $request->user();
        $habit = $user->habits()->create($validate);
        return response()->json($habit);
    }

    /**
     * Display the specified resource.
     */
    public function show(habit $habit)
    {
        if ($habit->user->id != Auth::id()) {
            return response()->json([
                "message" => "non authoriser (not owner)"
            ], 403);
        }
        return response()->json($habit);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(habit $habit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, habit $habit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(habit $habit)
    {
        //
    }
}
