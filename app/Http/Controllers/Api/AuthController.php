<?php

namespace App\Http\Controllers\Api;

use App\Helper\Media;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    use Media;

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|unique:users|email:dns,rfc",
            "password" => "required|min:8|confirmed",
            "password_confirmation" => "required",
        ]);

        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }

        $validated = $validator->validated();
        $validated["password"] = bcrypt($validated["password"]);

        try {
            $user = User::create($validated);
            Profile::create([
                "user_id" => $user->id,
            ]);
            return $this->responseCreated("Data berhasil ditambahkan");
        } catch (QueryException $e) {
            return $this->responseError(
                "Internal Server Error",
                500,
                $e->errorInfo
            );
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" => "required|email:dns,rfc",
            "password" => "required",
        ]);

        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }

        $token = Auth::attempt($validator->validated());

        if (!$token) {
            return $this->responseError("Unauthorized", 401);
        }

        $data = [
            "user" => Auth::user(),
            "token" => $token,
            "type" => "Bearer",
        ];

        return $this->responseSuccessWithData("Login success", $data);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            "status" => "success",
            "message" => "Successfully logged out",
        ]);
    }

    public function refresh()
    {
        $data = [
            "user" => Auth::user(),
            "token" => Auth::refresh(),
            "type" => "Bearer",
        ];

        return $this->responseSuccessWithData("Refresh success", $data);
    }

    public function me()
    {
        $user = auth()->user()->load('profile');
        $user->profile->photo = Storage::url($user->profile->photo);
        return $this->responseSuccessWithData("user", $user);
    }

    public function updateUser(User $user, Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            "email" => "required|email:dns,rfc",
            "password" => "nullable|min:8|confirmed",
            "password_confirmation" => "nullable",
            'biodata' => 'nullable|max:100',
            'photo' => 'sometimes|max:2048'
        ]);

        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }

        $validated = $validator->validated();
        if ($request->has('password')) {
            $validated["password"] = bcrypt($validated["password"]);
        }

        if ($request->has('biodata')) {
            $user->profile->update([
                'biodata' => $validated['biodata']
            ]);
        }

        dd($request->file('photo'));
        if ($request->hasFile('photo')) {
            $filePath = $this->uploads($request->file('photo'), 'image/profile');
            dd($filePath);
            $user->profile->update([
                'photo' => $filePath
            ]);
        }

        try {
            $user->update($validated);
            return $this->responseCreated("Data berhasil diubah");
        } catch (QueryException $e) {
            return $this->responseError(
                "Internal Server Error",
                500,
                $e->errorInfo
            );
        }
    }
}
