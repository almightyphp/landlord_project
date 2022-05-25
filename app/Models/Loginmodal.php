<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Loginmodal extends Model
{
    use HasFactory;

    protected $table = 'admin';
    protected $primarykey = 'id';

    public static function getUserData($id)
    {
        $users = DB::table('admin')
            ->where('df', '=', "")  
            ->where('id', '=', $id)          
            ->get();

            return $users;
    }

}

