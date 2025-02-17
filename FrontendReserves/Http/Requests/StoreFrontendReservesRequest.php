<?php

namespace Modules\FrontendReserves\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreFrontendReservesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'car_id' => 'required|integer',
            'service_id' => 'required|integer',
            'type' => 'required|integer',
            'reserve_date' => 'nullable|date',
            'reserve_time' => 'nullable|date_format:H:i:s',
            'passenger_name' => 'nullable|string|max:255',
            'passenger_tel' => 'nullable|string|max:255',
            'passenger_qty' => 'required|integer',
            'luggage_qty' => 'nullable|integer',
            'start_location' => 'nullable|string|max:255',
            'end_location' => 'nullable|string|max:255',
            'flight_info' => 'nullable|string|max:255',
            'status' => 'required|integer',
            'remark' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            // 'car_id.required' => 'Car ID 欄位是必填的',
            // 'car_id.integer' => 'Car ID 欄位必須是整數',
            'service_id.required' => 'Service ID 欄位是必填的',
            'service_id.integer' => 'Service ID 欄位必須是整數',
            'type.required' => '預約車型 欄位是必填的',
            'type.integer' => '預約車型 欄位必須是整數',
            'reserve_date.date' => '預約日期欄位必須是日期',
            'reserve_time.date_format' => '預約時間欄位格式必須是 HH:MM:SS',
            'passenger_name.string' => '乘客姓名欄位必須是字串',
            'passenger_tel.string' => '乘客電話欄位必須是字串',
            'passenger_qty.required' => '搭乘人數欄位是必填的',
            'passenger_qty.integer' => '搭乘人數欄位必須是整數',
            'luggage_qty.integer' => '行李件數欄位必須是整數',
            'start_location.string' => '上車地點欄位必須是字串',
            'end_location.string' => '下車地點欄位必須是字串',
            'flight_info.string' => '航班資訊欄位必須是字串',
            'status.required' => '預約狀態 欄位是必填的',
            'status.string' => '預約狀態 欄位必須是整數',
            'remark.string' => 'Remark 欄位必須是字串',
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
