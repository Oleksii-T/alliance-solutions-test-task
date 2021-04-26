<?php

use App\Http\Controllers\EmployerController;
use App\Http\Controllers\AuthApiController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

// authentication routes
Route::post('/register',    [AuthApiController::class, 'register']);
Route::post('/login',       [AuthApiController::class, 'login']);

// protect routes via Bearer token using Sanctum package
Route::middleware('auth:sanctum')->group(function () {
    // authentication logout routes
    Route::post('/logout',      [AuthApiController::class, 'logout']);

    // task methods
    Route::post('/',       [Controller::class, '']); //get list of clients who made a paymend due to date (include payment amount)
    Route::post('/',       [Controller::class, '']); //
    Route::post('/payments',            [PaymentController::class, 'paymentsByDate']); //list of payments due to date
    Route::post('/wages',               [EmployerController::class, 'wagesByMonth']); //list of full wage of employers grouped by month
    Route::post('/wages/avarage',       [EmployerController::class, 'medianWagesByDate']); //avarage full wage of all employers due to date
    Route::post('/payments/countries',  [PaymentController::class, 'paymentsByCounry']); //avarage payments amount grouped by counry
    Route::post('/',       [Controller::class, '']); //amount of clients who make more than one payment with all employers
});