<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;

use Illuminate\Support\Facades\Auth;
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

Route::get('/test-ws', function () {
    event(new \App\Events\PlaygroundEvent());
    echo "oke";
});


Route::get('/', function () {
    return view('welcome');
});


Route::get('users', [UserController::class, 'index'])->name('users');

Route::get('/login/{id}', function($id){
    Auth::loginUsingId($id);

    return redirect()->route('converstation');
})->name('login');

Route::group(['middleware' => ['auth']], function() {
    Route::get('/logout', function(){
        Auth::logout();
    
        return redirect('users');
    })->name('logout');
    
    Route::get('/chat-p2p', [ChatController::class, 'index'])->name("converstation");
});

Route::post('/chat-message', [ChatController::class, 'chat'])->name("chat");