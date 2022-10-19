<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Documents;
use Illuminate\Support\Facades\Storage;


class BaseDocumentController extends Controller
{
    public function getDocumentsByAjax($type, $id)
    {
        return $this->getDocuments($id, $type)->toJson();
    }

    protected function getDocuments($id, $type)
    {
        return Documents::where('linked_to', '=', $id)
           ->where('type', '=', $type)
           ->orderBy('created_at', 'desc')
           ->get();
    }

    protected function storeFileInS3($request, $id){
        if ($request->has('document')) {
            $this->createDirectoryForId($id);
            $document = $request->file('document');
            $path = Storage::disk('s3')->put('docs/'.$id, $document, 'protected');
            return $path; 
        }
        return 'error';
    }

    protected function createDirectoryForId($id){
        $dirExists = Storage::disk('s3')->exists('docs/'. $id);
        if (!$dirExists) {
            Storage::disk('s3')->makeDirectory( 'docs/'.$id);
        }
    }

     protected function storePathInDatabase($id,$path, $type){
        $document = new Documents([
            'type' => $type,
            'linked_to' => $id,
            'path' => $path,
            'file_type' => request('fileType')
        ]);
        $document->save();
        return $document;
    }
   
}
