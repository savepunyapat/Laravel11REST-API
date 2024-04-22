<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\NewAccessToken;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    //register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3',
        ],[
            'name.required' => 'Please enter your name',
            'email.required' => 'Please enter your email',
            'email.email' => 'Please enter a valid email',
            'email.unique' => 'This email is already taken',
            'password.required' => 'Please enter a password',
            'password.min' => 'Password must be at least 3 characters',
        
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error'],422);
        }
        // add user

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'User created successfully'
        ],201);

    }
    //login
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|',
            'password' => 'required|min:3',
            'device_name' => 'required',
        ],[
            'email.required' => 'Please enter your email',
            'email.email' => 'Please enter a valid email',
            'password.required' => 'Please enter a password',
            'password.min' => 'Password must be at least 3 characters',
        
        ]);

        if ($validator->fails()) {
            return response(['error' => $validator->errors(), 'Validation Error'],422);
        }

        // check email and password
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect'],
            ]);
        }
        $token = $user->createToken($request->device_name)->plainTextToken;

        $personal_token = PersonalAccessToken::findToken($token);

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => $personal_token->created_at->addMinutes(config('sanctum.expiration')),
        ],200);
    }

    //logout
    public function logout(Request $request)
    {
        // find user with current login
        $id = request()->user()->currentAccessToken()->id;
        // delete token
        $request->user()->tokens()->where('id', $id)->delete();

        return response()->json([
            'message' => 'Logged out'
        ],200);
    }
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'user' => [
                'fullname' => $user->officer->fullname,
                'email' => $user->email,
                'id' => $user->id,
                'picture_url' => $user->officer->picture_url
            ]
        ],200);

    }
}
