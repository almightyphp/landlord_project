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
            $password = Hash::make($request->password);
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
        // if((!empty($request->email)) && (!empty($request->password)))
        // {
        //     $email = $request->email;
        //     $password = md5($request->password);
        //     $usercheck = get_row_by_id($email,'users','email');
        //     if(!empty($usercheck)){
        //         if($usercheck->password === $password){
        //             $responsedata["user_id"] = $usercheck->id;
        //             $responsedata["name"] = $usercheck->name;
        //             $responsedata["dob"] = $usercheck->dob;
        //             $responsedata["phone_num"] = $usercheck->phone_num;
        //             $responsedata["gender"] = $usercheck->gender;
        //             $responsedata["address"] = $usercheck->address;
        //             $responsedata["city"] = $usercheck->city;
        //             $responsedata["type"] = $usercheck->type;
        //             $responsedata["image"] = asset("assets/uploads/user/$usercheck->image");


        //             return retJson(['success' => '1', 'message' => "Login Success", 'response' => $responsedata]);

        //         }else{
        //             return retJson(['success' => '0', 'message' => "Incorrect Password"]);
        //         }
        //     }else{
        //         return retJson(['success' => '0', 'message' => "Email doesn't exist"]);
        //     }
        // }else{
        //    return retJson(['status' => '0', 'message' => "email and password are required"]);
        // }

        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
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
        if(auth()->check() == 1){
            $user_data =  response()->json(auth()->user());

            $data = json_decode($user_data->content(), true);

            $response = array();

            $response['name'] = $data['name'];
            $response['email'] = $data['email'];
            $response['dob'] = $data['dob'];
            $response['phone_number'] = $data['phone_num'];
            $response['gender'] = $data['gender'];
            $response['address'] = $data['address'];
            $response['city'] = $data['city'];
            $response['type'] = $data['type'];
            $response['image'] = asset("assets/uploads/user/".$data['image']);


            return retJson(['success' => '1', 'message' => "Profile Details", 'response' => $response]);
        }else{
            return retJson(['status' => '0', 'message' => "Token Expired"]);
        }

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
        if(auth()->check() == 1){
            $user_data =  response()->json(auth()->user());
            $data = json_decode($user_data->content(), true);
            $landlord_id = $data['id'];
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
                'image' => 'required'

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
            $image = $request->image;

            $folderPath = "./assets/uploads/property/";
            $img = str_replace('data:image/png;base64,', '', $image);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $file = microtime(true).'.png';
            file_put_contents($folderPath.$file, $data);

            $property_data = get_row_by_id($property_name,'property','property_name');
            if(empty($property_data)){
                $id = DB::table('property')->insert(
                    ['property_name' => "$property_name",'property_type_id' => "$property_type", 'property_address' => "$property_address", 'area' => "$area", 'rooms' => "$rooms", 'property_length' => "$property_length", 'property_breathe' => "$property_breathe", 
                    'landlord_id' => "$landlord_id", 'status' => 'available', 'image' => "$file", 'facing_diraction' => "$facing_diraction", 'price' => "$price", 'latitude' => "$latitude", 'longitude' => "$longitude", 'createdate' => getCurrentDateTime(), 'modifieddate' => getCurrentDateTime()]
                );

                return retJson(['success' => '1', 'message' => "Property Added"]);

            }else{
                return retJson(['success' => '0', 'message' => "Property Name Already Exist"]);
            } 
        
        }else{
            return retJson(['status' => '0', 'message' => "Token Expired"]);

        }

        
    }

    public function propertyList()
    {
        // $result = json_decode(response()->json(auth()->check()));
        if(auth()->check() == 1){

            
            $data =  response()->json(auth()->user()->id);
            if(!empty($data)){

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
                $list[$key]['image'] = asset("assets/uploads/property/$value->image");

                }            

                return retJson(['status' => '1', 'message' => "Property List", 'response' => $list]);

            }else{
                return retJson(['status' => '0', 'message' => "Something Went Wrong"]);
            }
        }else{
            return retJson(['status' => '0', 'message' => "Token Expired"]);
        }
        // return auth()->check();
        
    }

    public function propertyDetails(Request $request)
    {
        $validator = Validator::make($request->all(),[
            // 'property_name'=> 'required|string|min:2|max:50|unique:property,property_name',
            'property_id'=> 'required',
        ]);

        if($validator->fails())
        {
            return retJson(['status' => '0', 'message' => "property_id is Required"]);

        }

        $property_id = $request->property_id;
        $property_data = get_row_by_id($property_id,'property','id');
        if(!empty($property_data)){
        $response =  array();

        $response['property_name'] = $property_data->property_name;
        $response['area'] = $property_data->area;
        $response['address'] = $property_data->property_address;
        $response['rooms'] = $property_data->rooms;
        $response['property_length'] = $property_data->property_length;
        $response['property_breadth'] = $property_data->property_breathe;
        $response['price'] = $property_data->price;
        $response['facing_direction'] = $property_data->facing_diraction;
        $response['latitude'] = $property_data->latitude;
        $response['longitude'] = $property_data->longitude;
        $response['image'] = asset("assets/uploads/property/$property_data->image");

            return retJson(['status' => '1', 'message' => "Property Details", 'response' => $response]);
    
        }else{

            return retJson(['status' => '0', 'message' => "Property Does Not Exist"]);

        }

    }

    public function editProperty(Request $request)
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
            'property_id' => 'required',
            'image' => 'required'            

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
        $property_id = $request->property_id;
        $image = $request->image;

        $folderPath = "./assets/uploads/property/";
        $img = str_replace('data:image/png;base64,', '', $image);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $file = microtime(true).'.png';
        file_put_contents($folderPath.$file, $data);

        // $property_data = get_row_by_id($property_name,'property','property_name');
        $property_data = get_row_by_id($property_id,'property','id');
        if(!empty($property_data)){
        // if(empty($property_data)){
            $id = DB::table('property')->where('id',$property_id)->update(
                ['property_name' => "$property_name",'property_type_id' => "$property_type", 'property_address' => "$property_address", 'area' => "$area", 'rooms' => "$rooms", 'property_length' => "$property_length", 'property_breathe' => "$property_breathe", 
                'image' => "$file",'facing_diraction' => "$facing_diraction", 'price' => "$price", 'latitude' => "$latitude", 'longitude' => "$longitude", 'createdate' => getCurrentDateTime(), 'modifieddate' => getCurrentDateTime()]
            );

            return retJson(['success' => '1', 'message' => "Property Updated"]);

        }else{
            return retJson(['success' => '0', 'message' => "Property Does Not Exist"]);
        }  

        return retJson(['status' => '1', 'message' => "Success"]);
    }


    public function deleteProperty(Request $request)
    {
        $validator = Validator::make($request->all(),[
            // 'property_name'=> 'required|string|min:2|max:50|unique:property,property_name',
            'property_id'=> 'required',
        ]);

        if($validator->fails())
        {
            return retJson(['status' => '0', 'message' => "property_id is Required"]);

        }

        $property_id = $request->property_id;
        $property_data = get_row_by_id($property_id,'property','id');
        if(!empty($property_data)){
        DB::table('property')->where('id', $property_id)->delete();
            return retJson(['status' => '1', 'message' => "Property Deleted Successfully"]);
        }else{
            return retJson(['success' => '0', 'message' => "Property Does Not Exist"]);
        }

    }


    public function updateProfile(Request $request)
    {

        if(auth()->check() == 1){
            $user_data =  response()->json(auth()->user());
            $data = json_decode($user_data->content(), true);
            $user_id = $data['id'];
            $validator = Validator::make($request->all(),[
                // 'property_name'=> 'required|string|min:2|max:50|unique:property,property_name',
                'name'=> 'required|max:250',
                'email' => 'required|max:250',
                'dob' => 'required',
                'phonenumber' => 'required|numeric',
                'gender' => 'required',
                'address' => 'required',
                'property_type' => 'required',
                'city' => 'required',
                'image' => 'required',           
                
            ]);

            if($validator->fails())
            {
                return retJson(['status' => '0', 'message' => "All Fields Are Required"]);
            }

                $name = $request->name; 
                $email = $request->email;            
                $dob = $request->dob;
                $phone = $request->phonenumber;
                $gender = $request->gender;
                $address = $request->address;
                $city = $request->city;
                $image = $request->image;            

                $folderPath = "./assets/uploads/user/";
                $img = str_replace('data:image/png;base64,', '', $image);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $file = microtime(true).'.png';
                file_put_contents($folderPath.$file, $data);

                $user_data = get_row_by_id($user_id,'users','id');
                if(!empty($user_data)){
                    $id = DB::table('users')->where('id',$user_id)->update(
                        ['email' => "$email", 'name' => "$name", 'phone_num' => "$phone", 'dob' => "$dob", 'gender' => "$gender", 
                        'address' => "$address", 'city' => "$city", 'image' => "$file", 'modifieddate' => getCurrentDateTime()]
                    );

                    return retJson(['success' => '1', 'message' => 'Profile Updated Successfully']);
                }else{
                    return retJson(['success' => '0', 'message' => "User Does Not Exist"]);

                }
        }else{
            return retJson(['status' => '0', 'message' => "Token Expired"]);
        }
            
    }

    public function resetPassword(Request $request)
    {
        if(auth()->check() == 1){
            $user_data =  response()->json(auth()->user());
            $data = json_decode($user_data->content(), true);

            $validator = Validator::make($request->all(),[
                'password' => 'required'
            ]);

            if($validator->fails())
            {
                return retJson(['status' => '0', 'message' => "password Required"]);
            }

            $id = $data['id'];
            
            $password = Hash::make($request->password);

            DB::table('users')->where('id',$id)->update(
                ['password' => "$password", 'modifieddate' => getCurrentDateTime()]
            );

            return retJson(['success' => '1', 'message' => "Password Changed Successfully"]);

        }else{
            return retJson(['status' => '0', 'message' => "Token Expired"]);
        }
    }


    public function logout()
    {
        if(auth()->check() == 1){
            auth()->logout();

            return response()->json(['status' => '1', 'message' => 'Successfully logged out']);
        }else{
            return retJson(['status' => '0', 'message' => "Token Expired"]);
        }
    } 

    public function bookProperty(Request $request)
    {
        if(auth()->check() == 1){
            $user_data =  response()->json(auth()->user());
            $data = json_decode($user_data->content(), true);
            $user_id = $data['id'];

            $validator = Validator::make($request->all(),[
                'property_id' => 'required',               
            ]);

            if($validator->fails())
            {
                return retJson(['status' => '0', 'message' => "property_id Required"]);
            }

            $property_id = $request->property_id;
            $property_data = get_row_by_id($property_id,'property','id');

            DB::table('cart')->insert(
                ['status' => "pending",'landlord_id' => "$property_data->landlord_id",'property_id' => "$property_id",'user_id' => "$user_id", 'createdate' => getCurrentDateTime(), 'modifieddate' => getCurrentDateTime()]
            );

            return retJson(['success' => '1', 'message' => "Property Booked"]);

        }else{
            return retJson(['status' => '0', 'message' => "Token Expired"]);
        }
    }

    public function bookingList()
    {
        if(auth()->check() == 1){
            $user_data =  response()->json(auth()->user());
            $data = json_decode($user_data->content(), true);
            $landlord_id = $data['id'];

            $property = Apimodel::getPropertyData($landlord_id);
            $response = array();

            foreach ($property as $key => $value) {
                $response[$key]['cart_id'] = $value->id;
                $response[$key]['property_name'] = $value->property_name;
                $response[$key]['property_address'] = $value->property_name;
                $response[$key]['booked_date'] = $value->createdate;
                $response[$key]['user_name'] = $value->user_name;
                $response[$key]['image'] = asset("assets/uploads/user/$value->image");


            }            

            return retJson(['status' => '1', 'message' => "Property Data", 'response' => $response]);

        }else{

            return retJson(['status' => '0', 'message' => "Token Expired"]);
        }
    }

    public function manageBooking(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'cart_id' => 'required',  
            'type' => 'required'            
        ]);

        if($validator->fails())
        {
            return retJson(['status' => '0', 'message' => "property_id Required"]);
        }

        $cart_id = $request->cart_id;
        $status = $request->type;

        $cart_data = get_row_by_id($cart_id,'cart','id');

        DB::table('cart')->where('id',$cart_id)->update(
            ['status' => "$status", 'modifieddate' => getCurrentDateTime()]
        );

       
        if($status == "accept"){
            DB::table('property')->where('id',$cart_data->property_id)->update(
                ['status' => "$status", 'modifieddate' => getCurrentDateTime()]
            );
            return retJson(['success' => '1', 'message' => "Booking Accepted"]);
        }else{
            return retJson(['success' => '1', 'message' => "Booking Declined"]);

        }
    }

    



    
    
}

