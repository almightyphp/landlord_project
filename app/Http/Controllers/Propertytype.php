<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Propertytype as Type;
use Illuminate\Support\Facades\DB;


class Propertytype extends Controller
{
    //
    public function index()
    {
        $list =  Type::getTypeData();  
        $data['list'] =  Type::getTypeData();
        $data['_page'] = "Property Type";
        $data['_title'] = "Manage Property Type";        

        return view('propertytype/propertytype', ['data'=>$data]);
    }

    public function managePropertyType(Request $request)
    {
        $id = $request->id;
        $type = $request->roleName;
        if(($id != "") && $type){
            $update = DB::table('property_type')
              ->where('id', $id)
              ->update(['type' => "$type"]);
            if($update){
                echo "update";
            }

        }else{
            $id = DB::table('property_type')->insert(
                ['type' => "$type", 'df' => "",'createdate' => getCurrentDateTime(), 'modifieddate' => getCurrentDateTime()]
            );

            if($id){
                echo "add";
            }
        }
        
    }

    public function deleteType(Request $request)
    {
        $id = $request->id;
        $update = DB::table('property_type')
              ->where('id', $id)
              ->update(['df' => "delete"]);
              if($update){
                  echo 1;
              }
    }

    public function deleteMultiple(Request $request)
    {
        foreach($request->id as $updateid){
            $data = [
                'df' => 'delete',
                'modifieddate' => getCurrentDateTime()
            ];
            DB::table('property_type')
            ->where('id', $updateid)
            ->update(['df' => "delete"]);           

        }
        echo "1";
    }
}
