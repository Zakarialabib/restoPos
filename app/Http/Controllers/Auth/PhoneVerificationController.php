<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PhoneVerificationController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
        ]);
        // Generate email and password
        $email = mb_strtolower(str_replace(' ', '', $request->input('full_name'))) . '@restopos.com';
        $password = bin2hex(random_bytes(4)); // Generate a random 8-character password

        $user = User::create([
            'name' => $request->input('full_name'),
            'email' => $email,
            'phone' => $request->input('phone'),
            'password' => Hash::make($password),
        ]);

        Auth::login($user);

        return response()->json([
            'message' => __('Registration successful'),
            'user' => $user,
        ]);
    }
}
