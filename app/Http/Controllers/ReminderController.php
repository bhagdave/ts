<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reminder;
use App\Properties;
use App\Tenant;
use Auth;

/**
 * Class to deal with all events relating to reminders
 * Reminders should be generic and able to be created against any other model
 */
class ReminderController extends Controller
{
    /**
     * Calendar view of all reminders for a given user
     */
    public function index(){
        $user = Auth::user();
        if ($user->userType == 'Agent'){
            $reminders = $this->getAllRemindersForAgency();
            return view('reminders.index', compact('reminders'));
        }
        return redirect()->back()->with('message', 'Only agents can check reminders');
    } 

    /**
     * Get all of the reminders for an agency in a format ready for vue calendar
     */
    private function getAllRemindersForAgency(){
        $user = Auth::user();
        if ($user->userType == 'Agent'){
            if (isset($user->agent->agency)){
                $reminders = $user->agent->agency->reminders;
                $controller = $this;
                return $reminders->map(function  ($row) use($controller){
                    $model = $controller->getTypeData($row->type, $row->type_id);
                    if ($row->type === 'property'){
                        $tip = $model->propertyName;
                    }
                    if ($row->type === 'tenant'){
                        $tip = $model->name;
                    }
                    return [
                        'title' => $tip,
                        'date' => $row->start_date,
                        'recurrence' => $row->recurrence,
                        'id' => $row->id,
                        'tip' => $tip,
                        'main' => $row->reminders()->exists()
                    ];
                });
            }
        }
        return [];
    }

    /**
     * Lists all reminders for a particular model record
     * Needs the the $type of the record and its $typeId
     * The view used gives the user the ability to add/delete/edit reminders
     */
    public function manage($type, $typeId){
        $model = $this->getTypeData($type, $typeId);
        $reminders = $this->getReminders($type, $typeId);
        return view('reminders.manage', compact('type', 'typeId', 'model', 'reminders'));
    }
    /*
     * Manage reminders for all types against all records
     */
    public function manageAll(){
        $reminders = $this->getAllRemindersForAgency()->paginate(20);
        return view('reminders.manage-all', compact('reminders'));
        return "Hello";
    }

    /**
     * Get the model linked to the particular reminder
     * Uses the $type and $typeid
     */
    private function getTypeData($type, $typeId){
        $model = null;
        if ($type == 'property'){
            $model = Properties::find($typeId);
        }
        if ($type == 'tenant'){
            $model = Tenant::find($typeId);
        }
        return $model;
    }

    /**
     * View all reminders for a particular model in a calendar
     * The calendar is a vue component
     */
    public function view($type, $typeId){
        $model = $this->getTypeData($type, $typeId);
        $reminders = $this->getReminders($type, $typeId);
        return view('reminders.view', compact('type', 'typeId', 'model', 'reminders'));
    }

    /**
     * displays a form to allow users to create a new reminder
     * Uses the type and typeid to attach the new reminder to a model
     */
    public function create($type, $typeId){
        $model = $this->getTypeData($type, $typeId);
        return view('reminders.edit', compact('type', 'typeId', 'model'));
    }

    /**
     * Get a collection of reminders from the Db
     * uses the type and typeid to find them
     * The field names are aliased for ease of use within vue components
     */
    private function getReminders($type, $typeId){
        return Reminder::where('type', $type)
            ->where('type_id', $typeId)
            ->select('name as title','start_date as date', 'id', 'recurrence')
            ->get(); 
    }

    /**
     * This is used via an ajax call in a vue comonent when the
     * user clicks on a date in the calendar
     * NB - These reminders have NO recurrences
     */
    public function addReminder(Request $request){
        Reminder::create($request->input('reminder'));
        return 'ok';
    }

    /**
     * deletes a reminder from the event on the manage reminders page
     */
    public function deleteReminder(Request $request, $id){
        Reminder::destroy($id);
        return redirect()->back()->with('message', "Reminder deleted");
    }

    /**
     * Displays a form to allow the user to amend an exiting reinder
     */
    public function editReminder($reminderId){
        $reminder = Reminder::find($reminderId);
        if (isset($reminder)){
            $model = $this->getTypeData($reminder->type, $reminder->type_id);
            return view('reminders.edit', compact('reminder', 'model'));
        }
        return redirect()->back()->with('message',  'Reminder not found.');
    }

    /**
     * handle the posting back of data from the edit form
     * NB - The recurrences of the event are created in the observer app/Observers/RecurringRemindersObserver
     */
    public function updateReminder(Request $request){
        $request->validate([
            'name' => 'required|max:125',
            'start_date' => 'required'
        ]);
        Reminder::find($request->input('id'))->update($request->all());
        return redirect('/reminders/manage/'. $request->input('type') . '/' . $request->input('type_id'))->with('message', 'Reminder Updated');
    }

    /**
     * handle the posting back of data from the add reminder
     * We do not offer the end date as an option on the form 
     * so we make it the same as the start date
     * NB - The recurrences of the event are created in the observer app/Observers/RecurringRemindersObserver
     */
    public function createReminder(Request $request){
        $request->validate([
            'name' => 'required|max:125',
            'start_date' => 'required'
        ]);
        Reminder::create([
            'type' => $request->input('type'),
            'type_id' => $request->input('type_id'),
            'name' => $request->input('name'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('start_date'),
            'recurrence' => $request->input('recurrence')

        ]);
        return redirect('/reminders/manage/'. $request->input('type') . '/' . $request->input('type_id'))->with('message', 'Reminder Created');
    }
}
