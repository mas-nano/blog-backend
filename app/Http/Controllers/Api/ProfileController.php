<?php

namespace App\Http\Controllers\Api;

use App\Helper\Media;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    use Media;
    public function profile()
    {
        return $this->responseSuccessWithData(
            "User",
            Auth::user()->load("profile")
        );
    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'biodata' => 'nullable|string',
            'photo' => 'nullable|mimes:jpg,png,jpeg|max:2048'
        ]);

        if ($validator->fails()) {
            return $this->responseValidation($validator->errors());
        }
        if (!is_null(Auth::user()->profile->photo)) {
            if (Storage::exists(Auth::user()->profile->photo)) {
                Storage::delete(Auth::user()->profile->photo);
            }
        }

        $filePath = $this->uploads($request->file('photo'), 'image/profile');

        $validated = $validator->validated();
        Auth::user()->update([
            'name' => $validated['name']
        ]);

        Auth::user()->profile()->update([
            'biodata' => $validated['biodata'] ?: null,
            'photo' => is_null(Auth::user()->profile->photo) ? ($filePath ?: null) : ($filePath ?: Auth::user()->profile->photo)
        ]);

        return $this->responseSuccess("Profile berhasil diubah");
    }
}
