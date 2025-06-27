<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/flutter-redirect', function (Illuminate\Http\Request $request) {
//     $email = $request->query('email');
//     $code = $request->query('code');
//       $link = "gem://reset-password?email={$email}&code={$code}";
//     return response()->json([
//         'message' => 'Redirect to Flutter app',
//         'link' => $link,
//     ]);

//     // Redirect to the Flutter deep link
//     return redirect("gem://reset-password?email={$email}&code={$code}");
// });