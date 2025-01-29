<?php

use App\Http\Controllers\ColaboratorController;
use App\Http\Controllers\ConfirmAccountController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\HrUserController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

Route::get('/email', function(){
    Mail::raw('management test message', function(Message $message){
        $message->to('test@gmail.com')
        ->subject('Welcome!')
        ->from('frz@management.com');
    });

    echo 'email susccessfully sended!';
});

Route::middleware('auth')->group(function(){
    Route::redirect('/', 'home');
    Route::view('/home', 'home')->name('home');

    Route::prefix('/user')->group(function(){
        Route::get('/profile', [ProfileController::class, 'index'])->name('user.profile');
        Route::post('/profile/update-password', [ProfileController::class, 'updatePassword'])->name('user.profile.update-password');
        Route::post('/profile/update-user-data', [ProfileController::class, 'updateUserData'])->name('user.profile.update-user-data');
    });

    Route::prefix('/departments')->group(function(){
        Route::get('/', [DepartmentController::class, 'index'])->name('departments');
        Route::get('/new-department', [DepartmentController::class, 'newDepartment'])->name('departments.new-department');
        Route::post('/create-department', [DepartmentController::class, 'createDepartment'])->name('departments.create-department');
        Route::get('/edit-department/{id}', [DepartmentController::class, 'editDepartment'])->name('departments.edit-department');
        Route::post('/update-department', [DepartmentController::class, 'updateDepartment'])->name('departments.update-department');
        Route::get('/delete-department/{id}', [DepartmentController::class, 'deleteDepartment'])->name('departments.delete-department');
        Route::get('/delete-department-confirm/{id}', [DepartmentController::class, 'deleteDepartmentConfirm'])->name('departments.delete-department-confirm');
    });

    Route::prefix('/hr')->group(function(){
        Route::get('/', [HrUserController::class, 'index'])->name('hr-users');
        Route::get('/new-colaborator', [HrUserController::class, 'newColaborator'])->name('hr.new-colaborator');
        Route::post('/create-colaborator', [HrUserController::class, 'createHRColaborator'])->name('hr.create-colaborator');
        Route::get('/edit-colaborator/{id}', [HrUserController::class, 'editHRColaborator'])->name('hr.edit-colaborator');
        Route::post('/update-colaborator', [HrUserController::class, 'updateHRColaborator'])->name('hr.update-colaborator');
        Route::get('/delete-colaborator/{id}', [HrUserController::class, 'deleteHRColaborator'])->name('hr.delete-colaborator');
        Route::get('/delete-colaborator-confirm/{id}', [HrUserController::class, 'deleteHRColaboratorConfirm'])->name('hr.delete-colaborator-confirm');
        Route::get('/restore/{id}', [HrUserController::class, 'restoreHRColaborator'])->name('hr.restore');
    });

    Route::prefix('/colaborators')->group(function(){
        Route::get('/', [ColaboratorController::class, 'index'])->name('colaborators');
        Route::get('/details/{id}', [ColaboratorController::class, 'showDetails'])->name('colaborators.details');
        Route::get('/delete/{id}', [ColaboratorController::class, 'deleteColaborator'])->name('colaborators.delete');
        Route::get('/delete-confirm/{id}', [ColaboratorController::class, 'deleteColaboratorConfirm'])->name('colaborators.delete-confirm');
        Route::get('/restore/{id}', [ColaboratorController::class, 'restoreColaborator'])->name('colaborators.restore');
    });
});

Route::middleware('guest')->group(function(){
    Route::get('/confirm-account/{token}', [ConfirmAccountController::class, 'confirmAccount'])->name('confirm-account');
    Route::post('/confirm-account', [ConfirmAccountController::class, 'confirmAccountSubmit'])->name('confirm-account-submit');
});