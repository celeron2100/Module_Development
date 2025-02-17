<?php

namespace Modules\FrontendAuth\Tests\Feature\Auth\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginTest extends TestCase
{
    use RefreshDatabase; // 使用 RefreshDatabase 清空資料庫

    public function test_login()
    {
        // 創建一個使用者
        $user = User::factory()->create([
            'email' => 'test01@gmail.com',
            'password' => Hash::make('password'),
            'name' => 'Test User',
        ]);

        // 登入
        $response = $this->post('/api/frontend/auth/login', [
            'email' => 'test01@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200); // 確認回應狀態碼

        // 檢查返回的 JSON 結構是否包含預期的欄位
        $response->assertJsonStructure([
            'result',
            'access_token',
            'message',
        ]);
    }
}
