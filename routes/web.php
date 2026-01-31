<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EtrafController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\FatherScheduleController;
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
    return view('welcome');
});

Route::prefix('auth')->group(function () {
    Route::get('/login', [AuthController::class, 'loginPage'])->name('loginPage');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Users Routes
Route::resource('users', UserController::class)->only(['index', 'show']);

// Father Schedules Routes
Route::resource('father-schedules', FatherScheduleController::class)->except(['edit', 'update', 'show']);

// Atraf Routes
Route::resource('atraf', EtrafController::class)->except(['edit', 'update', 'destroy']);
Route::patch('atraf/{id}/update-status', [EtrafController::class, 'updateStatus'])->name('atraf.update-status');
Route::get('atraf/user-report/{user_id}', [EtrafController::class, 'userReport'])->name('atraf.user-report');
Route::post('atraf/father-daily-report/{date}', [EtrafController::class, 'fatherDailyReport'])->name('atraf.father-daily-report');

// Families Routes
Route::get('families', [FamilyController::class, 'index'])->name('families.index');
Route::get('families/{familyCode}', [FamilyController::class, 'show'])->name('families.show');
Route::get('families/{familyCode}/export', [FamilyController::class, 'export'])->name('families.export');
