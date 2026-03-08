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
            $disk = config('filesystems.default', 's3');
            $this->createDirectoryForId($id, $disk);
            $document = $request->file('document');
            $path = Storage::disk($disk)->put('docs/'.$id, $document, 'private');
            return $path; 
        }
        return 'error';
    }

    protected function createDirectoryForId($id, $disk = null){
        $disk = $disk ?? config('filesystems.default', 's3');
        $dirExists = Storage::disk($disk)->exists('docs/'. $id);
        if (!$dirExists) {
            Storage::disk($disk)->makeDirectory('docs/'.$id);
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
