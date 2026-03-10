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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function isActive() {
        return $this->is_active;
    }
}
