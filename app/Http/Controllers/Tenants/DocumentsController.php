<?php

namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Controller;
use App\Http\Controllers\BaseDocumentController;
use Illuminate\Http\Request;
use App\Tenant;
use App\Documents;
use Auth;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentsController extends BaseDocumentController
{

    public function index($tenantId){
        $user = Auth::user();
        $tenant = $this->getTenant($tenantId);
        $documents = $this->getDocuments($tenantId, 'tenant');
        return view('tenants.documents', compact('tenant', 'user', 'documents'));
    }

    private function getTenant($tenantId){
        return Tenant::find($tenantId);
    }

    public function uploadFile(Request $request, $id){
        $path = $this->storeFileInS3($request, $id);
        if ($path == 'error') {
            return redirect()->back()->with('message', 'No file uploaded!');
        }
        $this->storePathInDatabase($id, $path, 'tenant');
        return ['message' =>'Uploaded'];
    }

    public function downloadFile($tenantId, $fileId){
        $user = Auth::user();
        $document = Documents::find($fileId);
        if ($this->canUserDownloadFile($document, $user, $tenantId)) {
            return Storage::disk('s3')->download($document->path);
        }
        return redirect()->back()->with('message', 'Unable to download file');
    }

    private function canUserDownloadFile($document, $user, $tenantId){
        if ($document) {
            if ($user->tenant) {
                if ($document->type == 'tenant' && $document->linked_to == $user->tenant->id) {
                    return true;
                }
                if ($user->tenant->property_id) {
                    if ($document->type == 'property' && $document->linked_to == $user->tenant->property_id) {
                        return true;
                    }
                }
            }
            if ($user->agent) {
                $tenant = $this->getTenant($tenantId);
                if ($tenant->property) {
                    if ($tenant->property->agent_id == $user->agent->agency_id) {
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function getDocumentsForTenant(){
        $user = Auth::user();
        if ($user->userType == 'Tenant') {
            $tenantDocuments = Documents::where('linked_to', '=', $user->tenant->id)
                ->where('type', '=', 'tenant')
                ->select('id', 'file_type');
            if ($user->tenant->property_id) {
                $propertyDocuments = Documents::where('linked_to', "=", $user->tenant->property_id)
                    ->where('type', '=', 'property')
                    ->select('id', 'file_type');
                $tenantDocuments->union($propertyDocuments);
            }
            return $tenantDocuments->get();
        }
        return "You do not have permission to access this!";
    }

    public function ownDocuments(){
        $documents = $this->getDocumentsForTenant();
        return view('tenants.own-documents', compact('documents'));
    }

    public function deleteFile($tenantId, $fileId){
        $user = Auth::user();
        $document = Documents::find($fileId);
        if ($this->canUserDownloadFile($document, $user, $tenantId)) {
            Storage::disk('s3')->delete($document->path);
            $document->delete();
            return response()->json(['message' => 'File deleted!']);
        }
        return response()->json(['message' => 'Unable to delete file!']);
    }

}
