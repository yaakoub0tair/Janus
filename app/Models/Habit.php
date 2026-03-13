<?php

namespace App\Models;

use App\Models\User;

use Illuminate\Database\Eloquent\Model;

class Habit extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'frequency',
        'target_days',
        'color_code',
        'is_active',
        'user_id'
    ];
    protected function casts(): array
    {
        return [
            'is_active'=>'boolean'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(HabitLog::class);
    }
}
