<?php

namespace Modules\FrontendAuth\Tests\Feature\Auth\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterTest extends TestCase
{
    use RefreshDatabase; // 使用 RefreshDatabase 清空資料庫

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_register()
    {
        // 準備測試用的使用者資料
        $user_data = [
            'email' => 'test02@gmail.com', // 設定測試使用的電子郵件
            'password' => 'Password123', // 設定測試使用的密碼
            'name' => 'Test User 2', // 設定測試使用的名稱
        ];

        // 呼叫註冊 API，傳送 POST 請求至 /api/frontend/auth/register
        $response = $this->post('/api/frontend/auth/register', $user_data);

        // 確認 API 回應的狀態碼是否為 200
        $response->assertStatus(200);

        // 檢查資料庫中是否有剛剛註冊的使用者資料
        $this->assertDatabaseHas('users', [
            'email' => 'test02@gmail.com', // 根據 email 欄位檢查資料是否存在
        ]);

        // 檢查返回的 JSON 結構是否包含預期的欄位
        $response->assertJsonStructure([
            'result',
            'access_token',
            'data' => [
                'id',
                'name',
                'email',
                'created_at',
                'updated_at',
            ],
            'message',
        ]);

        // 從資料庫中撈取剛註冊的使用者資料
        $user = User::where('email', $user_data['email'])->first(); // 根據 email 查詢使用者

        // 驗證密碼是否正確被 Hash，使用 Laravel 提供的 Hash::check 方法
        $this->assertTrue(
            Hash::check($user_data['password'], $user->password), // 檢查輸入的密碼與資料庫中 Hash 過的密碼是否相符
            '密碼比對失敗' // 若不相符，顯示錯誤訊息
        );
    }

    // 測試 Email 重複註冊
    public function test_register_duplicateEmail()
    {
        // 第一個測試用的使用者資料，先建立一個使用者
        $user_data = [
            'email' => 'test02@gmail.com', // 設定測試用的電子郵件
            'password' => 'Password123', // 設定測試用的密碼
            'name' => 'Test User 2', // 設定測試用的名稱
        ];

        // 呼叫註冊 API，傳送 POST 請求至 /api/frontend/auth/register 以建立第一個使用者
        $this->post('/api/frontend/auth/register', $user_data)->assertStatus(200);

        // 重複使用相同 Email 的使用者資料
        $duplicate_user_data = [
            'email' => 'test02@gmail.com', // 與第一筆資料相同的電子郵件
            'password' => 'Password123', // 設定測試用的密碼
            'name' => 'Test User Duplicate', // 測試用名稱不同
        ];

        // 呼叫註冊 API，傳送 POST 請求以嘗試建立重複的使用者
        $response = $this->post('/api/frontend/auth/register', $duplicate_user_data);

        // 依據 API 設計，若 Email 重複可能仍回傳 200，但 result 值為 -1 表示失敗
        $response->assertStatus(200); // 若 API 回傳其他狀態碼，如 422，請修改此處

        // var_dump($response);die();

        // 檢查返回的 JSON 結構是否包含預期的欄位：result 與 message
        $response->assertJsonStructure([
            'result', // 結果欄位
            'message', // 訊息欄位
        ]);
    }
}
