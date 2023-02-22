<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Traits\GeneralTrait;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class AuthController extends Controller
{
    // trait to generate Error and success message
    use GeneralTrait;
   
   // login
   public function login(Request $request){


    // validation
    try{
    $rules=[

        'email'=>'required',
        'password'=>'required'
    ];
    $validator = Validator::make($request->all(),$rules);

    if($validator->fails()){
        return $this->returnError('E004','email or password not Exist');

    }

//login
$credentiel= $request->only(['email','password']);

$token = JWTAuth::attempt($credentiel);
    if(!$token)
        return $this->returnError('E001','email and password not correct');

// generate Auth

 $user = JWTAuth::user();
 $user->remember_token=$token;
//return token jwt
return $this->returnData('user',$user,'succes',$token);

}catch(Exception $e){
    return $this->returnError($e->getCode(),$e->getMessage());
}


}

    // register
    public function register(Request $request)
    {

            // validation
     try{


        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if($validator->fails()){

                return $this->returnError('E003','Some inputs Not correct!');
        }


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // generate Auth

        $token = JWTAuth::fromUser($user);

      return $this->returnData('user',$user,'register Successfully',$token);


    }
    catch(Exception $e){
        return $this->returnError($e->getCode(),$e->getMessage());
    }
}
// logout
public function logout()
    {

            // Auth::logout();
            // return $this->returnSuccessMessage("Logout has been success!","S002");
            try {

                if (! $user = JWTAuth::parseToken()->authenticate()) {
                        return response()->json(['user_not_found'], 404);
                }

        } catch (TokenExpiredException $e) {

                return response()->json(['token_expired'], $e->getMessage());

        } catch (TokenInvalidException $e) {

                return response()->json(['token_invalid'], $e->getMessage());

        } catch (JWTException $e) {

                return response()->json(['token_absent'], $e->getMessage());

        }

        return response()->json(compact('user'));
}


}
