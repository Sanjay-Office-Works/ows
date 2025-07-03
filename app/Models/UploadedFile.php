<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UploadedFile extends Model
{
	use SoftDeletes;

    protected $fillable = ['user_id', 'file_path', 'status', 'statistics', 'error_log'];

    protected $casts = [
        'statistics' => 'array',
    ];
}
