<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\usesUuid;

class Message extends Model
{
    use UsesUuid;

    protected $fillable = [
        'subject', 'creator_sub', 'creator_type', 'creator_type_id', 'recipient_sub', 'message', 'reminders', 'parent_message_id', 'expiry_date', 'read'
    ];

    protected $hidden = [
        'reminders', 'parent_message_id', 'expiry_date' 
    ];

    protected $table = 'message';

    public function creator()
    {
        return $this->belongsTo('App\User' ,'creator_sub','sub');
    }

    public function recipient()
    {
        return $this->belongsTo('App\User' ,'receipient_sub','sub');
    }

    public static function getMessagesForChat($creator, $recipient){
        $created =  Message::where('creator_sub', '=', $creator)
            ->where('recipient_sub', '=', $recipient);
        return Message::where('creator_sub', '=', $recipient)
            ->where('recipient_sub', '=', $creator)
            ->union($created)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public static function markMessagesRead($recipient, $creator){
        Message::where('recipient_sub', '=', $recipient)
            ->where('creator_sub', '=',  $creator)
            ->where('read', '=' , 0)
            ->update(['read' => 1]);
    }

    public static function getUnreadMessages($userSub){
        $messages = Message::where('recipient_sub', '=', $userSub)
            ->where('read', '=', 0)
            ->join('users', 'creator_sub', '=', 'users.sub')
            ->select('creator_type', 'creator_type_id', 'firstName', 'lastName', 'userType', 'message')
            ->distinct()
            ->get();
        return $messages;
    }

    public static function getUsersWithUnreadMessages(){
        return Message::where('read', '=', '0')
            ->select('recipient_sub')
            ->distinct()
            ->get();
    }
}

