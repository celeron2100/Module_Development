<?php 

namespace Modules\FrontendUser\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash; // 引入 Hash

class FrontendUserService
{
    // 取得所有使用者資料
    public function frontendUserIndex()
    {
        $users = User::paginate(20); // 取得所有使用者資料

        return $users; // 返回使用者資料
    }

    // 顯示單一使用者資料
    public function frontendUserShow($id)
    {
        $user = User::find($id); // 根據ID查找使用者

        return $user; // 返回使用者資料
    }

    // 更新單一使用者資料
    public function frontendUserUpdate($request, $id)
    {
        
        $password = $request->input('password'); // 從請求中獲取密碼
        $name = $request->input('name'); // 從請求中獲取姓名
        $phone = $request->input('phone'); // 從請求中獲取電話號碼
        $line = $request->input('line'); // 從請求中獲取Line帳號
        $user_type = $request->input('user_type'); // 從請求中獲取使用者類型

        $user = User::find($id); // 根據ID查找使用者

        // 如果找不到使用者，返回false
        if(!$user){
            return false;
        }
        
        // 更新使用者資料到資料庫
        $user->password = Hash::make($password); // 更新密碼（使用Hash加密）
        $user->name = $name; // 更新姓名
        $user->phone = $phone; // 更新電話號碼
        $user->line = $line; // 更新Line帳號
        $user->user_type = $user_type; // 更新使用者類型
        
        $user->save(); // 儲存更新的使用者資料

        // 返回更新後的使用者資料
        return $user;
    }

    // public function frontendUserStore($request)
    // {
    //     return 'frontendUserStore';
    // }

    public function frontendUserDestroy($id)
    {
        $user = User::find($id); // 根據ID查找使用者

        // 如果找不到使用者，返回false
        if(!$user){
            return false;
        }

        $user->delete(); // 刪除使用者

        // 返回true，表示刪除成功
        return true;
    }
}