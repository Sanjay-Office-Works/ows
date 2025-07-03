<?php

namespace App\Events;

use App\Models\UploadedFile;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UploadedFileUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public UploadedFile $uploadedFile)
    {
        $this->uploadedFile->refresh();
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('product-excel-file-imported'),
        ];
    }

	public function broadcastWith(): array
    {
        return [
            'id' => $this->uploadedFile->id,
            'status' => $this->uploadedFile->status,
            'statistics' => $this->uploadedFile->statistics,
            'error_log' => $this->uploadedFile->error_log,
        ];
    }

	public function broadcastAs(): string
    {
        return 'uploaded-file.updated';
    }
}
