<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DrawResultController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ResultHistoryController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/',[HomeController::class,'index'])->name('home');

Route::get('/next-draw', [DrawResultController::class, 'getNextDraw']);


Route::get('admin', [LoginController::class, 'login'])->name('login');
Route::post('admin/login-user', [LoginController::class, 'loginUser'])->name('login-user');

Route::get('draw-results', [DrawResultController::class, 'index'])->name('draw-results');
Route::get('draw-results/data', [DrawResultController::class, 'getData'])->name('draw-results.data');
Route::post('draw-results/save', [DrawResultController::class, 'storeData'])->name('draw-results.store');
Route::get('draw-results/getTimes', [DrawResultController::class, 'getTimes'])->name('draw-results.getTimes');
Route::get('/get-latest-draw-result', [DrawResultController::class, 'getLatestDrawResult']);

Route::get('result-history', [ResultHistoryController::class, 'index'])->name('result-history');
Route::get('result-history/data', [ResultHistoryController::class, 'getData'])->name('result-history.data');
