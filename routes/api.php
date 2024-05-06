<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TransactionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group([
    'middleware' => 'auth:sanctum'
], function() {
    Route::get('/', [TransactionController::class, 'index']);
    Route::get('deposit', [TransactionController::class, 'getDeposits']);
    Route::post('deposit', [TransactionController::class, 'deposit']);
    Route::get('withdrawal', [TransactionController::class, 'getWithdrawal']);
    Route::post('withdrawal', [TransactionController::class, 'withdraw']);

    Route::post('logout', [AuthController::class, 'logout']);
});

Route::post('users', [UserController::class, 'store']);
Route::post('login', [AuthController::class, 'login']);