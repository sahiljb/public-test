<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\siteadmin\AdminAuthController;
use App\Http\Controllers\siteadmin\AssignLeadsController;
use App\Http\Controllers\siteadmin\CustomerController;
use App\Http\Controllers\siteadmin\ExcelUploadController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('customer.list', ['staff']);
    } else {
        return redirect()->route('login');
    }
});


Route::prefix('siteadmin')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });

    Route::group(['middleware' => ['guest']], function () {
        Route::get('/login', function () {
            return view('pages.authentication.cover.signin', ['title' => 'Login', 'breadcrumb' => 'Signin']);
        })->name('login');
    });

    Route::post('/login', [AuthController::class, 'loginProcess'])->name('login');

    Route::group(['middleware' => ['auth']], function () {
        Route::prefix('profile')->group(function () {
            Route::get('/change-password', [AdminAuthController::class, 'index'])->name('profile.change-password');
            Route::post('/update-password-process', [AdminAuthController::class, 'updatePassword'])->name('profile.update-password-process');
        });

        Route::prefix('customer')->group(function () {
            Route::get('/list/{role}', [CustomerController::class, 'index'])->name('customer.list');
            Route::get('/create', [CustomerController::class, 'create'])->name('customer.create');
            Route::post('/store', [CustomerController::class, 'store'])->name('customer.store');
            Route::get('/update/{id}', [CustomerController::class, 'edit'])->name('customer.update');
            Route::post('/save/{id}', [CustomerController::class, 'update'])->name('customer.save');
            Route::post('/delete/{id}', [CustomerController::class, 'destroy'])->name('customer.delete');
        });

        Route::prefix('leads')->group(function () {
            Route::get('/list', [ExcelUploadController::class, 'index'])->name('leads.list');
            Route::get('/duplicate', [ExcelUploadController::class, 'duplicateLeads'])->name('leads.duplicate');

            Route::get('/assigned', [AssignLeadsController::class, 'assignedLeads'])->name('leads.assigned');

            Route::get('/assign-lead', [AssignLeadsController::class, 'dragLeads'])->name('leads.assign-lead');
            Route::post('/process-assign', [AssignLeadsController::class, 'processAssign'])->name('leads.process-assign');

            Route::get('/upload', [ExcelUploadController::class, 'upload'])->name('leads.upload');
            Route::post('/store', [ExcelUploadController::class, 'store'])->name('leads.store');

            Route::get('/create', [ExcelUploadController::class, 'create'])->name('leads.create');
            Route::post('/single-store', [ExcelUploadController::class, 'singleStore'])->name('leads.single-store');

            Route::get('/edit/{id}', [ExcelUploadController::class, 'edit'])->name('leads.edit');
            Route::post('/save/{id}', [ExcelUploadController::class, 'update'])->name('leads.save');

            Route::post('/delete/{id}', [ExcelUploadController::class, 'destroy'])->name('leads.delete');
            Route::post('/duplicate_delete/{id}', [ExcelUploadController::class, 'duplicateDestroy'])->name('leads.duplicate.delete');

        });

        Route::prefix('dashboard')->group(function () {
            Route::get('/', function () {
                return redirect()->route('customer.list', ['staff']);
            });
        });

        Route::get('/logout', function () {
            Auth::logout();
            return redirect()->route('login');
        });
    });
});
