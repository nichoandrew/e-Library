<?php

use App\Http\Controllers\LibraryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return redirect('/login');
});

Route::get('/login', [LibraryController::class, 'login'])->name('login');
Route::get('/register', [LibraryController::class, 'register'])->name('register');
Route::get('/profil', [LibraryController::class, 'profil'])->name('profil');
Route::get('/dashboard_admin', [LibraryController::class, 'index_admin'])->name('dashboard_admin');
Route::get('/dashboard_mahasiswa', [LibraryController::class, 'index_mahasiswa'])->name('dashboard_mahasiswa');



Route::post('/login', [AuthController::class, 'login'])->name('login_user');
Route::post('/register', [AuthController::class, 'register'])->name('register_user');

Route::prefix('admin')->middleware('auth')->group(function (){
    Route::get('/dashboard_admin', [LibraryController::class, 'index_admin'])->name
    ('dashboard_admin');
    
    Route::delete('/buku/{id}', [LibraryController::class, 'delete_buku'])->name
    ('buku.delete');
    Route::post('/buku', [LibraryController::class, 'create_buku'])->name
    ('buku.create');
    Route::put('/buku/{id}', [LibraryController::class, 'edit_buku'])->name
    ('buku.edit');

    Route::delete('/mahasiswa/{id}', [LibraryController::class, 'delete_mahasiswa'])->name
    ('mahasiswa.delete');
    Route::post('/mahasiswa', [LibraryController::class, 'create_mahasiswa'])->name
    ('mahasiswa.create');
    Route::put('/mahasiswa/{id}', [LibraryController::class, 'edit_mahasiswa'])->name
    ('mahasiswa.edit');
});
Route::prefix('mahasiswa')->middleware('auth:mahasiswa')->group(function (){
    Route::get('/dashboard_mahasiswa', [LibraryController::class, 'index_mahasiswa'])->name
    ('dashboard_mahasiswa');
});

Route::middleware('auth:mahasiswa')->group(function () {
    Route::post('/borrow/{mahasiswaId}/{namamahasiswa}/{kelasmahasiswa}/{bukuId}/{judulBuku}/{mahasiswaEmail}', [LibraryController::class, 'borrow_book'])->name('borrow_book');
    Route::post('/return-book/{mahasiswaId}/{bukuId}', [LibraryController::class, 'return_book'])->name('return_book');
});

