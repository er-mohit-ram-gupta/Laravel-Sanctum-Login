<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use App\Models\User;
use Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email',
        ]);
        if($validator->fails()){
            $response =[
                'success'=> false,
                'message' => $validator->errors()
            ];
            return $response()->json($response,400);
        }
            
        
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);

        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;

        $response = [
            'success'=> true,
            'data'=> $success,
            'message'=> "USer Register Successfully"
        ];
        return response()->json($response,200);
    }

    public function login(Request $request){

        if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password])){
            $user = Auth::User();
            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;

            $response = [
                'success'=> true,
                'data'=> $success,
                'message'=> "USer Login Successfully"
            ];
            return response()->json($response,200);
        }else{
            $response = [
                'success'=>false,
                'message'=> 'Unauthorized User'
            ];
        }
        return response()->json($response);
    }
}
