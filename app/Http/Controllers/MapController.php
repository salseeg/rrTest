<?php
/**
 * Created by PhpStorm.
 * User: salseeg
 * Date: 19.03.16
 * Time: 19:35
 */

namespace App\Http\Controllers;


class MapController extends Controller
{
    
    function index(){
        return view('map');
    }
    
    function getNotams(){
        return response()->json(['i\'m' => 'here']);
    }

}