<?php

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

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/', \App\Http\Controllers\MainPageController::class);
Route::post('/comment', \App\Http\Controllers\Comments\CreateCommentController::class);
Route::patch('/comment/{id}', \App\Http\Controllers\Comments\EditCommentController::class);
Route::delete('/comment/{id}', \App\Http\Controllers\Comments\DeleteCommentController::class);
