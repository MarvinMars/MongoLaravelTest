<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Spot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use DateTime;

class CarController extends Controller
{
	public $markers = [];

	public $distance = 0;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cars = Car::all();

        if( $cars ){
	        return response()->json([ 'cars'=>$cars ]);
        }

	    return response()->json([ 'message' => 'Cars not found'],404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
		//TODO: create CRUD logic
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    //TODO: create CRUD logic
    }

	/**
	 * @param Car $car
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
    public function show(Car $car, Request $request)
    {
	    if ( Cache::has( $request->fullUrl() ) ) {
		    return response()->json( Cache::get( $request->fullUrl() ) );
	    }

	    if( $request->has('start') || $request->has('end') ) {

	    	$spots = DB::collection('spot')->where( 'imei', $car['tracker']['imei'] );

	    	if( $request->has('start') ) {
	    		$start = $this->convertDate( $request->get('start') );
			    $spots = $spots->where('time', '>', $start);
		    }

		    if( $request->has('end') ) {
			    $end = $this->convertDate( $request->get('end') );
			    $spots = $spots->where('time', '<', $end );
		    }

		    $spots->orderBy('time')->chunk(100, function ($spots) use ($car) {
			    foreach ($spots as $key => $spot) {

			    	if( $key > 0 ) {
					    $spot1 = $spots[$key - 1];
					    $spot2 = $spot;

					    if( Cache::has( 'distance_'.$spot1['_id'].'_'.$spot2['_id'] ) ){
						    $distance = Cache::get('distance_'.$spot1['_id'].'_'.$spot2['_id']);
						    $this->distance += $distance;
					    }else{
						    $spot1 = Spot::find($spot1['_id']);
						    $spot2 = Spot::find($spot2['_id']);
						    $distance = $this->getDistance($spot1,$spot2);
						    Cache::forever( 'distance_'.$spot1['_id'].'_'.$spot2['_id'], $distance);
					    }

					    $this->distance += $distance;
			    	}

			    	if( Cache::has( 'marker_'.$spot['_id']) ){
					    $value = Cache::get( 'marker_'.$spot['_id']);
					    $this->markers[] = $value;
				    }else{
					    $spot = Spot::find($spot['_id']);
					    $value = $this->convertSpot($spot);

					    if( $value ) {
						    Cache::forever( 'marker_'.$spot['_id'], $value );
						    $this->markers[] = $value;
					    }
				    }
			    }
		    });

	    }else{
	    	$spot = Spot::find( $car['tracker']['last_spot_id'] );

	    	if( $spot ) {
			    $this->markers[] = $this->convertSpot($spot);
		    } else {
			    return response()->json([ 'message' => 'Base spot not found'],404);
		    }

	    }

	    if( $car && !empty( $this->markers ) ) {
		    Cache::forever( $request->fullUrl(), [ 'car' => $car, 'markers' => $this->markers ]);
	    }

	    $car['distance'] = $this->distance ;

        return response()->json([ 'car' => $car, 'markers' => $this->markers ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function edit(Car $car)
    {
	    //TODO: create CRUD logic
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Car $car)
    {
	    //TODO: create CRUD logic
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Car  $car
     * @return \Illuminate\Http\Response
     */
    public function destroy( Car $car )
    {
	    //TODO: create CRUD logic
    }

    public function convertDate($date){
	    return new DateTime($date);
    }

	public function convertSpot( $spot ){
		if( isset( $spot->latitude ) && isset( $spot->longitude ) ){
			$value = [
				'position'=>[
					'lat' => $spot->latitude ?? '',
					'lng' => $spot->longitude ?? '',
				]
			];
			return $value;
		}else{
			return false ;
		}

	}

	public function getDistance( $spot1, $spot2 ){
		$earthRadius = 6371000;

		// convert from degrees to radians
		$latFrom = deg2rad($spot1->latitude);
		$lonFrom = deg2rad( $spot1->longitude);
		$latTo = deg2rad($spot2->latitude);
		$lonTo = deg2rad( $spot2->longitude);

		$latDelta = $latTo - $latFrom;
		$lonDelta = $lonTo - $lonFrom;

		$angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
		                       cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
		return $angle * $earthRadius;
	}
}
