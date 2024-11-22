<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReceivedBookController;
use App\Http\Controllers\FollowBookController;
use App\Http\Controllers\BookFileController ;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [ReceivedBookController::class, 'ReceivedBook'])->name('ReceivedBook');
Route::post('/received-books/store', [ReceivedBookController::class, 'store'])->name('store');

Route::get('/follow/book', [FollowBookController::class, 'FollowBook'])->name('FollowBook');
Route::get('receivedbooks/{id}', [ReceivedBookController::class, 'show'])->name('receivedbooks.show');

Route::get('/book/file', [BookFileController::class, 'bookfile'])->name('bookfile');
Route::get('/book/file/{id}/view', [BookFileController::class, 'viewFile'])->name('viewFile');


