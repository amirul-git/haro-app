<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = ['lecturer_id', 'user_id', 'day_id', 'time', 'duration', 'link'];

    protected function time(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::now()->setTimeFromTimeString($value)->format('H:i')
        );
    }

    protected function endTime(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $startTime = $attributes['time'];
                $durationMinutes = $attributes['duration'];

                $endTime = Carbon::now()->setTimeFromTimeString($startTime)->addMinutes($durationMinutes)->format('H:i');
                return $endTime;
            }
        );
    }

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
