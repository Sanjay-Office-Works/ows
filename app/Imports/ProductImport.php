<?php

namespace App\Imports;

use App\Events\UploadedFileUpdated;
use App\Models\Product;
use App\Models\UploadedFile;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductImport implements ToCollection, WithHeadingRow, ShouldQueue, WithChunkReading
{
	use Importable;

	protected $stats = ['total' => 0, 'new' => 0, 'failed' => 0];
    protected $failures = [];
	private static int $chunkIndex = 0;

    public function __construct(protected UploadedFile $uploadedFile)
    { }

	public function chunkSize(): int
    {
        return 500; // or 1000, adjust as needed
    }

    public function collection(Collection $rows)
    {
		foreach ($rows as $index => $row) {
            $this->stats['total']++;
            $rowIndex = $index + 2; // +2: first row is header, second is Excel row 2
            $data = $row->toArray();

			$validator = Validator::make($data, [
				'CATEGORY NAME' => 'required|string',
				'CATEGORY DESCRIPTION' => 'required|string',
				'TITLE' => 'required|string|unique:products,title',
				'SKU' => ['required', 'regex:/^[a-zA-Z0-9\-]+$/', 'unique:products,sku'],
				'DESCRIPTION' => 'required|string',
				'PRICE' => 'required|numeric',
				'STOCK' => 'required|numeric',
			]);

			$validator->setAttributeNames([
				'CATEGORY NAME' => 'category name',
				'CATEGORY DESCRIPTION' => 'category description',
				'TITLE' => 'title',
				'SKU' => 'sku',
				'DESCRIPTION' => 'description',
				'PRICE' => 'price',
				'STOCK' => 'stock',
			]);

			if ($validator->fails()) {
				$this->stats['failed']++;
				$this->failures[] = [
					'row' => $rowIndex,
					'errors' => $validator->errors()->all(),
				];
				continue;
			}

			try {
				Product::create([
					'category_name' => $data['CATEGORY NAME'],
					'category_description' => $data['CATEGORY DESCRIPTION'],
					'title' => $data['TITLE'],
					'sku' => $data['SKU'],
					'description' => $data['DESCRIPTION'],
					'price' => $data['PRICE'],
					'stock' => $data['STOCK'],
				]);
				$this->stats['new']++;
			} catch (Exception $e) {
				$this->stats['failed']++;
				$this->failures[] = [
					'row' => $rowIndex,
					'errors' => [$e->getMessage()],
				];
			}
		}

		$this->updateChunkStats();
    }

	protected function updateChunkStats(): void
	{
		$this->uploadedFile->refresh();

		$existingStats = json_decode($this->uploadedFile->statistics ?? '{}', true) ?: ['total' => 0, 'new' => 0, 'failed' => 0];
		$existingErrors = json_decode($this->uploadedFile->error_log ?? '[]', true) ?: [];

		$updatedStats = [
			'total' => $existingStats['total'] + $this->stats['total'],
			'new' => $existingStats['new'] + $this->stats['new'],
			'failed' => $existingStats['failed'] + $this->stats['failed'],
		];

		$updatedErrors = array_merge($existingErrors, $this->failures);

		$this->uploadedFile->update([
			'statistics' => json_encode($updatedStats),
			'error_log' => json_encode($updatedErrors),
		]);

		// Trigger the event to notify about the updated file
		event(new UploadedFileUpdated($this->uploadedFile));
	}
}
