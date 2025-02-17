<?php

namespace Modules\FrontendDispatchs\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreFrontendDispatchsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'car_id' => 'required|integer',
            'no' => 'nullable|string|max:255',
            'service_id' => 'nullable|integer',
            'dispatch_car_model' => 'nullable|string|max:255',
            'dispatch_date' => 'nullable|date',
            'dispatch_time' => 'nullable|date_format:H:i:s',
            'passenger_name' => 'nullable|string|max:255',
            'passenger_tel' => 'nullable|string|max:255',
            'passenger_qty' => 'required|integer',
            'luggage_qty' => 'nullable|integer',
            'start_location' => 'nullable|string|max:255',
            'end_location' => 'nullable|string|max:255',
            'flight_info' => 'nullable|string|max:255',
            'car_type' => 'nullable|string|max:255',
            'uniform_type' => 'nullable|string|max:255',
            'payment_type' => 'nullable|integer',
            'status' => 'nullable|integer',
            'rebate' => 'required|integer',
            'receiver' => 'nullable|string|max:255',
            'remark' => 'nullable|string',
            'return_status' => 'nullable|integer',
        ];
    }

    public function messages()
    {
        return [
            'car_id.required' => 'Car ID 欄位是必填的',
            'car_id.integer' => 'Car ID 欄位必須是整數',
            'no.string' => '派遣單號欄位必須是字串',
            'service_id.integer' => '服務類型欄位必須是整數',
            'dispatch_car_model.string' => '派遣車型欄位必須是字串',
            'dispatch_date.date' => '派遣日期欄位必須是日期',
            'dispatch_time.date_format' => '派遣時間欄位必須是時間格式',
            'passenger_name.string' => '乘客姓名欄位必須是字串',
            'passenger_tel.string' => '聯絡電話欄位必須是字串',
            'passenger_qty.required' => '搭乘人數欄位是必填的',
            'passenger_qty.integer' => '搭乘人數欄位必須是整數',
            'luggage_qty.integer' => '行李數量欄位必須是整數',
            'start_location.string' => '上車地點欄位必須是字串',
            'end_location.string' => '下車地點欄位必須是字串',
            'flight_info.string' => '航班資訊欄位必須是字串',
            'car_type.string' => '指定車款欄位必須是字串',
            'uniform_type.string' => '指定服裝欄位必須是字串',
            'payment_type.integer' => '付款方式欄位必須是整數',
            'status.integer' => '狀態欄位必須是整數',
            'rebate.required' => '回金欄位是必填的',
            'rebate.integer' => '回金欄位必須是整數',
            'receiver.string' => '收款對象欄位必須是字串',
            'remark.string' => '備註欄位必須是字串',
            'return_status.integer' => '回報狀態欄位必須是整數',
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
