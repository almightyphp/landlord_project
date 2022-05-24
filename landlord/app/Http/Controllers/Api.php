<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
// use App\Apimodal;

use App\Models\Apimodel as Apimodel;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;


class Api extends Controller
{
    //

    public function __construct()
    {
        
        $this->guard = "api"; // add
    }

    public function register(Request $request)
    {
        if((!empty($request->name)) && (!empty($request->email)) && (!empty($request->password)) && (!empty($request->dob)) && (!empty($request->phonenumber)) && (!empty($request->gender)) && (!empty($request->address)) && (!empty($request->city)) && (!empty($request->type)))
        {
            $name = $request->name;
            $email = $request->email;
            $password = md5($request->password);
            $dob = $request->dob;
            $phone = $request->phonenumber;
            $gender = $request->gender;
            $address = $request->address;
            $city = $request->city;
            $type = $request->type;
            $image = $request->image;

            $folderPath = "./assets/uploads/user/";
            $img = str_replace('data:image/png;base64,', '', $image);
			$img = str_replace(' ', '+', $img);
			$data = base64_decode($img);
			$file = microtime(true).'.png';
			file_put_contents($folderPath.$file, $data);


            $usercheck = get_row_by_id($email,'users','email');
            if(empty($usercheck)){
               $id = DB::table('users')->insert(
                    ['email' => "$email", 'name' => "$name", 'password' => "$password", 'phone_num' => "$phone", 'dob' => "$dob", 'gender' => "$gender", 
                    'address' => "$address", 'city' => "$city", 'type' => "$type", 'image' => "$file", 'createdate' => getCurrentDateTime(), 'modifieddate' => getCurrentDateTime()]
                );

                return retJson(['success' => '1', 'message' => 'Successfully Registered']);
            }else{
                return retJson(['success' => '0', 'message' => "User Already Exist"]);
            }

        }else{
           return retJson(['status' => '0', 'message' => "PLEASE ENTER_ALL (name, email, password, dob, phonenumber, gender, address, city, type are required and image is optional)"]);
        }
        
    }

    public function login(Request $request)
    {
        if((!empty($request->email)) && (!empty($request->password)))
        {
            $email = $request->email;
            $password = md5($request->password);
            $usercheck = get_row_by_id($email,'users','email');
            if(!empty($usercheck)){
                if($usercheck->password === $password){
                    $responsedata["user_id"] = $usercheck->id;
                    $responsedata["name"] = $usercheck->name;
                    $responsedata["dob"] = $usercheck->dob;
                    $responsedata["phone_num"] = $usercheck->phone_num;
                    $responsedata["gender"] = $usercheck->gender;
                    $responsedata["address"] = $usercheck->address;
                    $responsedata["city"] = $usercheck->city;
                    $responsedata["type"] = $usercheck->type;
                    $responsedata["image"] = asset("assets/uploads/user/$usercheck->image");


                    return retJson(['success' => '1', 'message' => "Login Success", 'response' => $responsedata]);

                }else{
                    return retJson(['success' => '0', 'message' => "Incorrect Password"]);
                }
            }else{
                return retJson(['success' => '0', 'message' => "Email doesn't exist"]);
            }
        }else{
           return retJson(['status' => '0', 'message' => "email and password are required"]);
        }
    }

    public function test(Request $request)
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    public function respondWithToken($token)
    {
        return response()->json([
            'access_token'=> $token,
            'token_type'=> 'bearer',
            'expires_in' => auth()->factory()->getTTL()*60

        ]);
    }

    public function profile()
    {
        return response()->json(auth()->user());
    }

    public function refresh()
    {
        
        return $this->respondWithToken(auth()->refresh());
    }

    public function property_type()
    {
        $type = Apimodel::getPropertyType();

        $response = array();

        // foreach ($type as $key => $value) {

        //     $response[$key]['id'] = $value['id'];
            
        //     # code...
        // }

        return retJson(['success' => '1', 'message' => "Login Success", 'response' => $type]);

    }

    public function addProperty(Request $request)
    {
        $validator = Validator::make($request->all(),[
            // 'property_name'=> 'required|string|min:2|max:50|unique:property,property_name',
            'property_name'=> 'required|string|min:2|max:50',
            'property_address' => 'required|string|min:10|max:250',
            'area' => 'required|string|min:5|max:250',
            'rooms' => 'required|numeric',
            'property_length' => 'required|string|min:1|max:250',
            'property_breathe' => 'required',
            'property_type' => 'required',
            'facing_diraction' => 'required',
            'price' => 'required|numeric',
            'latitude' => 'required',
            'longitude' => 'required',
            'user_id' => 'required'
        ]);

        if($validator->fails())
        {
            return retJson(['status' => '0', 'message' => "All Fields Are Required"]);
        }

        $property_name = $request->property_name;
        $property_address = $request->property_address;
        $area = $request->area;
        $rooms = $request->rooms;
        $property_length = $request->property_length;
        $property_breathe = $request->property_breathe;
        $property_type = $request->property_type;
        $facing_diraction = $request->facing_diraction;
        $price = $request->price;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $user_id = $request->user_id;

        $property_data = get_row_by_id($property_name,'property','property_name');
        if(empty($property_data)){
            $id = DB::table('property')->insert(
                ['property_name' => "$property_name",'property_type_id' => "$property_type", 'property_address' => "$property_address", 'area' => "$area", 'rooms' => "$rooms", 'property_length' => "$property_length", 'property_breathe' => "$property_breathe", 
                'user_id' => "$user_id", 'facing_diraction' => "$facing_diraction", 'price' => "$price", 'latitude' => "$latitude", 'longitude' => "$longitude", 'createdate' => getCurrentDateTime(), 'modifieddate' => getCurrentDateTime()]
            );

            return retJson(['success' => '1', 'message' => "Property Added"]);

        }else{
            return retJson(['success' => '0', 'message' => "Property Name Already Exist"]);
        }  

        return retJson(['status' => '1', 'message' => "Success"]);
    }

    public function propertyList()
    {
        if(!empty(response()->json(auth()))){

        
        $data =  response()->json(auth()->user()->id);
        if(!empty(is_object($data))){

            $property_list = Apimodel::getPropertyList($data->original);
            $list = array();
            foreach ($property_list as $key => $value) {               
               $list[$key]['property_id'] = $value->id;
               $list[$key]['property_name'] = $value->property_name;
               $list[$key]['address'] = $value->property_address;
               $list[$key]['rooms'] = $value->rooms;
               $list[$key]['area'] = $value->area;
               $list[$key]['property_length'] = $value->property_length;
               $list[$key]['property_length'] = $value->property_length;

            }
            

            return retJson(['status' => '0', 'message' => "Something Went Wrong", 'response' => $list]);


        }else{
            return retJson(['status' => '0', 'message' => "Something Went Wrong"]);
        }
    }
        
    }

    
    
}

