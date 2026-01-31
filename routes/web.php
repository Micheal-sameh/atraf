<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EtrafController;
use App\Http\Controllers\FamilyController;
use App\Http\Controllers\FatherScheduleController;
use App\Http\Controllers\SettingController;
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

Route::middleware(['auth'])->group(function () {

    // Users Routes - Only father and admin
    Route::middleware(['role:father,admin'])->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{id}', [UserController::class, 'show'])->name('users.show');
    });

    // User Role Management - Only admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('users/{id}/edit-roles', [UserController::class, 'editRoles'])->name('users.edit-roles');
        Route::put('users/{id}/update-roles', [UserController::class, 'updateRoles'])->name('users.update-roles');
    });

    // Father Schedules Routes - Only father and admin
    Route::middleware(['role:father,admin'])->group(function () {
        Route::get('father-schedules', [FatherScheduleController::class, 'index'])->name('father-schedules.index');
        Route::get('father-schedules/create', [FatherScheduleController::class, 'create'])->name('father-schedules.create');
        Route::post('father-schedules', [FatherScheduleController::class, 'store'])->name('father-schedules.store');
        Route::delete('father-schedules/{id}', [FatherScheduleController::class, 'destroy'])->name('father-schedules.destroy');
    });

    // Atraf Routes - All authenticated users
    Route::get('atraf', [EtrafController::class, 'index'])->name('atraf.index');
    Route::get('atraf/create', [EtrafController::class, 'create'])->name('atraf.create');
    Route::post('atraf', [EtrafController::class, 'store'])->name('atraf.store');
    Route::get('atraf/available-slots', [EtrafController::class, 'getAvailableSlots'])->name('atraf.available-slots');
    Route::get('atraf/my-etraf', [EtrafController::class, 'myEtraf'])->name('atraf.my-etraf');
    Route::get('atraf/{id}', [EtrafController::class, 'show'])->name('atraf.show');
    Route::patch('atraf/{id}/update-status', [EtrafController::class, 'updateStatus'])->name('atraf.update-status');
    Route::get('atraf/user-report/{user_id}', [EtrafController::class, 'userReport'])->name('atraf.user-report');
    Route::post('atraf/father-daily-report/{date}', [EtrafController::class, 'fatherDailyReport'])->name('atraf.father-daily-report');

    // Families Routes - Only father and admin
    Route::middleware(['role:father,admin'])->group(function () {
        Route::get('families', [FamilyController::class, 'index'])->name('families.index');
        Route::get('families/{familyCode}', [FamilyController::class, 'show'])->name('families.show');
        Route::get('families/{familyCode}/export', [FamilyController::class, 'export'])->name('families.export');
    });

    // Settings Routes - Only admin
    Route::middleware(['role:admin'])->group(function () {
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    });
});
