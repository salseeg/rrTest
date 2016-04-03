<?php
/**
 * Created by PhpStorm.
 * User: salseeg
 * Date: 19.03.16
 * Time: 19:35
 */

namespace App\Http\Controllers;


use App\Domain\IcaoCollection;
use App\Domain\IcaoNotamCollection;
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
        $notamArray = [];
        $codes = new IcaoCollection();
        try {
            $codes->addStrings(explode("\n", trim($request->input('codes'))));
        }catch (\UnexpectedValueException $e){
            $error = $e->getMessage();
        }
        if (!$error){
            if (! $codes->isEmpty()){
                $api = new RocketRouteApi();
                try {
                    $notamArray = $api->getNotam($codes)->asArray();
                }catch (RocketRouteException $e){
                    $error = $e->getMessage();
                }
            }else{
                $error = 'No valid ICAO codes specified';
            }
            
        }

        return response()->json([
            'error' => $error,
            'notams' => $notamArray,
        ]);
    }

}