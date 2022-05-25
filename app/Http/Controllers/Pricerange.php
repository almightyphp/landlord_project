<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pricerange_model as Model;

use Illuminate\Support\Facades\DB;

class Pricerange extends Controller
{
    public function index()
    {
       
        $data['list'] =  Model::getPriceRangeList();
        $data['_page'] = "Pricerange";
        $data['_title'] = "Manage Pricerange";        

        return view('pricerange/pricerange', ['data'=>$data]);
    }
}
