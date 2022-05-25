<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class Pricerange_model extends Model
{
    use HasFactory;

    public static function getPriceRangeList()
    {
        $users = DB::table('price_range')
        ->where('df', '=', '')                        
        ->get();

        return $users;
    }
}
