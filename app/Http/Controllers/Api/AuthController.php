<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $user = Auth::user();
        $plainTextToken = bin2hex(random_bytes(20));
        
        DB::table('personal_access_tokens')->insert([
            'tokenable_type' => 'App\Models\User',
            'tokenable_id' => $user->id,
            'name' => 'api-token',
            'token' => hash('sha256', $plainTextToken),
            'abilities' => ['*'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'token' => $plainTextToken,
            'user' => $user,
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $userId = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $plainTextToken = bin2hex(random_bytes(20));
        
        DB::table('personal_access_tokens')->insert([
            'tokenable_type' => 'App\Models\User',
            'tokenable_id' => $userId,
            'name' => 'api-token',
            'token' => hash('sha256', $plainTextToken),
            'abilities' => ['*'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'token' => $plainTextToken,
            'user' => DB::table('users')->find($userId),
        ], 201);
    }

    public function logout(Request $request): JsonResponse
    {
        DB::table('personal_access_tokens')
            ->where('tokenable_id', $request->user()->id)
            ->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }
}

// Note: Full Eloquent models would be in app/Models/
// but this API controller uses DB facade for compatibility