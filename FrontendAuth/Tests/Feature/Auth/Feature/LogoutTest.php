<?php

namespace Modules\FrontendAuth\Tests\Feature\Auth\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LogoutTest extends TestCase
{

    use RefreshDatabase; // 使用 RefreshDatabase 清空資料庫

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_logout()
    {
        $user = User::factory()->create([
            'email' => 'test03@gmail.com',
            'password' => Hash::make('password'),
            'name' => 'Test User',
        ]);

        // 登入
        $response = $this->post('/api/frontend/auth/login', [
            'email' => 'test03@gmail.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200); // 確認回應狀態碼

        // 取得 access_token
        $token = $response['access_token'];

        // var_dump('$response');
        // var_dump($response);die();

        // 確保 token 存在
        $this->assertNotEmpty($token);

        // 登出
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->post('/api/frontend/auth/logout');

        // 檢查是否成功登出
        $response
            ->assertJson([
                'result' => 0,
                'message' => '登出成功'
            ]);
    }
}
