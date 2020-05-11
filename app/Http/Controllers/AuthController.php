<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Create user
     *
     * @param  [string] name
     * @param  [string] email
     * @param  [string] password
     * @param  [string] password_confirmation
     * @return [string] message
     */
    public function signup(Request $request)
    {
        $newUserRole = $request->user_role_id;
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:8'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ]);
        }
        $user = new User([
            'user_role_id' => $newUserRole,
            'first_name' => $request->email,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        return response()->json([
            'status' => true,
            'data' => $user,
            'message' => 'Successfully created user!'
        ], 201);
        
    }
  
    /**
     * Login user and create token
     *
     * @param  [string] email
     * @param  [string] password
     * @param  [boolean] remember_me
     * @return [string] access_token
     * @return [string] token_type
     * @return [string] expires_at
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ]);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'status' => true,
            'data' => [
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ],
            'message' => 'logged'
        ]);
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'status' => true,
            'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json(
            [  
                'status' => true,
                'data' => $request->user(),
                'message' => "user responed"
            ]
        );
    }

    public function userList(Request $request)
    {
        return response()->json(
            [  
                'status' => true,
                'data' => User::All(),
                'message' => "user responed"
            ]
        );
    }

    public function update(Request $request)
    {
        $id = $request->user()->id;
        $arr= [];
        foreach ($request->user as $param_name => $param_val) {
            if(isset($param_val))
            $arr[$param_name] = $param_val;
        }
        try {
            User::edit($id,$arr);
            return response()->json(
                [  
                    'status' => true,
                    'message' => "user responed"
                ]
            );
        } catch (\Throwable $th) {
            return response()->json(
                [  
                    'status' => false,
                    'message' => "user responed"
                ]
            ,404);
        }

    }
}