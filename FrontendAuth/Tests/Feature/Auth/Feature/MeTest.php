<?php

namespace Modules\FrontendAuth\Tests\Feature\Auth\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MeTest extends TestCase
{
    use RefreshDatabase; // 使用 RefreshDatabase 清空資料庫
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_me()
    {

         // 準備測試用的使用者資料
         $user_data = [
            'email' => 'test01@gmail.com', // 設定測試使用的電子郵件
            'password' => 'Password123', // 設定測試使用的密碼
            'name' => 'Test User', // 設定測試使用的名稱
        ];

        // 呼叫註冊 API，傳送 POST 請求至 /api/auth/register
        $response = $this->post('/api/frontend/auth/register', $user_data);

        // 確認 API 回應的狀態碼是否為 200
        $response->assertStatus(200);

        // 抓取註冊時的 token
        $token = $response['access_token'];

        // 呼叫 me API，傳送 GET 請求至 /api/auth/me
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)->get('/api/frontend/auth/me');

        // 確認 API 回應的 JSON 結構是否包含預期的欄位
        $response->assertJsonStructure([
            'result',
            'data' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'phone',
                'line',
                'created_at',
                'updated_at',
            ],
            'message',
        ]);

        // 確認 API 回應的狀態碼是否為 200
        $response->assertStatus(200);
    }
}
