<?php

namespace App\Http\Controllers\Properties;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;
use Auth;
use Mail;
use App\Mail\StreamUpdateEmail;
use Spatie\Activitylog\Models\Activity;
use App\Properties;
use App\Documents;
use App\Notifications\NotifyTenantOfStreamUpdate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class DocumentController extends BaseDocumentController
{
    public function index($propertyId){
        $user = Auth::user();
        $property = $this->getProperty($propertyId);
        $documents = $this->getDocuments($propertyId, 'property');
        return view('properties.documents', compact('user', 'documents', 'property'));
    }

    private function getProperty($propertyId){
        return Properties::find($propertyId);
    }

    public function uploadFile(Request $request, $id){
        $path = $this->storeFileInS3($request, $id);
        if ($path == "error"){
            return redirect()->back()->with('message', 'No file uploaded');
        }
        $document = $this->storePathInDatabase($id, $path, 'property');
        $this->addMessageToStream($id, $document);
        return ['message' =>'Uploaded'];
    }

    public function downloadFile($propertyId, $fileId){
        $document = Documents::find($fileId);
        if ($document) {
            if ($document->type == 'property' && $document->linked_to == $propertyId) {
                return Storage::disk('s3')->download($document->path);
            }
        }
        return redirect()->back()->with('message', 'Unable to download file');
    }

    private function addMessageToStream($propertyId, $document){
        $user = Auth::user();
        $property = $this->getProperty($propertyId);
        $message = "A new document has been stored against the property. ";
        $message = $message . " You can view them by clicking on documents in left menu or on the property menu above."; 
        activity($property->stream_id)
            ->causedBy($user->sub)
            ->withProperties(['propertyId' => $propertyId])
            ->log($message);
        $this->sendTenantUpdateEmail($property, $user);
    }
    
    private function sendTenantUpdateEmail($property, $user){
        if ($property) {
            foreach ($property->tenants as $tenant) {
                if ($tenant->sub != $user->sub) {
                    if ($tenant->user){
                        $mailData = array(
                            'subject' => "Property stream updated",
                            'link' => url('stream/' . $property->stream_id),
                            'name' => $tenant->name
                        );    
                        $tenant->user->notify(New NotifyTenantOfStreamUpdate($mailData));
                    }
                }
            }
        }
    }
  
    public function deleteFile($propertyId, $fileId){
        $user = Auth::user();
        $property = $this->getProperty($propertyId);
        $document = Documents::find($fileId);
        if ($user->agent) {
            if ($user->agent->agency_id == $property->agent_id) {
                if ($document) { 
                    Storage::disk('s3')->delete($document->path);
                    $document->delete();
                    return response()->json(['message' => 'File deleted!']);
                }
            }
        }
        return response()->json(['message' => 'Unable to delete file!']);
    }

}
