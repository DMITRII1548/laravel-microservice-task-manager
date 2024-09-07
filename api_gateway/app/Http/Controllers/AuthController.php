<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /** register new account */
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|min:4',
            'email'    => 'required|email',
            'password' => 'required|min:8',
        ]);

        $dt        = Carbon::now();
        $join_date = $dt->toDayDateTimeString();

        $user = new User();
        $user->name         = $request->name ;
        $user->email        = $request->email;
        $user->password     = Hash::make($request->password);
        $user->save();

        $data = [];
        $data['response_code']  = '200';
        $data['status']         = 'success';
        $data['message']        = 'success Register';
        return response()->json($data);
    }

    /**
     * Login Req
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        try {

            $email     = $request->email;
            $password  = $request->password;

            if (Auth::attempt(['email' => $email,'password' => $password]))
            {
                $user = Auth::user();
                $accessToken = $user->createToken($user->email)->accessToken;

                $data = [];
                $data['response_code']  = '200';
                $data['status']         = 'success';
                $data['message']        = 'success Login';
                $data['user_infor']     = $user;
                $data['token']          = $accessToken;
                return response()->json($data);
            } else {
                $data = [];
                $data['response_code']  = '401';
                $data['status']         = 'error';
                $data['message']        = 'Unauthorised';
                return response()->json($data);
            }
        } catch(\Exception $e) {
            Log::info($e->getMessage());
            $data = [];
            $data['response_code']  = '401';
            $data['status']         = 'error';
            $data['message']        = 'fail Login';
            return response()->json($data);
        }
    }
}
