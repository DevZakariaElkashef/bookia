<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use App\Models\User;
use App\Traits\BaseApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use BaseApi;

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);

        if ($validator->fails()){
            return $this->sendResponse('', $validator->errors()->first(), 403);
        }

        $user = User::where('email', $request->email)->first();
        $password = $user->password;

        if (!Hash::check($request->password, $password)) {
            return $this->sendResponse('', "Password Is Wrong", 403);

        }

        $data = new AuthResource($user);

        return $this->sendResponse($data, '');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|min:3',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:255|string|same:confirm_password'
        ]);

        if ($validator->fails()){
            return $this->sendResponse('', $validator->errors()->first(), 403);
        }

        $user = User::create($request->all());

        $data = new AuthResource($user);

        return $this->sendResponse($data, '');
    }

    public function logout(Request $request)
    {

        $user = $request->user();
        $user->tokens()->delete();

        return $this->sendResponse('', "Logout success!");
    }


    public function forgetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse('', $validator->errors()->first(), 403);
        }

        $user = User::where('email', $request->email)->first();
        $otp = rand(1111, 9999);
        $user->update(['otp' => $otp]);


        return $this->sendResponse($otp, 'OTP sent to you mail successfully!');
    }



    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
            'otp' => 'required|exists:users,otp'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse('', $validator->errors()->first(), 403);
        }

        $user = User::where('email', $request->email)->first();
        $data = [
            'token' => $user->createToken('api')->plainTextToken
        ];

        return $this->sendResponse($data, 'OTP is correct, update your password!');
    }


    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8|max:255|string|same:confirm_password'
        ]);

        if ($validator->fails()){
            return $this->sendResponse('', $validator->errors()->first(), 403);
        }


        $user = $request->user();
        $user->update($request->all());

        return $this->sendResponse('', 'Password Updated Successfully!');
    }


}
