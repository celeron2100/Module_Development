<?php

namespace Modules\FrontendUser\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GetAllFrontendUserTest extends TestCase
{
    use RefreshDatabase; // 使用 RefreshDatabase 清空資料庫
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetAllFrontendUser()
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

        // 取的全部前台使用者，並設定 Bearer Token
        $response = $this->withToken($access_token)->get('/api/frontend/user');

        // 驗證是否成功，回傳 200
        $response->assertStatus(200);

        // 檢查返回的 JSON 結構是否包含預期的欄位
        $response->assertJsonStructure([
            'result',
            'message',
            'data' => [
                'current_page',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'email_verified_at',
                        'phone',
                        'line',
                        'user_type',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'first_page_url',
                'from',
                'last_page',
                'last_page_url',
                'links',
                'next_page_url',
                'path',
                'per_page',
                'prev_page_url',
                'to',
                'total'
            ]
        ]);
    }
}
