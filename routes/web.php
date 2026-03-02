<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\Tenant;

// frontend application URL (adjust via .env FRONTEND_URL)
$frontendUrl = config('app.frontend_url');

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) use ($frontendUrl) {

    if (! $request->hasValidSignature()) {
        return redirect()->away($frontendUrl.'/email-verified?status=invalid');
    }

    $user = User::find($id);

    if (! $user) {
        return redirect()->away($frontendUrl.'/email-verified?status=invalid');
    }

    if (! hash_equals(sha1($user->getEmailForVerification()), (string) $hash)) {
        return redirect()->away($frontendUrl.'/email-verified?status=invalid');
    }

    if ($user->hasVerifiedEmail()) {
        return redirect()->away($frontendUrl.'/email-verified?status=already_verified');
    }

    if ($user->markEmailAsVerified()) {
        event(new Verified($user));
    }

    return redirect()->away($frontendUrl.'/email-verified?status=just_verified');

})->middleware(['signed'])->name('verification.verify');

Route::get('/tenant/email/verify/{tenant}/{id}/{hash}', function (Request $request, $tenant, $id, $hash) use ($frontendUrl) {

    // 1️⃣ Check signed URL validity
    if (! $request->hasValidSignature()) {
        return redirect()->away($frontendUrl.'/email-verified?status=invalid');
    }

    // 2️⃣ Find tenant and switch DB
    $tenantModel = Tenant::find($tenant);
    if (! $tenantModel) {
        return redirect()->away($frontendUrl.'/email-verified?status=invalid');
    }

    tenancy()->initialize($tenantModel);

    // 3️⃣ Find user inside tenant DB
    $user = User::find($id);
    if (! $user) {
        tenancy()->end();
        return redirect()->away($frontendUrl.'/email-verified?status=invalid');
    }

    // 4️⃣ Validate email hash
    if (! hash_equals(sha1($user->getEmailForVerification()), (string) $hash)) {
        tenancy()->end();
        return redirect()->away($frontendUrl.'/email-verified?status=invalid');
    }

    // 5️⃣ Already verified?
    if ($user->hasVerifiedEmail()) {
        tenancy()->end();
        return redirect()->away($frontendUrl.'/email-verified?status=already_verified');
    }

    // 6️⃣ Mark as verified
    if ($user->markEmailAsVerified()) {
        event(new Verified($user));
    }

    tenancy()->end();

    return redirect()->away($frontendUrl.'/email-verified?status=just_verified');

})->middleware(['signed'])->name('tenant.verification.verify');