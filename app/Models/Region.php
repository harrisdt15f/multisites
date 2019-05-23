<?php

namespace App\Models;

class Region extends BaseModel {
	protected $table = 'region';

	protected $fillable = [
		'region_id', 'region_parent_id', 'region_name', 'region_level',
	];
}
