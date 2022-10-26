<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\PhotoController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post("/login", [AuthController::class, "login"]);

Route::group(["middleware" => "api"], function () {
    Route::group(["middleware" => "jwt.verify"], function () {
        Route::get('/account', [AuthController::class, 'me']);
        Route::post("/logout", [AuthController::class, "logout"]);
        Route::post("/refresh", [AuthController::class, "refresh"]);

        Route::prefix('category')->group(function () {
            Route::post("/", [CategoryController::class, "updateOrCreateCategory"]);
            Route::get("/", [CategoryController::class, "getCategories"]);
            Route::delete('/{category}', [CategoryController::class, 'deleteCategory']);
        });

        Route::prefix('profile')->group(function () {
            Route::get("/", [ProfileController::class, "profile"]);
            Route::post("/", [ProfileController::class, 'updateProfile']);
        });

        Route::prefix('post')->group(function () {
            Route::get("/", [PostController::class, "getAllPost"]);
            Route::post("/image", [PostController::class, "uploadImage"]);
            Route::get("/{uuid}", [PostController::class, "getSinglePost"]);
            Route::post("/", [PostController::class, "createPost"]);
            Route::post("/{uuid}", [PostController::class, "updatePost"]);
            Route::delete('/{post:uuid}', [PostController::class, 'deletePost']);
        });

        Route::prefix('photo')->group(function () {
            Route::get('/', [PhotoController::class, 'index']);
            Route::post('/{photo}', [PhotoController::class, 'delete']);
        });

        Route::prefix('user')->group(function () {
            Route::get('/', [UserController::class, 'getUsers']);
            Route::post("/register", [AuthController::class, "register"]);
            Route::post('/update/{user}', [AuthController::class, 'updateUser']);
            Route::delete('/delete/{user}', [UserController::class, 'deleteUser']);
        });
    });
});
