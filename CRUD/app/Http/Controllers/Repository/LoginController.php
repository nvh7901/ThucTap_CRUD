<?php

namespace App\Http\Controllers\Repository;

use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use App\Helpers\PublicHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $params = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($params)) {
            $user = User::where('email', $request->email)->first();

            $publicHelper = new PublicHelper;
            $token = $publicHelper->encodeJWT($user);

            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công',
                'data' => [
                    'user' => [
                        'name' => $user->name,
                        'email' => $user->email,
                    ],
                    'token' => $token,
                ],
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Email hoặc Password sai',
        ], 400);
        
    }

    public function register(Request $request)
    {
        $params = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ];

        $data = User::create($params);

        if ($data) {
            return response()->json([
                'success' => true,
                'message' => 'Đăng ký thành công',
                'data' => $data,
            ], 201);
        }
        return response()->json([
            'success' => false,
            'message' => 'Đăng ký không thành công',
        ], 400);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json([
            'success' => true,
            'message' => 'Đăng xuất thành công',
        ], 200);
    }
}
