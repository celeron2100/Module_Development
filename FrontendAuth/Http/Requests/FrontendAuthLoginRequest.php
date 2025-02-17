<?php

namespace Modules\FrontendAuth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FrontendAuthLoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|max:255',
            'password' => 'required|string|max:255',
        ];
    }

    // 自訂錯誤訊息
    public function messages()
    {
        return [
            'email.required' => 'Email 欄位是必填的',
            'email.email' => 'Email 格式不正確',
            'password.required' => '密碼欄位是必填的',
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
