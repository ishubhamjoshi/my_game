<?php

use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DrawResultController;
use App\Http\Controllers\HomeController;

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
Route::post('/change-password', [LoginController::class, 'changePassword'])->name('change-password');

Route::get('draw-results', [DrawResultController::class, 'index'])->name('draw-results');
Route::get('draw-results/data', [DrawResultController::class, 'getData'])->name('draw-results.data');
Route::post('draw-results/save', [DrawResultController::class, 'storeData'])->name('draw-results.store');
Route::get('draw-results/getTimes', [DrawResultController::class, 'getTimes'])->name('draw-results.getTimes');
Route::get('/get-latest-draw-result', [DrawResultController::class, 'getLatestDrawResult']);

Route::get('result-history', [DrawResultController::class, 'ResultIndex'])->name('result-history');
Route::get('result-history/data', [DrawResultController::class, 'getResultData'])->name('draw-results.resultData');
