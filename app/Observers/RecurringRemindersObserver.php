<?php

namespace App\Observers;

use App\Reminder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;


class RecurringRemindersObserver
{
    public static function created(Reminder $reminder)
    {
        Log::info("Creating reminder - Observer firing");
        if(!$reminder->reminder()->exists())
        {
            $recurrences = [
                'weekly'    => [
                    'times'     => 52,
                    'function'  => 'addWeek'
                ],
                'monthly'    => [
                    'times'     => 24,
                    'function'  => 'addMonth'
                ],
                'annually'     => [
                    'times'     => 5,
                    'function'  => 'addYear'
                ],
            ];
            $startDate = Carbon::parse($reminder->start_date);
            $endDate = Carbon::parse($reminder->end_date);
            $recurrence = $recurrences[$reminder->recurrence] ?? null;

            if($recurrence)
                for($i = 0; $i < $recurrence['times']; $i++)
                {
                    $startDate->{$recurrence['function']}();
                    $endDate->{$recurrence['function']}();
                    $reminder->reminders()->create([
                        'name' => $reminder->name,
                        'type' => $reminder->type,
                        'type_id' => $reminder->type_id,
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                        'recurrence' => $reminder->recurrence,
                    ]);
                }
        }
    }    

    public function updated(Reminder $reminder)
    {
        if ($reminder->reminders()->exists() || $reminder->reminder)
        {
            $startDate = Carbon::parse($reminder->getOriginal('start_date'))->diffInDays($reminder->start_date, false);
            $endDate = Carbon::parse($reminder->getOriginal('end_date'))->diffInDays($reminder->end_date, false);
            if($reminder->reminder){
                $childReminders = $reminder->reminder->reminders()->whereDate('start_date', '>', $reminder->getOriginal('start_date'))->get();
            } else {
                $childReminders = $reminder->reminders;
            }

            foreach($childReminders as $childReminder)
            {
                if($startDate)
                    $childReminder->start_date = Carbon::parse($childReminder->start_date)->addDays($startDate);
                if($endDate)
                    $childReminder->end_date = Carbon::parse($childReminder->end_date)->addDays($endDate);
                if($reminder->isDirty('name') && $childReminder->name == $reminder->getOriginal('name'))
                    $childReminder->name = $reminder->name;
                $childReminder->saveQuietly();
            }
        }

        if($reminder->isDirty('recurrence')){
            // remove existing reminders
            if($reminder->reminders()->exists()){
                $reminders = $reminder->reminders()->pluck('id');
                Reminder::whereIn('id', $reminders)->delete();
            }
            // recreate if reecurrence is not none
            if ($reminder->recurrence != 'none'){
                self::created($reminder);
            }
        }
    }

    public function deleting(Reminder $reminder)
    {
        if($reminder->reminders()->exists()){
            $reminders = $reminder->reminders()->pluck('id');
        } else if($reminder->reminder){
            $reminders = $reminder->reminder->reminders()->pluck('id');
            $reminders[] = $reminder->reminder->id;
        } else {
            $reminders = [];
        }
        Reminder::whereIn('id', $reminders)->delete();
    }

}
