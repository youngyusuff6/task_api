<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\api\TaskController;
use App\Http\Controllers\api\CreditController;




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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::post('/refresh-token', [AuthController::class, 'refreshToken']);

Route::middleware('auth:api')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);

    // Task routes
    Route::post('/post-task', [TaskController::class, 'submitTask']);
    Route::get('/last-ten-tasks', [TaskController::class, 'lastTenTasks']);
    //Credit Routes
    Route::get('/credits-count', [CreditController::class, 'getCreditsCount']);
    Route::get('/purchase-history', [CreditController::class, 'getLastTenPurchasedCredits']);
    //Purchase Credit
    Route::post('/purchase-task-credits', [CreditController::class, 'purchaseTaskCredits']);

});