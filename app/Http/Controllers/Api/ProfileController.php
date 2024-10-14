<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Traits\BaseApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use BaseApi;

    public function index(Request $request)
    {
        $user = $request->user();

        $data = new ProfileResource($user);
        return $this->sendResponse($data, '');
    }


    public function update(Request $request)
    {
        $user = $request->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'image' => 'nullable|file|mimes:jpg,png,jpeg',
            'address' => 'nullable|string'
        ]);


        if ($validator->fails()) {
            return $this->sendResponse('', $validator->errors()->first(), 403);
        }


        $user->update($request->except('image'));

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = public_path('images/users');
            $file->move($path, $filename);

            $user->update(['image' => $filename]);
        }

        return $this->sendResponse('', 'Profile Updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|max:255|same:confirm_password|different:current_password',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse('', $validator->errors()->first(), 403);
        }


        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return $this->sendResponse('', 'Current Password is incorrect!', 403);
        }


        $user->update(['password' => $request->new_password]);
        return $this->sendResponse('', 'Password Updated Successfully!');
    }
}
