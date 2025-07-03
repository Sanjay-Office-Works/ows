<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use SoftDeletes;

    protected $fillable = [
		"category_name",
		"category_description",
		"title",
		"sku",
		"description",
		"price",
		"stock"
	];
}
