<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Properties;
use App\Tenant;

class Reminder extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'start_date', 'end_date', 'type', 'type_id', 'recurrence'];
    /**
     * Used to fill in selection box in forms and also in the list of reminders
     */ 
    const RECURRENCE_SELECT = [
        'none'    => 'None',
        'weekly'  => 'Weekly',
        'monthly' => 'Monthly',
        'annually' => 'Annually',
    ];


    public function reminders()
    {
        return $this->hasMany(Reminder::class, 'reminder_id', 'id');
    }

    public function reminder()
    {
        return $this->belongsTo(Reminder::class, 'reminder_id');
    }

    public function property(){
        return $this->hasOne(Properties::class, 'id', 'type_id');
    }
    /**
     * This is used within the observer to make sure 
     * we do not fir off more observers 
     */
    public function saveQuietly()
    {
        return static::withoutEvents(function () {
            return $this->save();
        });
    }

}
