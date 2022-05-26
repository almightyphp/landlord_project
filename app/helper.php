<?php


use Illuminate\Support\Facades\DB;


function retJson($array){
    return response($array)->header('Content-Type', 'application/json');
    // echo json_encode($array);
}

function get_row_by_id($id, $tblname, $colname){
    
   return DB::table($tblname)->where($colname,$id)->get()->first();
}

function getCurrentDateTime() {
    $mytime = Carbon\Carbon::now();
    return date('Y-m-d H:i:s');
 
 }

//  function getCurrentDate($format="Y-m-d") {
//      date_default_timezone_set('Asia/Kolkata');

//      return date($format);
//  }


?>
