<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use App\Models\Spot;

class Car extends Eloquent
{
	protected $collection = 'car';

	protected $fillable = [];

	/**
	 * Get the phone record associated with the user.
	 */
	public function spots()
	{
		return $this->hasMany(Spot::class,'imei','tracker.imei');
	}
}
