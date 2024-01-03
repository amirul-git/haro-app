<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use App\Models\Schedule;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $lecturerID = $data['lecturer_id'];
        $dayID = $data['day_id'];

        $schedules = Schedule::where(['lecturer_id' => $lecturerID, 'day_id' => $dayID])->get();

        $schedules->each(function ($schedule) use ($data) {
            // check if a new schedules starttime and endtime in between of existing schedule

            // if yes give a alert and halt process
            // if not continue

            $time = $data['time'];
            $durationMinutes = $data['duration'];

            $newScheduleStartTime = Carbon::now()->setTimeFromTimeString($time);
            $newScheduleEndTime = Carbon::now()->setTimeFromTimeString($time)->addMinutes($durationMinutes);

            $existingScheduleStartTime = Carbon::now()->setTimeFromTimeString($schedule->time);
            $existingScheduleEndTime = Carbon::now()->setTimeFromTimeString($schedule->time)->addMinutes($schedule->duration);

            $startTimeCollide = $newScheduleStartTime->between($existingScheduleStartTime, $existingScheduleEndTime);
            $endTimeCollide = $newScheduleEndTime->between($existingScheduleStartTime, $existingScheduleEndTime);

            if ($startTimeCollide || $endTimeCollide) {
                Notification::make()
                    ->title('Schedule collision')
                    ->warning()
                    ->send();
                $this->halt();
            }
        });

        return $data;
    }
}
