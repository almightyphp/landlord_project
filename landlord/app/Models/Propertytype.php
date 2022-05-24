<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Propertytype extends Model
{
    use HasFactory;
    protected $table = 'property_type';
    protected $primarykey = 'id';

    public static function getTypeData()
    {
        $users = DB::table('property_type')
            ->where('df', '=', '')                        
            ->get();

            return $users;
    }
}
