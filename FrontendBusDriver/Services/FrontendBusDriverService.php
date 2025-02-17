<?php

namespace Modules\FrontendBusDriver\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // 引入 Hash
use App\Models\FrontendBusDriver; //引入BusDriver Model
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth; // 引入 JWTAuth 產生 JWT token
use Illuminate\Support\Facades\DB;

class FrontendBusDriverService
{
    public function index()
    {
        // 取的所有的使用者
        $user = User::where('user_type', 3)
            ->with('driver')
            ->paginate(20);

        // 把駕照圖片轉回 Data URI
        foreach ($user as $key => $value) {
            $driver_license_front = $value->driver->driver_license_front;
            $driver_license_back = $value->driver->driver_license_back;

            // 駕照正面圖片
            if ($driver_license_front) {
                $path = storage_path('app/public/user/bus_driver_license/' . $driver_license_front);
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $user[$key]->driver->driver_license_front = $base64;
            }

            // 駕照背面圖片
            if ($driver_license_back) {
                $path = storage_path('app/public/user/bus_driver_license/' . $driver_license_back);
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                $user[$key]->driver->driver_license_back = $base64;
            }
        }

        // 回傳使用者資料，如果為空陣列則回傳空陣列
        return $user;
    }

    // 新增或更新司機資料
    public function store($request)
    {
        // 接收資料
        $user_id = $request->user_id;
        $driver_license_front = $request->driver_license_front;
        $driver_license_back = $request->driver_license_back;
        $firm = $request->firm;
        $name = $request->name;
        $tel = $request->tel;
        $default_car = $request->default_car;
        $license_date = $request->license_date;
        $license_type = $request->license_type;
        $pcrc_license = $request->pcrc_license;
        $status = $request->status;
        $remark = $request->remark;

        // 拆解 driver_license_front Data URI
        list($type, $driver_license_front_data) = explode('base64,', $driver_license_front);
        // list(, $driver_license_front_data) = explode(',', $driver_license_front_data);

        // 拆解 driver_license_back Data URI
        list($type, $driver_license_back_data) = explode('base64,', $driver_license_back);
        // list(, $driver_license_back_data) = explode(',', $driver_license_back_data);

        // 新增資料夾
        $path = storage_path('app/public/user/bus_driver_license/');
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        // 處理駕照正面圖片上傳
        if (strpos($type, 'image/jpeg') !== false || strpos($type, 'image/jpg') !== false) {
            // 取得 Data URI 的 base64 資料
            // 解碼 base64 資料為 binary
            $driver_license_front_data = base64_decode($driver_license_front_data);
            // 檔案名稱
            $driver_license_front_filename = uniqid() . '.jpg';
            // 儲存檔案
            if (!file_put_contents($path . $driver_license_front_filename, $driver_license_front_data)) {
                return response()->json([
                    'result' => -1,
                    'message' => '檔案儲存失敗'
                ]);
            }
        } elseif (strpos($type, 'image/png') !== false) {
            // 取得 Data URI 的 base64 資料
            // 解碼 base64 資料為 binary
            $driver_license_front_data = base64_decode($driver_license_front_data);
            // 檔案名稱
            $driver_license_front_filename = uniqid() . '.png';
            // 儲存檔案
            if (!file_put_contents($path . $driver_license_front_filename, $driver_license_front_data)) {
                return response()->json([
                    'result' => -1,
                    'message' => '檔案儲存失敗'
                ]);
            }
        } else {
            return response()->json([
                'result' => -1,
                'message' => '不支援的檔案格式'
            ]);
        }

        // 處理駕照背面圖片上傳
        if (strpos($type, 'image/jpeg') !== false || strpos($type, 'image/jpg') !== false) {
            // 取得 Data URI 的 base64 資料
            // 解碼 base64 資料為 binary
            $driver_license_back_data = base64_decode($driver_license_back_data);
            // 檔案名稱
            $driver_license_back_filename = uniqid() . '.jpg';
            // 儲存檔案
            if (!file_put_contents($path . $driver_license_back_filename, $driver_license_back_data)) {
                return response()->json([
                    'result' => -1,
                    'message' => '檔案儲存失敗'
                ]);
            }
        } elseif (strpos($type, 'image/png') !== false) {
            // 取得 Data URI 的 base64 資料
            // 解碼 base64 資料為 binary
            $driver_license_back_data = base64_decode($driver_license_back_data);
            // 檔案名稱
            $driver_license_back_filename = uniqid() . '.png';
            // 儲存檔案
            if (!file_put_contents($path . $driver_license_back_filename, $driver_license_back_data)) {
                return false;
            }
        } else {
            return false;
        }

        try {
            // 開始資料庫交易
            DB::beginTransaction();
            // 檢查 user_id 是否存在
            $user = User::find($user_id);
            if (!$user) {
                return false;
            }

            // 查找是否已存在記錄
            $bus_driver = FrontendBusDriver::where('user_id', $user_id)->first();

            // user_type 改為 3，代表身份變為司機
            $user->user_type = 3;
            $user->touch();
            $user->save();

            if ($bus_driver) {
                // 更新現有記錄
                $bus_driver->driver_license_front = $driver_license_front_filename;
                $bus_driver->driver_license_back = $driver_license_back_filename;
                $bus_driver->firm = $firm;
                $bus_driver->name = $name;
                $bus_driver->tel = $tel;
                $bus_driver->default_car = $default_car;
                $bus_driver->license_date = $license_date;
                $bus_driver->license_type = $license_type;
                $bus_driver->pcrc_license = $pcrc_license;
                $bus_driver->status = $status;
                $bus_driver->remark = $remark;
                $bus_driver->touch();
                $bus_driver->save();
            } else {
                // 新增記錄
                $bus_driver = new FrontendBusDriver();
                $bus_driver->user_id = $user_id;
                $bus_driver->driver_license_front = $driver_license_front_filename;
                $bus_driver->driver_license_back = $driver_license_back_filename;
                $bus_driver->firm = $firm;
                $bus_driver->name = $name;
                $bus_driver->tel = $tel;
                $bus_driver->default_car = $default_car;
                $bus_driver->license_date = $license_date;
                $bus_driver->license_type = $license_type;
                $bus_driver->pcrc_license = $pcrc_license;
                $bus_driver->status = $status;
                $bus_driver->remark = $remark;
                $bus_driver->save();
            }
            // 提交資料庫交易
            DB::commit();
        } catch (\Exception $e) {
            // 還原資料庫交易
            DB::rollBack();
            return false;
        }


        return $bus_driver->id;
    }

    // 取得司機資料，是使用司機的 driver_id 來查找
    public function show($driver_id)
    {
        // 取得使用者資料
        $driver = User::where('user_type', 3)
            ->whereHas('driver', function ($query) use ($driver_id) {
                $query->where('id', $driver_id);
            })
            ->with('driver')
            ->first();

        // 檢查是否有使用者資料，如果為空則回傳 false
        if (!$driver) {
            return false;
        }

        //  把駕照圖片轉回 Data URI
        $driver_license_front = $driver->driver->driver_license_front;
        $driver_license_back = $driver->driver->driver_license_back;

        // 駕照正面圖片
        if ($driver_license_front) {
            $path = storage_path('app/public/user/bus_driver_license/' . $driver_license_front);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $driver->driver->driver_license_front = $base64;
        }

        // 駕照背面圖片
        if ($driver_license_back) {
            $path = storage_path('app/public/user/bus_driver_license/' . $driver_license_back);
            $type = pathinfo($path, PATHINFO_EXTENSION);
            $data = file_get_contents($path);
            $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            $driver->driver->driver_license_back = $base64;
        }

        // 回傳使用者資料，如果為空陣列則回傳空陣列
        return $driver;
    }

    // 刪除司機身分，但是不刪除使用者資料，只是將 user_type 改為 4(客戶)
    public function destroy($driver_id)
    {
        try {
            // 開始資料庫交易
            DB::beginTransaction();
            // 以 driver_id 查找司機資料
            $driver = FrontendBusDriver::find($driver_id);

            $user_id = $driver->user_id; // 要用來變更 user_type 的 user_id

            // 檢查是否有司機資料，如果為空則回傳 false
            if (!$driver) {
                return false;
            }

            // var_dump($driver);die();

            // 刪除司機資料
            $driver->delete();

            // 變更 user_type 為 4，代表身份變為客戶
            $user = User::find($user_id);
            // $user->user_type = 4;
            // $user->touch();
            // $user->save();

            // user 也要刪除
            $user->delete();

            // 提交資料庫交易
            DB::commit();

            return true;
        } catch (\Exception $e) {
            // 還原資料庫交易
            DB::rollBack();
            return false;
        }
    }
}
