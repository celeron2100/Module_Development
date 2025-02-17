<?php

namespace Modules\FrontendBusDriver\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateFrontendBusDriverRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer',
            'driver_license_front' => 'required|string|data_uri',
            'driver_license_back' => 'required|string|data_uri',
            'firm' => 'nullable|string',
            'name' => 'nullable|string',
            'tel' => 'nullable|string',
            'default_car' => 'nullable|string',
            'license_date' => 'nullable|date',
            'license_type' => 'nullable|string',
            'pcrc_license' => 'nullable|integer|in:0,1',
            'status' => 'nullable|integer|in:0,1',
            'remark' => 'nullable|string',
        ];
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

    public function messages()
    {
        return [
            'user_id.required' => 'User ID 欄位是必填的',
            'user_id.integer' => 'User ID 欄位必須是整數',
            'driver_license_front.required' => '駕照正面欄位是必填的',
            'driver_license_front.string' => '駕照照面欄位必須是字串',
            'driver_license_back.required' => '駕照背面欄位是必填的',
            'driver_license_back.string' => '駕照背面欄位必須是字串',
            'firm.string' => '車商欄位必須是字串',
            'name.string' => '名稱欄位必須是字串',
            'tel.string' => '行動電話欄位必須是字串',
            'default_car.string' => '預設車輛欄位必須是字串',
            'license_date.date' => '執照效期欄位必須是日期',
            'license_type.string' => '駕照種類欄位必須是字串',
            'pcrc_license.integer' => '良民證欄位必須是整數',
            'pcrc_license.required' => '良民證欄位是必填的',
            'pcrc_license.in' => '良民證欄位必須是 0 或 1',
            'status.integer' => '狀態欄位必須是整數',
            'status.required' => '狀態欄位是必填的',
            'status.in' => '狀態欄位必須是 0 或 1',
            'remark.string' => '備註欄位必須是字串',
        ];
    }


    protected function failedValidation(Validator $validator)
    {
        $error_response = [
            'result' => -1,
            'message' => $validator->errors()->first(),
        ];

        throw new HttpResponseException(response()->json($error_response));
    }
}
