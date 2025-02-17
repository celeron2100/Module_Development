<?php

namespace Modules\FrontendDispatchs\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class GetOneFrontendDispatchsTest extends TestCase
{
    use RefreshDatabase; // 使用 RefreshDatabase 清空資料庫

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testGetOneFrontendDispatchs()
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

        // 新增一個車輛
        $response = $this->withToken($access_token)->post('/api/frontend/car/store', [
            "driver_id" => 1,
            "firm" => "豐田",
            "license_plate" => "ABC-1234",
            "brand" => "欣欣",
            "type" => "大巴",
            "fuel" => "柴油",
            "color" => "黑",
            "factory_date" => "1999-01-01",
            "passenger_insurance" => "A12345",
            "insurance_period" => "2025-01-03"
        ]);

        // 新增一個派遣
        $response = $this->withToken($access_token)->post('/api/frontend/dispatchs/store', [
            "car_id" => 1,
            "no" => "AAA00001",
            "service_id" => 1,
            "dispatch_car_model" => "大巴",
            "dispatch_date" => "2025-02-14",
            "dispatch_time" => "21:11:01",
            "passenger_name" => "大中天",
            "passenger_tel" => "0987654321",
            "passenger_qty" => 1,
            "luggage_qty" => 0,
            "start_location" => "高雄",
            "end_location" => "台北",
            "flight_info" => "無",
            "car_type" => "六人座",
            "uniform_type" => "唐裝",
            "payment_type" => 1,
            "status" => 1,
            "rebate" => 1,
            "receiver" => "司機",
            "remark" => "司機有潔癖"
        ]);

        // 取得單一前台派遣，並設定 Bearer Token
        $response = $this->withToken($access_token)->get('/api/frontend/dispatchs/1');

        // 驗證是否成功，回傳 200
        $response->assertStatus(200);

        // 驗證回傳的 JSON 結構是否包含預期的欄位
        $response->assertJsonStructure([
            'result',
            'message',
            'data' => [
                'id',
                'car_id',
                'no',
                'service_id',
                'dispatch_car_model',
                'dispatch_date',
                'dispatch_time',
                'passenger_name',
                'passenger_tel',
                'passenger_qty',
                'luggage_qty',
                'start_location',
                'end_location',
                'flight_info',
                'car_type',
                'uniform_type',
                'payment_type',
                'status',
                'rebate',
                'receiver',
                'remark',
                'created_at',
                'updated_at'
            ]
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
