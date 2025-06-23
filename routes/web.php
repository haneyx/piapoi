<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CabezaController;

// LOGIN
Route::match(['get', 'post'], '/', [LoginController::class, 'login'])->name('login');
// OODD & OOCC
Route::match(['get', 'post'], '/ooddselect', [CabezaController::class, 'ooddselect'])->name('ooddselect');
Route::match(['get', 'post'], '/ooddmain', [CabezaController::class, 'ooddmain'])->name('ooddmain');
Route::match(['get', 'post'], '/ooccmain', [CabezaController::class, 'ooccmain'])->name('ooccmain');

//Route::get('/ooddselect', [CabezaController::class, 'ooddselect'])->name('ooddselect');
Route::get('/consolida_red', [CabezaController::class, 'consolida_red'])->name('consolida_red');
Route::get('/consolida_oocc', [CabezaController::class, 'consolida_oocc'])->name('consolida_oocc');

Route::match(['get', 'post'], '/ooddhoja', [CabezaController::class, 'ooddhoja'])->name('ooddhoja');
Route::post('/grabaooddhoja', [CabezaController::class, 'grabaooddhoja'])->name('grabaooddhoja');
Route::match(['get', 'post'], '/exportaooddhoja', [CabezaController::class, 'exportaooddhoja'])->name('exportaooddhoja');
Route::post('/cerrarooddhoja', [CabezaController::class, 'cerrarooddhoja'])->name('cerrarooddhoja');

//Route::match(['get', 'post'], '/grabarooddhoja', [CabezaController::class, 'grabarooddhoja'])->name('grabarooddhoja');

// OOCC
//Route::get('/ooccselect', [CabezaController::class, 'ooccselect'])->name('ooccselect');


// LOGOUT
Route::match(['get', 'post'], '/logout', [LoginController::class, 'logout'])->name('logout');