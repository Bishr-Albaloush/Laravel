<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use App\Models\Models\Expert;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Validator;
use Auth;

class AuthController extends Controller
{

    use GeneralTrait;
    public function register_user(Request $request)
    {   
        try
        {
            //Validate data
            $data = $request->only('name', 'email', 'password');
            $validator = Validator::make($data, [
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6|max:50'
            ]);

            //Send failed response if request is not valid
            if ($validator->fails()) {
                return response()->json(['error' => $validator->messages()], 200);
            }

            //Request is valid, create new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);

            //User created, return success response
            return $this->returnData('user', $user);
        } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function register_expert(Request $request)
    {
        try
        {
    	    //Validate data
            $data = $request->only('name', 'email', 'password', 'experience', 'category_id', 'phone', 'image', 'address');
            $validator = Validator::make($data, [
                'name' => 'required|string',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:6|max:50',
                'experience' => 'required|string',
                'category_id' => 'required|string',
                'phone' => 'required|string',
                'image' => '',
                'address' => 'required',
            ]);

            //Send failed response if request is not valid
            if ($validator->fails()) {
                return $this->returnError('E200', $validator->messages());
            }
            if($request->hasFile('image'))
            {
                $filename = $request->file('image')->store('posts', 'public');
            }
            else
            {
                $filename = "posts/DEFAULT.jpg";
            }
            //Request is valid, create new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $expert = Expert::create([
                'experience' => $request->experience,
                'category_id' => $request->category_id,
                'phone' => $request->phone,
                'image' =>$filename,
                'address' => $request->address,
                'user_id' => $user->id
            ]);

            //User created, return success response
            return $this->returnData('expert', $expert->with(['user'])->where('user_id', $user->id)->get());
        } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function login(Request $request)
    {

        try {
            $rules = [
                "email" => "required",
                "password" => "required"

            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                $code = $this->returnCodeAccordingToInput($validator);
                return $this->returnValidationError($code, $validator);
            }

            //login

            $credentials = $request->only(['email', 'password']);

            $token = Auth::guard('user-api')->attempt($credentials);  //generate token

            if (!$token)
                return $this->returnError('E001', 'Credentials is not correct');

            $user = Auth::guard('user-api')->user();
            $user ->api_token = $token;
            //return token
            return $this->returnData('user', $user);  //return json response

        } catch (\Exception $ex) {
            return $this->returnError($ex->getCode(), $ex->getMessage());
        }
    }

    public function logout(Request $request)
    {
        try{
         $token = $request -> header('auth-token');
        if($token){
            try {

                JWTAuth::setToken($token)->invalidate(); //logout
            }catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
                return  $this -> returnError('','some thing went wrongs');
            }
            return $this->returnSuccessMessage('Logged out successfully');
        }else{
            $this -> returnError('','some thing went wrongs');
        }
    } catch (\Exception $ex) {
        return $this->returnError($ex->getCode(), $ex->getMessage());
    }

    }
}
