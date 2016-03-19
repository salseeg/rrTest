<?php
/**
 * Created by PhpStorm.
 * User: salseeg
 * Date: 19.03.16
 * Time: 19:35
 */

namespace App\Http\Controllers;


use App\Domain\Notam;
use App\Domain\RocketRouteApi;
use App\Domain\RocketRouteException;
use Illuminate\Http\Request;

class MapController extends Controller
{
    
    function index(){
        return view('map');
    }
    
    function getNotams(Request $request){
        $error = '';
        $notams = [];
        $codes = trim($request->input('codes'));
        $codes = explode("\n", $codes);
        $codes = array_map('trim', $codes);
        if (! empty($codes)){
            $api = new RocketRouteApi();
            try {
                /** @var Notam[][] $notams */
                $notams = $api->getNotam($codes);
                foreach ($notams  as  $icao => & $list){
                    foreach ($list as & $notam){
                        $notam = [
                            'id' => $notam->id,
                            'geoSpot' => $notam->getGeoSpot(),
                            'message' => $notam->getMessage(),
                        ];
                    }
                }
            }catch (RocketRouteException $e){
                $error = $e->getMessage();
            }
        }

        return response()->json([
            'error' => $error,
            'notams' => $notams,
        ]);
    }

}