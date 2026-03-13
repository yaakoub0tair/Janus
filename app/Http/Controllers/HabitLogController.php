<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use App\Models\HabitLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use App\Models\User;

class HabitLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        

        $user = Auth::user();
        $logs = $user->load("habits.logs");
        // $logs = User::where('id',Auth::id())->with('habits.logs')->get();
        return response()->json($logs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        //
        $request->validate([
            'habit_id' => "required|exists:habits,id",
            "completed_at" => "date",
            "note" => "string"
        ]);

        $habit = Habit::find($request->input('habit_id'));
        if ($habit->user_id != Auth::id()) {
            return response()->json([
                "success" => false,
                "message" => "Habit not owned by logged user"
            ], 403);
        }
        if (!$habit->is_active) {
            return response()->json([
                "success" => false,
                "message" => "Habit to log should be active"
            ], 403);
        }
        if ($habit->logs()->whereDate('completed_at', now()->format('Y-m-d'))->exists() && !$request->has('completed_at')) {
            return response()->json([
                "success" => false,
                "message" => "Habit already logged today"
            ], 403);
        }
        $log = $habit->logs()->create([
            "completed_at" => $request->input("completed_at",  now())
        ]);

        return response()->json(["success" => true, "data" => $log], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show( $habitid)
    {   
        //
        // return "test";
        $habit=Habit::find($habitid);
        if ($habit->user_id != Auth::id()) {
            return response()->json([
                "success" => false,
                "message" => "Habit not owned by logged user"
            ], 403);
        }

        return response()->json(["success" => true, "data" =>$habit->logs], status: 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, HabitLog $habitLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HabitLog $habitLog)
    {
        //
    }
}
