<?php

namespace Modules\FrontendExportPDF\Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class FrontendExportPDFTest extends TestCase
{
    use RefreshDatabase; // 使用 RefreshDatabase 清空資料庫
    /**
     * 基本功能測試範例，測試登入並生成 PDF
     *
     * @return void
     */
    public function testFrontendExportPDF()
    {

        // 創建一個使用者 
        $user = User::factory()->create([
            'email'    => 'test01@gmail.com', // 使用者信箱
            'password' => Hash::make('password'), // 使用者密碼（加密後）
            'name'     => 'Test User', // 使用者名稱
        ]);

        // 發送登入請求 
        $response = $this->post('/api/frontend/auth/login', [
            'email'    => 'test01@gmail.com', // 輸入的信箱
            'password' => 'password', // 輸入的密碼
        ]);

        // 取得登入後的 access_token 
        $access_token = $response['access_token']; // 防止未定義狀況

        // 生成測試用的圖片
        $image_data = $this->createTestImage();

        // 送出生成 PDF 的請求
        $response = $this->withToken($access_token)->post('/api/frontend/export/pdf', [
            'img_data' => "data:image/jpeg;base64," . base64_encode($image_data), // 圖片資料
        ]);

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
