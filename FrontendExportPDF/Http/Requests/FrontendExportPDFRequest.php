<?php

namespace Modules\FrontendExportPDF\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FrontendExportPDFRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'img_data' => 'required|string',
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
            'img_data.required' => 'Image Data 欄位是必填的',
            'img_data.string' => 'Image Data 欄位必須是字串',
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
