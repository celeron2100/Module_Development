<?php

namespace Modules\FrontendUser\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateFrontendUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => 'required|string|min:8|max:255',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'line' => 'required|string|max:255',
            'user_type' => 'required|integer|in:1,2,3,4',
        ];
    }

    public function messages()
    {
        return [
            'password.required' => '密碼欄位是必填的',
            'password.string' => '密碼欄位必須是字串',
            'password.min' => '密碼欄位最少 8 個字元',
            'name.required' => '姓名欄位是必填的',
            'name.string' => '姓名欄位必須是字串',
            'name.max' => '姓名欄位最多 255 個字元',
            'phone.required' => '行動電話欄位是必填的',
            'phone.string' => '行動電話欄位必須是字串',
            'phone.max' => '行動電話欄位最多 255 個字元',
            'line.required' => 'Line 欄位是必填的',
            'line.string' => 'Line 欄位必須是字串',
            'line.max' => 'Line 欄位最多 255 個字元',
            'user_type.required' => '帳號類型欄位是必填的',
            'user_type.integer' => '帳號類型欄位必須是整數',
            'user_type.max' => '帳號類型欄位必須是 1, 2, 3, 4',
        ];
    }

    // 驗證失敗回應
    protected function failedValidation(Validator $validator)
    {
        // 建立自訂錯誤回應資料，包含狀態、訊息及詳細錯誤資訊
        $error_response = [
            'result' => -1, // 錯誤狀態
            'message' => $validator->errors()->first(), // 第一筆錯誤訊息字串
        ];

        // 拋出 HttpResponseException，回傳 JSON 格式錯誤回應
        throw new HttpResponseException(response()->json($error_response));
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
