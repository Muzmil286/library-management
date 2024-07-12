<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryCantroller;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PublisherController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Index');
});
Route::resource('/categories', CategoryCantroller::class);
Route::resource('/authors', AuthorController::class);
Route::resource('/books', BookController::class);
Route::get('/api/books', [BookController::class, 'books']);
Route::resource('/languages', LanguageController::class);
Route::resource('/publishers', PublisherController::class);
Route::resource('/members', MemberController::class);
Route::resource('/borrowings', BorrowingController::class);
Route::post('/borrowings/{borrowing}/return', [BorrowingController::class, 'bookReturn']);
