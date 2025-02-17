<?php 

namespace Modules\FrontendExportPDF\Services;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use TCPDF;  // 載入 TCPDF 套件
use Illuminate\Support\Facades\Log;  // 載入 Log 類別

class FrontendExportPDFService
{
    public function exportPdf( $request)
    {
        try {
            $img_data = $request['img_data']; // 取得圖片資料

            // 檢查圖片資料是否包含 "data:image/{格式};base64," 前置字串
            if (preg_match('/^data:image\/(\w+);base64,/', $img_data, $type)) {
                // 取得圖片格式(例如 png 或 jpg)，轉為小寫
                $image_format = strtolower($type[1]);  // 取得圖片格式
                // 移除前置字串，取得純 Base64 資料
                $img_data = substr($img_data, strpos($img_data, ',') + 1);
                // 解碼 Base64 字串轉換為二進位資料
                $img_data = base64_decode($img_data);
                // 若解碼失敗則拋出例外
                if ($img_data === false) {
                    throw new \Exception('Base64 解碼失敗');
                }
            } else {
                // 若資料格式不正確則拋出例外
                throw new \Exception('無效的圖片資料格式');
            }

            // 建立圖片資源(GD 圖片資源)，用於取得原始圖片尺寸(像素)
            $img_resource = imagecreatefromstring($img_data);
            if (!$img_resource) {
                throw new \Exception('建立圖片資源失敗');
            }
            // 取得原始圖片寬度(像素)
            $orig_width = imagesx($img_resource);
            // 取得原始圖片高度(像素)
            $orig_height = imagesy($img_resource);

            // 建立 TCPDF 物件
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

            // 設定 PDF 文件資訊
            $pdf->SetCreator(PDF_CREATOR); // 設定建立者
            $pdf->SetAuthor('您的網站'); // 設定作者
            $pdf->SetTitle('匯出 PDF'); // 設定標題
            $pdf->SetSubject('PDF 匯出'); // 設定主題
            $pdf->SetKeywords('PDF, 匯出, TCPDF'); // 設定關鍵字

            // 關閉頁首與頁尾的輸出，避免干擾全頁內容
            $pdf->setPrintHeader(false); // 關閉頁首輸出
            $pdf->setPrintFooter(false); // 關閉頁尾輸出

            // 將邊界設定為 0，使圖片能夠從邊緣開始
            $pdf->SetMargins(0, 0, 0); // 設定左右與上邊界均為 0
            // 關閉自動分頁，並設定下邊界為 0
            $pdf->SetAutoPageBreak(false, 0); // 關閉自動分頁

            // 新增第一頁 PDF(A4 紙)
            $pdf->AddPage();

            // 取得當前 PDF 頁面寬度(單位：mm)
            $page_width = $pdf->getPageWidth(); // 取得頁面寬度(mm)
            // 取得當前 PDF 頁面高度(單位：mm)
            $page_height = $pdf->getPageHeight(); // 取得頁面高度(mm)

            // 計算縮放比例：以原始圖片寬度填滿 PDF 頁面寬度
            // 此比例為：每個像素轉換為多少 mm(單位：mm/像素)
            $scale_factor = $page_width / $orig_width;

            // 計算每一頁在原始圖片中所對應的高度(像素)
            // 例如：PDF 頁面高度除以縮放比例，即為該頁在原始圖片中的像素高度
            $page_crop_height_px = $page_height / $scale_factor;

            // 計算總頁數：若原始圖片高度超過一頁則需要換頁
            $page_count = ceil($orig_height / $page_crop_height_px);

            // 逐頁處理：依據原始圖片切割成多個區段並逐頁輸出
            for ($page_index = 0; $page_index < $page_count; $page_index++) {
                // 若非第一頁，則新增 PDF 頁面
                if ($page_index > 0) {
                    $pdf->AddPage();
                }

                // 計算當前頁面在原始圖片中的起始 Y 座標(像素)
                $crop_y = $page_index * $page_crop_height_px;
                // 計算本頁裁切的高度(像素)，最後一頁可能不足一頁高度
                $crop_height = min($page_crop_height_px, $orig_height - $crop_y);

                // 建立一個新的真彩色圖片資源，用於存放裁切後的區段(尺寸：原始圖片寬度 x 裁切高度)
                $cropped_img = imagecreatetruecolor($orig_width, $crop_height);
                if (!$cropped_img) {
                    throw new \Exception('建立裁切圖片資源失敗');
                }

                // 若原始圖片為 PNG 等支援透明的格式，保留透明度設定
                imagealphablending($cropped_img, false);
                imagesavealpha($cropped_img, true);

                // 將原始圖片中指定區域(裁切範圍)複製到新的裁切圖片資源中
                $copy_result = imagecopy(
                    $cropped_img, // 目標圖片
                    $img_resource, // 原始圖片
                    0, // 目標圖片起始 X 座標
                    0, // 目標圖片起始 Y 座標
                    0, // 原始圖片起始 X 座標
                    $crop_y, // 原始圖片起始 Y 座標(依據換頁計算)
                    $orig_width, // 複製寬度(整個圖片寬度)
                    $crop_height // 複製高度(本頁裁切高度)
                );
                if (!$copy_result) {
                    throw new \Exception('裁切圖片失敗');
                }

                // 將裁切後的圖片輸出到記憶體中(透過 output buffering)以取得圖片二進位資料
                ob_start(); // 開始輸出緩衝區
                $output_result = imagepng($cropped_img); // 將裁切圖片以 PNG 格式輸出
                $cropped_img_data = ob_get_clean(); // 取得輸出資料並清除緩衝區
                if (!$output_result || !$cropped_img_data) {
                    throw new \Exception('取得裁切圖片資料失敗');
                }

                // 釋放裁切圖片資源
                imagedestroy($cropped_img);

                // 將裁切後的圖片加入到當前 PDF 頁面中
                // 設定 x 與 y 為 0，並以整個頁面寬度輸出圖片；高度則由 TCPDF 自動依比例計算
                // 設定 DPI 為 300 以確保高解析度
                $pdf->Image(
                    '@' . $cropped_img_data, // 以 '@' 表示傳入圖片二進位資料
                    0, // x 座標：0 mm
                    0, // y 座標：0 mm
                    $page_width, // 圖片寬度：填滿整個頁面寬度
                    0, // 圖片高度設為 0，讓 TCPDF 自動計算高度以保持比例
                    'PNG', // 圖片格式
                    '', // 連結設定
                    '', // 對齊方式
                    false, // 不自動調整尺寸
                    300, // DPI 設定為 300，確保高解析度
                    '', // 參考尺寸
                    false, // 不加邊框
                    false, // 不啟用調整大小
                    0, // 解析度設定
                    false, // 不隱藏
                    false, // 不使用 fitbox
                    false // 不使用填滿整頁
                );
            }

            // 釋放原始圖片資源
            imagedestroy($img_resource);

            // 將產生的 PDF 輸出為字串，'S' 參數表示將 PDF 儲存為字串
            $pdf_content = $pdf->Output('exported.pdf', 'S');
            // 將 PDF 檔案內容轉換為 Base64 字串，以便前端接收並解碼
            $pdf_base64 = base64_encode($pdf_content);

            return $pdf_base64;
        } catch (\Exception $e) {
            // 捕捉例外並將錯誤訊息記錄到 Log
            return false;
        }
    }
}