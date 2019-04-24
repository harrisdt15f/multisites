<?php

namespace App\models;

class ActivityType extends BaseModel {
	protected $table = 'partner_activity_lists';

	protected $fillable = [
		'name', 'type', 'status', 'created_at', 'updated_at',
	];
}
