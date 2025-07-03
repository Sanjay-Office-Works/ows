<?php

namespace App\Services;

use App\Actions\StoreUploadedFileAction;
use App\Http\Requests\StoreUploadedFileRequest;
use App\Jobs\ProcessExcelFile;

class UploadedFileService
{
	/**
	 * Handle the file upload.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return void
	 */
	public function upload(StoreUploadedFileRequest $request)
	{
		// Store the uploaded file in the 'product_excel_files' directory
		$path = $request->file('excel_file')->store('product_excel_files', 'public');

		// Store file into storage and create an UploadedFile record
		$uploadedFile = (new StoreUploadedFileAction($request))->execute($path);

		// Dispatch the job to process the uploaded file
        ProcessExcelFile::dispatch($uploadedFile)->onQueue('imports');
	}
}
