<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function getUsers()
    {
        $users = User::with('profile')->whereNot('role', '=', 'admin')->paginate(10)->through(function ($user) {
            $user->profile->photo == 'default' ?: Storage::url($user->profile->photo);
            return $user;
        });

        return $this->responseSuccessWithData("users", $users);
    }

    public function deleteUser(User $user)
    {
        if (!$user->posts()->exists()) {
            $user->profile()->delete();
            $user->delete();

            return $this->responseSuccess('user deleted');
        }

        return $this->responseError('has relation in post, can\'t delete', 400);
    }
}
