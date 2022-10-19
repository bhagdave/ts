<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\CsvData;
use App\Properties;
use Auth;
use App\Agency;
use App\Tenant;
use App\Agent;
use App\Stream;
use App\User;
use App\Traits\inviteUser;

class UploadController extends Controller
{

    use inviteUser;

    private $propertyFields = [
        'IGNORE',
        'propertyName',
        'propertyType',
        'inputAddress',
        'inputAddress2',
        'inputCity',
        'inputRegion',
        'inputPostcode'
    ];

    private $agencyId;
    private $agency;
    private $user = null;

    public function index(){
        $user = Auth::user();
        if ($user->userType != 'Admin'){
            return redirect()->back()->with('message', 'Authorised users only!');
        }
        $agents = Agency::orderBy('company_name')->get();
        return view('upload', compact('agents'));
    }

    public function parseUpload(Request $request){
        $path = $request->file('csv_file')->getRealPath();
        $agency = Request('agent');
        $data = array_map('str_getcsv', file($path));
        if (count($data) > 0){
            $csvDataFile = $this->createCsvDataRecord($request, $data);
            $csvData = array_slice($data, 0, 2);
            $fields = $this->propertyFields;
            return view('upload_fields', compact('csvData', 'fields', 'csvDataFile', 'agency'));
       } 
       return redirect()->back()->with('message', 'Nothing to import');
    }

    private function createCsvDataRecord(Request $request, $data){
        $csvDataFile = CsvData::create([
            'csv_filename' => $request->file('csv_file')->getClientOriginalName(),
            'csv_header' => $request->has('header'),
            'csv_data' => json_encode($data)
        ]);   
        return $csvDataFile;     
    }

    public function processUpload(Request $request){
        $data = CsvData::find($request->csv_data_file_id);
        $csvData = json_decode($data->csv_data, true);
        $lines = 0;
        // loop through each row of the CSV data
        foreach ($csvData as $row) {
            // ignore the first line with the header
            if ($lines != 0){
                if (count($row) > 1 ){ // Make sure we have some data - Empty lines in CSV
                    $property = new Properties();
                    $property->agent_id = Request('agency');
                    foreach ($row as $index => $data) { // Loop through the row of data
                        if ($request->fields[$index] != 0){ // If they have selected ignore then ignore this
                            if (isset($data)){
                                $fieldIndex = $request->fields[$index];
                                $field = $this->propertyFields[$fieldIndex];
                                $property->$field = $data;
                            }
                        }
                    }
                    $property->save();
                    $newStream = $this->createStreamForProperty($property->id, false);
                    $privateStream = $this->createStreamForProperty($property->id, true);
                    $this->addMessageToStream($newStream->id, $property->id);
                    $this->updatePropertyWithStreams($property, $newStream->id, $privateStream->id);
                    $this->attachAgentsToStream(request('agency'), $newStream->id);
                    $this->attachAgentsToStream(request('agency'), $privateStream->id);
                }
            }
            $lines = $lines + 1;
        }

        return redirect('/admin')->with('message', 'Properties uploaded successfully!');        
    }

    private function createStreamForProperty($propertyId, $private){
        $newStream = new Stream();
        $newStream->extra_attributes->broadcastOnly = $private;
        $newStream->extra_attributes->property_id = $propertyId;
        $newStream->private = $private;
        $newStream->save(); 
        return $newStream;
    }

    private function addMessageToStream($streamId, $propertyId){
        $message = "Welcome to your new property stream.  This is a place for you to communicate with everyone connected to your property.";
        activity($streamId)
            ->causedBy(User::current()->sub)
            ->withProperties(['propertyId' => $propertyId, 'messageType' => 'Event' ])
            ->log($message);
    }

    private function updatePropertyWithStreams($property, $streamId, $privateStreamId){
        $property->stream_id = $streamId;
        $property->private_stream_id = $privateStreamId;
        $property->save();
    }

    private function attachAgentsToStream($agencyId, $streamId){
        $agents = Agent::where('agency_id', '=', $agencyId)->get();
        foreach($agents as $agent){
            \DB::table('stream_user')->insert(
                [
                    'user_id' => $agent->user->id,
                    'stream_id' => $streamId
                ]
            );
        }
    }

    public function mixed(){
        $user = Auth::user();
        if ($user->userType != 'Admin'){
            return redirect()->back()->with('message', 'Authorised users only!');
        }
        $agents = Agency::orderBy('company_name')->get();
        return view('uploadmixed', compact('agents'));
    }

    public function mixedUpload(Request $request){
        $path = $request->file('csv_file')->getRealPath();
        $this->agencyId = Request('agent');
        $this->getUserForUpload();
        $data = array_map('str_getcsv', file($path));
        if (count($data) > 0){
            $lastProperty = null;
            foreach($data as $record){
                // first field is the type
                switch($record[0]){
                    case 'property' : 
                        $lastProperty = $this->processProperty($record);
                        break;
                    case 'tenant' : 
                        $this->processTenant($record, $lastProperty);
                        break;
                    default:
                        break;
                }
            }
            return redirect('/admin')->with('message', 'Properties uploaded successfully!');        
        }
        return redirect('/admin')->with('message', 'NO Properties uploaded ');        
    }

    private function getUserForUpload(){
        $this->agency = Agency::find($this->agencyId);
        $agent = $this->agency->agents()->where('main', 1)->first();
        if (isset($agent)){
            if (isset($agent->user)){
                $this->user = $agent->user;
            }
        }

    }

    private function processProperty($data){
        $property = new Properties();
        $property->agent_id = $this->agencyId;
        $property->propertyName = $data[1];
        $property->propertyType = $data[2];
        $property->inputAddress = $data[3];
        $property->inputAddress2 = $data[4];
        $property->inputCity = $data[5];
        $property->inputPostcode = $data[6];
        $property->save();
        $propertyStream = $this->createStreamForProperty($property->id, false);
        $prvtPropertyStream = $this->createStreamForProperty($property->id, true);
        $this->updatePropertyWithStreams($property, $propertyStream->id, $prvtPropertyStream->id);
        $this->attachAgentsToStream($this->agencyId, $propertyStream->id);
        $this->attachAgentsToStream($this->agencyId, $prvtPropertyStream->id);
        return $property;
    }

    private function processTenant($data, $property){
        if (isset($data[3])){ // check for email
            $tenant = new Tenant();
            $tenant->name = $data[1] . ' ' . $data[2];
            $tenant->phone = $data[4];
            $tenant->email = trim($data[3]);
            $tenant->property_id = $property->id;
            $tenant->save();
            $emailData = $this->buildDataForInviteEmail($tenant, $property);
            $this->inviteNewTenant($emailData, $this->user);
        }
    }

    private function buildDataForInviteEmail($tenant, $property){
        $userName = $this->user->firstName ?: $this->user->name;
        $subject = $userName . " from " . $this->agency->company_name . " has invited you to join them in TenancyStream.com";
        $agencyName = $this->agency->company_name;
        $emailData = array(
            'name' => $tenant->name,
            'email' => $tenant->email,
            'property_address' => $property->propertyName,
            'agencyName' => $agencyName,
            'agentName' => $userName,
            'subject' => $subject,
        );
        return $emailData;
   }
}
