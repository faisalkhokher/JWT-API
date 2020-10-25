<?php

namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Auth;

class AuthController extends Controller
{
    public $successStatus = true;
    public $loginAfterSignUp = true;

    public function register(Request $request)
    {
      $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
      ]);

      $token = auth()->login($user);


      return $this->respondWithToken($token);
    }

    public function login(Request $request)
    {
      $credentials = $request->only(['email', 'password']);

      if (!$token = auth()->attempt($credentials)) {
        return response()->json(['error' => 'Unauthorized'], 401);
      }
      
      return $this->respondWithToken($token);
    }
    public function getAuthUser(Request $request)
    {
        // return response()->json(auth()->user());
        $user = Auth::user();
        // $success['name'] = $user -> name;
        // $success['email'] = $user -> email;
        // $success['password'] = $user -> password;

        $success = [
            'name' => $user -> name,
            'email' => $user -> email,
            'password' =>   $user -> password,
            'remember Token' => $user -> remember_token
        ];
        return response()->json(['success' => $success], 200); 
    }
    public function logout()
    {
        auth()->logout();
        return response()->json(['message'=>'Successfully logged out']);
    }
    protected function respondWithToken($token)
    {
      return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => auth()->factory()->getTTL() * 60
      ]);
    }

}
