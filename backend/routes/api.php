<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EventController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ArtifactController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\NewPasswordController;
use App\Http\Controllers\PasswordResetLinkController;
use App\Http\Controllers\Auth\EmailVerificationController;

/*
-------------------------------------------------------------------------------------------------------------------------
User Authentication
-------------------------------------------------------------------------------------------------------------------------
*/
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function()
{
    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/user',[AuthController::class,'user']);
});

// Email Verification

// Route::post('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verifyEmail'])->name('verification.verify');
// Route::post('/email/resend', [EmailVerificationController::class, 'resendVerificationEmail'])->middleware('auth:sanctum');

Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verifyEmail'])
    ->middleware(['signed']) // This is important for URL signature validation
    ->name('verification.verify');

Route::post('/email/resend', [EmailVerificationController::class, 'resendVerificationEmail'])
    ->middleware(['auth:sanctum']);




//Reset Password

Route::post('/forgot-password', [PasswordResetLinkController::class, 'forgotPassword'])->middleware('api');
Route::post('/reset-password', [NewPasswordController::class, 'resetPassword'])->middleware('api')->name('password.reset');


// Route::get('/flutter-redirect', function (Illuminate\Http\Request $request) {
//     $email = $request->query('email');
//     $code = $request->query('code');

//     // Redirect to the Flutter deep link
//     return redirect("gem://reset-password?email={$email}&code={$code}");
// });

/*
-------------------------------------------------------------------------------------------------------------------------
Frontend
-------------------------------------------------------------------------------------------------------------------------
*/

//Artifacts Routes
Route::prefix('artifact')->group(function(){
    Route::get('/all', [ArtifactController::class, 'index'])->name('artifact.index');
    Route::get('/egypt', [ArtifactController::class, 'egyptArtifact'])->name('artifact.egypt');
    Route::get('/outside', [ArtifactController::class, 'outsideArtifact'])->name('artifact.outside');
    Route::post('/store', [ArtifactController::class, 'store'])->name('artifact.store');
    Route::get('/show/{id}', [ArtifactController::class, 'show'])->name('artifact.show');
    Route::post('/update/{id}', [ArtifactController::class, 'update'])->name('artifact.update');
    Route::get('/delete/{id}', [ArtifactController::class, 'destroy'])->name('artifact.delete');
});

//Events Routes
Route::prefix('event')->group(function(){
    Route::get('/all', [EventController::class, 'index'])->name('event.index');
    Route::post('/store', [EventController::class, 'store'])->name('event.store');
    Route::get('/show/{id}', [EventController::class, 'show'])->name('event.show');
    Route::post('/update/{id}', [EventController::class, 'update'])->name('event.update');
    Route::get('/delete/{id}', [EventController::class, 'destroy'])->name('event.delete');
});


//Booking & Payment 
Route::post('/booking', [BookingController::class, 'book']);

// Create payment order and get iframe URL
Route::post('/payment/create-order', [PaymentController::class, 'createOrder']);

// Webhook endpoint to receive Paymob payment status updates
Route::post('/payment/webhook', [PaymentController::class, 'handlePaymobWebhook']);