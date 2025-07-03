<?php

namespace App\Actions;

use App\DTO\UploadedFileDTO;
use App\Http\Requests\StoreUploadedFileRequest;
use App\Models\UploadedFile;

class StoreUploadedFileAction
{
	public function __construct(
		protected StoreUploadedFileRequest $request
	) {}

	public function execute(String $path): UploadedFile
	{
		// Create a new UploadedFile record in the database
		$dto = new UploadedFileDTO(
			user_id: $this->request->user()->id,
			file_path: $path,
		);

		return UploadedFile::create($dto->toArray());
	}
}
