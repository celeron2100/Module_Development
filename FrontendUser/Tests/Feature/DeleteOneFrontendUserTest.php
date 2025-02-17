<?php

namespace Modules\FrontendUser\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DeleteOneFrontendUserTest extends TestCase
{
    use RefreshDatabase; // 使用 RefreshDatabase 清空資料庫
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDeleteOneFrontendUser()
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

        // 取得登入後的 access_token
        $access_token = $response['access_token'];

        // 刪除單一前台使用者，並設定 Bearer Token
        $response = $this->withToken($access_token)->delete('/api/frontend/user/1');

        // 驗證是否成功，回傳 200
        $response->assertStatus(200);

        // 檢查返回的 JSON 結構是否包含預期的欄位
        $response->assertJsonStructure([
            'result',
            'message',
            'data',
        ]);
    }
}
