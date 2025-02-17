<?php

namespace Modules\FrontendReserves\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GetOneFrontendReservesTest extends TestCase
{
    use RefreshDatabase; // 使用 RefreshDatabase 清空資料庫
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetOneFrontendReserves()
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

        // 新增一個預約
        $response = $this->withToken($access_token)->post('/api/frontend/reserves/store', [
            "service_id" => 1,
            "type" => 1,
            "reserve_date" => "2025-01-02",
            "reserve_time" => "12:01:01",
            "passenger_name" => "小美",
            "passenger_tel" => "0987654321",
            "passenger_qty" => 1,
            "luggage_qty" => 0,
            "start_location" => "高雄",
            "end_location" => "台北",
            "flight_info" => "無",
            "status" => 0,
            "remark" => "客人有潔癖"
        ]);

        // 取得新增的預約
        $response = $this->withToken($access_token)->get('/api/frontend/reserves/1');

        // 驗證是否成功，回傳 200
        $response->assertStatus(200);

        // 驗證回傳的 JSON 結構是否包含預期的欄位
        $response->assertJsonStructure([
            'data' => [
                'id',
                'service_id',
                'type',
                'reserve_date',
                'reserve_time',
                'passenger_name',
                'passenger_tel',
                'passenger_qty',
                'luggage_qty',
                'start_location',
                'end_location',
                'flight_info',
                'status',
                'remark',
                'created_at',
                'updated_at',
            ]
        ]);
    }
}
