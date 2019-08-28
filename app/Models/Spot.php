<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model as Model;
use App\Models\Car;

class Spot extends Model
{
	protected $collection = 'spot';
//	protected $dateFormat = 'Y-m-d H:i:s';
	protected $fillable = [];

	protected $dates = [ 'time'];
	/**
	 * Get the user's full name.
	 *
	 * @return string
	 */
	public function getLatitudeAttribute()
	{
		if(!isset($this->lat)){
			if(isset($this->loc[0])){
				return $this->loc[0];
			}
		}else{
			return $this->lat;
		}
	}

	/**
	 * Get the user's full name.
	 *
	 * @return string
	 */
	public function getLongitudeAttribute()
	{
		if(!isset($this->lng)){
			if(isset($this->loc[1])){
				return $this->loc[1];
			}
		}else{
			return $this->lng;
		}
	}

	/**
	 * Get the user that owns the phone.
	 */
	public function car()
	{
		return $this->belongsTo(Car::class, 'imei', 'tracker.imei');
	}
}
