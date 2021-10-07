<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessagesController;
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

Route::get('/', function () {
    return redirect()->route('chat');
});

Auth::routes();

Route::get('/chat', [ChatController::class, 'index'])->name('chat');
Route::get('/messages', [MessagesController::class, 'index'])->name('messages');
Route::post('/message', [MessagesController::class, 'store'])->name('message.store');
