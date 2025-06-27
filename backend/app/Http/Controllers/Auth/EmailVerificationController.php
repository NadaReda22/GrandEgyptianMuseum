<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\ModelNotFoundException;


    class EmailVerificationController extends Controller
    {
        public function verifyEmail($user_id, Request $request)
        {
            if (!$request->hasValidSignature()) {
                return response()->json(['message' => 'Invalid or Expired verification request.'], 400);
            }
    
            $user = User::findOrFail($user_id);
    
            if ($user->hasVerifiedEmail()) {
                return response()->json(['message' => 'Email already verified.'], 200);
            }
    
            $user->markEmailAsVerified();
    
           return view('auth.email-verified');
        }




       //Resend Email Verification 

       public function resendVerificationEmail(Request $request)
           
         {
            $user_id = $request->input('user_id');

           
                $user = User::findOrFail($user_id);

                try {
                    $user = User::findOrFail($user_id);
                } catch (ModelNotFoundException $e) {
                    return response()->json(['message' => 'User not found.'], 404);
                }

                if ($user->hasVerifiedEmail()) {
                    return response()->json(['message' => 'Email already verified.'], 200);
                }

                $user->sendEmailVerificationNotification();

                return response()->json(['message' => 'Verification email resent successfully.'], 200);
         }
       



    }
    

