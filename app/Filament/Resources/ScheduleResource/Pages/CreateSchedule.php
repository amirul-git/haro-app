<?php

namespace App\Filament\Resources\ScheduleResource\Pages;

use App\Filament\Resources\ScheduleResource;
use App\Models\Schedule;
use Carbon\Carbon;
use Dflydev\DotAccessData\Data;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Nette\Utils\Arrays;

class CreateSchedule extends CreateRecord
{
    protected static string $resource = ScheduleResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $studentID = $data['user_id'];
        $lecturerID = $data['lecturer_id'];
        $dayID = $data['day_id'];

        $lecturer_schedules = Schedule::where(['lecturer_id' => $lecturerID, 'day_id' => $dayID])->get();
        $student_schedules = Schedule::where(['user_id' => $studentID, 'day_id' => $dayID])->get();
        $schedules = Schedule::where(['day_id' => $dayID])->get();

        // send notification and halt the process if there's a collision on schedule
        // $this->haltOnScheduleCollision($lecturer_schedules, $data, "Lecturer already have a schedule at that time span");
        $this->haltOnScheduleCollision($student_schedules, $data, "Student already have a schedule at that time span");

        // change to secondary link if there's a taken schedule with different lecturer and student
        // $isMainLinkUsed = $this->isMainLinkUsed($schedules, $data);

        // if ($isMainLinkUsed) {
        //     Notification::make()
        //         ->title("Changing to secondary link due to main link is taken")
        //         ->info()
        //         ->send();

        //     $data['link_id'] = 2;
        //     return $data;
        // } else {
        //     return $data;
        // }
        return $data;
    }

    private function haltOnScheduleCollision(Collection $schedules, array $data, string $message)
    {
        $schedules->each(function ($schedule) use ($data, $message) {
            // check if a new schedules starttime and endtime in between of existing schedule

            // if yes give a alert and halt process
            // if not continue

            $time = $data['time'];
            $durationMinutes = $data['duration'];

            $newScheduleStartTime = Carbon::now()->setTimeFromTimeString($time);
            $newScheduleEndTime = Carbon::now()->setTimeFromTimeString($time)->addMinutes($durationMinutes);

            $existingScheduleStartTime = Carbon::now()->setTimeFromTimeString($schedule->time);
            $existingScheduleEndTime = Carbon::now()->setTimeFromTimeString($schedule->time)->addMinutes($schedule->duration)->subSecond(); // we use subSecond, so if schedule ends at 13.30, it will be 13.29.59, so we could easly add a next schedule at 13.30

            $startTimeCollide = $newScheduleStartTime->between($existingScheduleStartTime, $existingScheduleEndTime);
            $endTimeCollide = $newScheduleEndTime->between($existingScheduleStartTime, $existingScheduleEndTime);

            if ($startTimeCollide || $endTimeCollide) {
                Notification::make()
                    ->title($message)
                    ->danger()
                    ->send();
                $this->halt();
            }
        });
    }

    private function isMainLinkUsed(Collection $schedules, $data)
    {
        return $schedules->contains(function ($schedule) use ($data) {

            // check if a new schedules starttime and endtime in between of existing schedule

            // if yes give a alert and halt process
            // if not continue

            $time = $data['time'];
            $durationMinutes = $data['duration'];

            $newScheduleStartTime = Carbon::now()->setTimeFromTimeString($time);
            $newScheduleEndTime = Carbon::now()->setTimeFromTimeString($time)->addMinutes($durationMinutes);

            $existingScheduleStartTime = Carbon::now()->setTimeFromTimeString($schedule->time);
            $existingScheduleEndTime = Carbon::now()->setTimeFromTimeString($schedule->time)->addMinutes($schedule->duration)->subSecond(); // we use subSecond, so if schedule ends at 13.30, it will be 13.29.59, so we could easly add a next schedule at 13.30

            $startTimeCollide = $newScheduleStartTime->between($existingScheduleStartTime, $existingScheduleEndTime);
            $endTimeCollide = $newScheduleEndTime->between($existingScheduleStartTime, $existingScheduleEndTime);

            return $startTimeCollide || $endTimeCollide;
        });
    }
}
