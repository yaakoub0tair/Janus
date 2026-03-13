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
    public function index(Request $request)
    {
        //
        // $habitss = Habit::where('user_id' == Auth::id());
        // $habitss->isActive;
        // $is_active=$request->query('is_active');
    
        $habits = Habit::where('user_id', Auth::id())
            ->when($request->has('is_active'), function ($query) use ($request) {
                return $query->where('is_active', $request->query('is_active') == "true" ? 1 : 0);
            })
            // ->where('is_active', true)
            ->get();
        return response()->json($habits);
    }

    /**
     * Show the form for creating a new resouoLLLLLLFGGTrce.
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
            'target_days' => 'required|integer|min:1',
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
        if ($habit->user_id !== Auth::id()) {
            return response()->json([
                "success" => false,
                "message" => "Unauthorized"
            ], 403);
        }

        return response()->json([
            "success" => true,
            "data" => $habit,
            "message" => "Habit details"
        ]);
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
        if ($habit->user_id !== Auth::id()) {
            return response()->json([
                "success" => false,
                "message" => "Unauthorized"
            ], 403);
        }
        $validate = $request->validate([
            'title' => 'string',
            'description' => 'string',
            'frequency' => 'in:daily,weekly,monthly',
            'target_days' => 'integer|min:1',
            // 'color_code' => 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'
            'color_code' => 'string',
            'is_active' => 'boolean'
        ]);
        $habit->update($validate);
        return response()->json($habit);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(habit $habit)
    {
        //
        if ($habit->user_id !== Auth::id()) {
            return response()->json([
                "success" => false,
                "message" => "Unauthorized"
            ], 403);
        }

        $habit->delete();

        return response()->json([
            "success" => true,
            "message" => "Habit deleted"
        ]);
    }
}
