<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Contracts\JWTSubject;


class Apimodel extends Model implements JWTSubject
{
    use HasFactory, Notifiable;

    protected $table = 'admin';
    protected $primarykey = 'id';

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public static function checkUserExist($email)
    {
        $users = DB::table('user')        
        ->where('email', '=', $email)          
        ->get();

        return $users;
    }

    public static function getPropertyType()
    {
        $type = DB::table('property_type')->select('type','id')
        ->where('df', '=', "")
        ->get()->toArray();

        return $type;
    }

    public static function getPropertyList($id){
        $type = DB::table('property')->select('id','property_name','property_address','property_type_id','area','rooms','property_length','property_breathe','facing_diraction','price','latitude','longitude')
        ->where('user_id', '=', $id)
        ->get()->toArray();

        return $type;
    }
}

