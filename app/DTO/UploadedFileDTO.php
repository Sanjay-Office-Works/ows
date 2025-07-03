<?php

namespace App\DTO;

class UploadedFileDTO
{
    public function __construct(
		public int $user_id,
		public string $file_path,
		public string $status = 'init',
		public ?string $statistics = null,
		public ?string $error_log = null
    ) {}

	public function toArray(): array
	{
		return [
			'user_id' => $this->user_id,
			'file_path' => $this->file_path,
			'status' => $this->status,
			'statistics' => $this->statistics,
			'error_log' => $this->error_log,
		];
	}
}
