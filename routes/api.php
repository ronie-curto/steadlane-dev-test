<?php

use App\Http\Controllers\MedicationController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('patients', 'PatientController')->except('destroy');
    
    Route::get('medications', [MedicationController::class, 'index']);
});

Route::post('users/sign-up', [UserController::class, 'signUp']);
