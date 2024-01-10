<?php

namespace App\Console\Commands;

use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Twilio\Rest\Client;

class SendSchedules extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'haro:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send whatsapp schedule to today student and lecturer';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // find schedule base on today id
        $todayID = $this->todayName(Carbon::now());
        $todaySchedules = Schedule::where('day_id', $todayID)->get();

        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $twilio = new Client($sid, $token);

        $todaySchedules->each(function ($schedule) use ($twilio) {
            $bodyForStudent = "Haloo {$schedule->user->name},\nKamu hari ini ada jadwal belajar dengan kak {$schedule->lecturer->name} di jam {$schedule->time}-{$schedule->end_time}, berikut link zoom meetingnya yaa:\n\n{$schedule->link->link}\n\nSemangat belajarnya dek 💪😊";

            // student
            $message = $twilio->messages
                ->create(
                    "whatsapp:+62" . $schedule->user->phone,
                    // to
                    array(
                        "from" => "whatsapp:+14155238886",
                        "body" => $bodyForStudent
                    )
                );

            $bodyForLecturer = "Selamat pagi kak {$schedule->lecturer->name}\n\nKakak ada jadwal mengajar siswa {$schedule->user->name} hari ini di jam {$schedule->time}-{$schedule->end_time} nih, jangan lupa disiapkan materi dan pembelajarannya yaa kak. semangat mengajar hari ini 💪😊\n\nberikut link zoom meetingnya:\n{$schedule->link->link}\n\nTerima kasih 💪😊";

            // lecturer
            $message = $twilio->messages
                ->create(
                    "whatsapp:+62" . $schedule->lecturer->phone,
                    // to
                    array(
                        "from" => "whatsapp:+14155238886",
                        "body" => $bodyForLecturer
                    )
                );
        });
    }

    private function todayName(Carbon $date): int
    {
        $dayName = $date->format('D');
        $dayID = 0;
        switch ($dayName) {
            case 'Sun':
                $dayID = 1;
                break;
            case 'Mon':
                $dayID = 2;
                break;
            case 'Tue':
                $dayID = 3;
                break;
            case 'Wed':
                $dayID = 4;
                break;
            case 'Thu':
                $dayID = 5;
                break;
            case 'Fri':
                $dayID = 6;
                break;
            case 'Sat':
                $dayID = 7;
                break;
            default:
                $dayID = 0;
                break;
        }
        return $dayID;
    }
}
