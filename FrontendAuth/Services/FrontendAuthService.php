<?php

namespace Modules\FrontendAuth\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // 引入 Hash
use App\Models\User; //引入User Model
use Tymon\JWTAuth\Facades\JWTAuth; // 引入 JWTAuth 產生 JWT token

// 用於 User 認證的 Service
class FrontendAuthService
{

    // User 登入
    public function login($input_data)
    {
        // 嘗試登入
        $token = auth('api')->attempt(['email' => $input_data->email, 'password' => $input_data->password]);

        if ($token) {
            return $token; // 登入成功回傳 token
        } else {
            return false; // 登入失敗回傳 false
        }
    }

    // User 登出
    public function logout(Request $request)
    {
        $token = $request->bearerToken(); // 取得 token
        JWTAuth::setToken($token)->invalidate(); // 使 token 失效
        return true;
    }

    // 取得 User 資料
    public function me(Request $request)
    {

        // 透過認證的 token 取得 User
        $user = JWTAuth::parseToken()->authenticate();

        return $user;
    }

    // User 註冊
    public function register($input_data)
    {
        // 建立使用者
        $user = User::create([
            'name' => $input_data->name,
            'email' => $input_data->email,
            'password' => Hash::make($input_data->password),
            'user_type' => 4, // 預設為客戶
        ]);

        $token = auth('api')->login($user); // 取得 JWT token

        // 註冊成功回傳 token
        return array($token, $user);
    }
}
