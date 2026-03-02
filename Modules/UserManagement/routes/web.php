<?php

use Illuminate\Support\Facades\Route;
use Modules\UserManagement\Http\Controllers\UserManagementController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('usermanagements', UserManagementController::class)->names('usermanagement');
});
