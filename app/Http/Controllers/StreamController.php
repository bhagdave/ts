<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stream;
use App\Properties;
use App\User;
use Auth;

class StreamController extends Controller
{
    public function fetchMessages(Request $request, $id)
    {
        $activities = \Spatie\Activitylog\Models\Activity::where('log_name', $id)
            ->orderBy('created_at', 'asc')
            ->get();
        return response()->json($activities);
    }

    public function sendMessage(Request $request, $id)
    {
        $user = User::current();
        $messageData = $request->input('message');
        $message = is_array($messageData) ? ($messageData['message'] ?? '') : $messageData;

        activity($id)
            ->causedBy($user)
            ->withProperties(['messageType' => 'Message'])
            ->log($message);

        return response()->json(['status' => 'ok']);
    }

    public function sendMessageToAll(Request $request)
    {
        $user = User::current();
        $message = $request->input('message');
        return response()->json(['status' => 'ok']);
    }

    public function sendMessageToProperty(Request $request)
    {
        $user = User::current();
        $message = $request->input('message');
        return response()->json(['status' => 'ok']);
    }

    public function show(Request $request, $uuid)
    {
        $stream = Stream::find($uuid);
        if (!$stream) {
            abort(404);
        }
        $user = User::current();
        $property = Properties::withoutGlobalScopes()
            ->where('stream_id', $uuid)
            ->orWhere('private_stream_id', $uuid)
            ->first();

        return view('stream.show', compact('stream', 'user', 'property'));
    }

    public function getLatestVisit(Request $request, $uuid)
    {
        return response()->json(['visited' => now()]);
    }

    public function getUsersWhoHaveReadMessage(Request $request, $uuid, $id)
    {
        return response()->json([]);
    }

    public function post(Request $request, $uuid)
    {
        return $this->sendMessage($request, $uuid);
    }

    public function uploadMediaFile(Request $request, $uuid)
    {
        return response()->json(['status' => 'ok']);
    }

    public function indexStreams(Request $request)
    {
        $user = User::current();
        return response()->json([]);
    }
}
