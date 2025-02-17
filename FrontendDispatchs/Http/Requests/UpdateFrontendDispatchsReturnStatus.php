<?php

namespace Modules\FrontendDispatchs\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateFrontendDispatchsReturnStatus extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            // 'dispatch_id' => 'required|integer',
            'return_status' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            // 'dispatch_id.required' => 'Dispatch ID 欄位是必填的',
            // 'dispatch_id.integer' => 'Dispatch ID 欄位必須是整數',
            'return_status.required' => 'Return Status 欄位是必填的',
            'return_status.integer' => 'Return Status 欄位必須是整數',
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
