<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['lecturer_id', 'user_id', 'day_id', 'time', 'duration', 'link'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(User::class);
    }

    public function day()
    {
        return $this->belongsTo(Day::class);
    }
}
