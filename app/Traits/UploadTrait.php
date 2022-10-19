<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait UploadTrait
{
    public function uploadOne(UploadedFile $uploadedFile, $filename)
    {
        $name = !is_null($filename) ? $filename : str_random(25);

        //$file = $uploadedFile->storeAs($folder, $name.'.'.$uploadedFile->getClientOriginalExtension(), $disk);

		$storagePath = "https://tenancystream.s3-eu-west-1.amazonaws.com/".Storage::disk('s3')->put("uploads", $uploadedFile, 'public');

        return $storagePath;

    }
}