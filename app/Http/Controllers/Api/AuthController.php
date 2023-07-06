<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\LoginRequest;
use App\Http\Requests\Api\User\SignUpRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        try {
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return apiResponse(false, __('User not found.'), [], 404);
            }
            if ($user->email_verified_at == null) {
                return apiResponse(false, __('Please verify your account', [], 401));
            }
            if ($user->status == User::INACTIVE) {
                return apiResponse(false, __('Your account is deactivated', [], 401));
            }
            if (!Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                return apiResponse(false, __("Invalid Credentials"), [], 401);
            }
            $token = $user->createToken($user->name ?? "smallWorld")->plainTextToken;
            $data = ['user' => $user, 'token' => $token];
            return apiResponse(true, __('Logged in successfully'), $data);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage(), [], 500);
        }
    }

    public function signUp(SignUpRequest $request)
    {
        try {
            DB::beginTransaction();
            $user           = new User();
            $user->name     = $request->name;
            $user->email    = $request->email;
            $user->password = Hash::make($request->password);
            $user->markEmailAsVerified(true);
            $user->save();
            DB::commit();
            $token = $user->createToken($user->name ?? "smallWorld")->plainTextToken;
            $data = ['user' => $user, 'token' => $token];
            return apiResponse(true, __('Logged in successfully'), $data);
        } catch (Exception $e) {
            return apiResponse(false, $e->getMessage(), [], 500);
        }
    }
}
