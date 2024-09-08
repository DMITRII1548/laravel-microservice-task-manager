<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * register new account
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return response()->json([
            'response_code' => '200',
            'status' =>  'success',
            'message' => 'success Register'
        ]);
    }

    /**
     * Login Req
     */
    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        try {
            if (Auth::attempt($data)) {
                $user = Auth::user();
                $accessToken = $user->createToken($user->email)->accessToken;

                return response()->json([
                    'response_code' => '200',
                    'status' => 'success',
                    'message' => 'success Login',
                    'token' => $accessToken,
                ]);
            } else {
                return response()->json([
                    'response_code' => '401',
                    'status' => 'error',
                    'message' => 'Unauthorised',
                ], 401);
            }
        } catch(\Exception $e) {
            Log::info($e->getMessage());

            return response()->json([
                'response_code' => '401',
                'status' => 'error',
                'message' => 'Failed login'
            ], 401);
        }
    }
}
