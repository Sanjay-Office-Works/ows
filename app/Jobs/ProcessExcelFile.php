<?php

namespace App\Jobs;

use App\Imports\ProductImport;
use App\Models\UploadedFile;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Maatwebsite\Excel\Facades\Excel;

class ProcessExcelFile implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public UploadedFile $uploadedFile)
    { }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->uploadedFile->update(['status' => 'in_progress']);

		$filePath = $this->uploadedFile->file_path;
		$fullPath = 'app/public/'.$filePath;
		$path = storage_path($fullPath);

        try {
			Excel::queueImport(new ProductImport($this->uploadedFile), $path)->allOnQueue('imports');

            $this->uploadedFile->update(['status' => 'complete']);
        } catch (\Exception $e) {
            $this->uploadedFile->update([
                'status' => 'failed',
                'error_log' => $e->getMessage(),
            ]);
        }
    }
}
