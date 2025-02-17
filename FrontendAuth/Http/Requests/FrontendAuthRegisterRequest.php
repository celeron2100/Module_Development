<?php

namespace Modules\FrontendAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FrontendAuthRegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|max:255',
        ];
    }

    // 自訂錯誤訊息
    public function messages()
    {
        return [
            'name.required' => '姓名欄位是必填的',
            'name.string' => '姓名欄位必須是字串',
            'name.max' => '姓名欄位最多 255 個字元',
            'email.required' => 'Email 欄位是必填的',
            'email.string' => 'Email 欄位必須是字串',
            'email.email' => 'Email 格式不正確',
            'email.max' => 'Email 欄位最多 255 個字元',
            'email.unique' => 'Email 已經被註冊過了',
            'password.required' => '密碼欄位是必填的',
            'password.string' => '密碼欄位必須是字串',
            'password.min' => '密碼欄位最少 8 個字元',
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
