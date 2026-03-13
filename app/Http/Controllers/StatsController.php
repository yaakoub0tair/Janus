<?php

namespace App\Http\Controllers;

use App\Models\Habit;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class StatsController extends Controller
{
    //
    public function show($id)
    {
        // current_streak : nombre de jours consécutifs en cours.
        // longest_streak : meilleure série jamais réalisée.
        // total_completions : nombre total de complétions.
        // completion_rate : pourcentage de complétion sur les 30 derniers jours.
        $habit = Habit::find($id);
        if ($habit->user_id != Auth::id()) {
            return response()->json([
                "success" => false,
                "message" => "Habit not owned by logged user"
            ], 403);
        }

        $data = [


            "current_streak" => $this->getCurrentStreak($habit),
            "longest_streak" => $this->getLongestStreak($habit->logs()->orderBy("completed_at", "desc")->get()->pluck("completed_at")),
            "total_completions" => $habit->logs()->count(),
            "completion_rate" => $this->getCompletionRate($habit),

        ];

        return response()->json($data);
    }
    private function getCurrentStreak(Habit $habit)
    {
        $logs = $habit->logs()->orderBy('completed_at', 'desc')->pluck('completed_at')->toArray();
        $current_streak = 0;
        $today = now()->toDateString();

        foreach ($logs as $date) {
            if ($current_streak == 0 && $date != $today) {
                $yesterday = now()->subDay()->toDateString();
                if ($date == $yesterday) {
                    $current_streak++;
                    $today = $date;
                } else {
                    break;
                }
            } elseif ($current_streak > 0) {
                $prevDay = date('Y-m-d', strtotime($today . ' -1 day'));
                if ($date == $prevDay) {
                    $current_streak++;
                    $today = $date;
                } else {
                    break;
                }
            } else {
                $current_streak = 1;
                $today = $date;
            }
        }
        return $current_streak;
    }
    private function getLongestStreak($logs)
    {
        $longest_streak = 0;
        $streak = 1;
        // return $logs;

        for ($i = 1; $i < count($logs); $i++) {
            $prev = date('Y-m-d', strtotime($logs[$i - 1]));
            $current = date('Y-m-d', strtotime($logs[$i]));

            if (strtotime($prev . ' -1 day') == strtotime($current)) {
                $streak++;
            } else {
                $streak = 1;
            }

            if ($streak > $longest_streak) {
                $longest_streak = $streak;
            }
        }
        return $longest_streak;
    }

    public function getCompletionRate($habit)
    {
        $start = now()->subDays(30)->toDateString();
        $logs_last30 = $habit->logs()->where('completed_at', '>=', $start)->count();
        $completion_rate = ($logs_last30 / 30) * 100;

        return $completion_rate;
    }
}
