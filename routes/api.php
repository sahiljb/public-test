<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\LeadsController;
use App\Http\Controllers\Api\LoanTypeController;
use App\Http\Controllers\Api\ProposalController;
use App\Http\Controllers\Api\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::group(['middleware' => 'api','prefix' => 'auth'], function ($router) {

    Route::post('/login', [AuthController::class, 'login'])->name('login');
    
    
});

Route::middleware(['auth.jwt'])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/update-profile', [AuthController::class, 'updateProfile'])->name('update-profile');
    Route::post('/change-password', [AuthController::class, 'updatePassword'])->name('change-password');



    Route::prefix('leads')->group(callback: function () {
        Route::post('/list', [LeadsController::class, 'index'])->name('apileads.list');
        Route::post('/update-status', [LeadsController::class, 'update'])->name('apileads.update');
    });

    Route::prefix('proposal')->group(callback: function () {
        
        Route::post('/save', [ProposalController::class, 'store'])->name('proposal.store');
    });
    


});