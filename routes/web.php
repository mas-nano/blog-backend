<?php

use App\Http\Controllers\Client\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get("/", [PostController::class, 'index']);
Route::get("/{post:slug}", [PostController::class, 'show'])->name('post.show');
Route::prefix("/admin")->group(function () {
  Route::get("dashboard", function () {
    return view("dashboard.index");
  });
});
