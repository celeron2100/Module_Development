<?php

namespace Modules\FrontendBusDriver\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Support\RefreshFlow;

class DeleteOneFrontendBusDriverTest extends TestCase
{

    use RefreshDatabase; // 使用 RefreshDatabase 清空資料庫
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testDeleteOneFrontendBusDriver()
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

        // 新增一個司機
        $response = $this->withToken($access_token)->post('/api/frontend/bus-driver/store', [
            "user_id" => "1",
            "driver_license_front" => "data:image/jpeg;base64," . base64_encode($this->createTestImage()),
            "driver_license_back" => "data:image/jpeg;base64," . base64_encode($this->createTestImage()),
            "firm" => "日產",
            "name" => "喪彪",
            "tel" => "0987654321",
            "default_car" => "NJM-1234",
            "license_date" => "2025-01-12",
            "license_type" => "換發",
            "pcrc_license" => 1,
            "status" => 1,
            "remark" => "無",
        ]);

        // 取得單一前台司機，並設定 Bearer Token
        $response = $this->withToken($access_token)->get('/api/frontend/bus-driver/1');

        // 驗證是否成功，回傳 200
        $response->assertStatus(200);

        // var_dump($response);die();

        // 檢查返回的 JSON 結構是否包含預期的欄位
        $response->assertJsonStructure([
            'result',
            'message',
            'data' => [
                'id',
                'name',
                'email',
                'email_verified_at',
                'phone',
                'line',
                'user_type',
                'created_at',
                'updated_at',
                'driver' => [
                    'id',
                    'user_id',
                    'driver_license_front',
                    'driver_license_back',
                    'firm',
                    'name',
                    'tel',
                    'default_car',
                    'license_date',
                    'license_type',
                    'pcrc_license',
                    'status',
                    'remark',
                    'created_at',
                    'updated_at',
                ]
            ]
        ]);

        // 刪除單一前台司機，並設定 Bearer Token
        $response = $this->withToken($access_token)->delete('/api/frontend/bus-driver/1');

        // 驗證是否成功，回傳 200
        $response->assertStatus(200);

        // 檢查返回的 JSON 結構是否包含預期的欄位
        $response->assertJsonStructure([
            'result',
            'message',
            'data',
        ]);
    }

    // 生成測試用的圖片
    public function createTestImage()
    {
        $image = imagecreatetruecolor(100, 100);
        imagefill($image, 0, 0, imagecolorallocate($image, 255, 255, 255));
        ob_start();
        imagejpeg($image);
        $image_data = ob_get_contents();
        ob_end_clean();
        return $image_data;
    }
}
