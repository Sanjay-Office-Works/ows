<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUploadedFileRequest;
use App\Services\UploadedFileService;

class UploadedFileController extends Controller
{

    public function upload(StoreUploadedFileRequest $request)
    {
        (new UploadedFileService())->upload($request);

        return redirect()->back()->with('message', 'File uploaded and queued for processing.');
    }
}
