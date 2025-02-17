<?php

namespace Modules\FrontendCars\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreFrontendCarsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'driver_id' => 'required|integer',
            'firm' => 'nullable|string|max:255',
            'license_plate' => 'nullable|string|max:255',
            'brand' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'fuel' => 'nullable|string|max:255',
            'color' => 'nullable|string|max:255',
            'factory_date' => 'nullable|date',
            'passenger_insurance' => 'nullable|string|max:255',
            'insurance_period' => 'nullable|date',
        ];
    }

    public function messages()
    {
        return [
            'driver_id.required' => 'Driver ID 欄位是必填的',
            'driver_id.integer' => 'Driver ID 欄位必須是整數',
            'firm.string' => '車商欄位必須是字串',
            'license_plate.string' => '車號欄位必須是字串',
            'brand.string' => '品牌欄位必須是字串',
            'type.string' => '車款欄位必須是字串',
            'fuel.string' => '燃料種類欄位必須是字串',
            'color.string' => '車色欄位必須是字串',
            'factory_date.date' => '出廠年/月欄位必須是日期',
            'passenger_insurance.string' => '乘客險欄位必須是字串',
            'insurance_period.date' => '保險效期欄位必須是日期',
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
