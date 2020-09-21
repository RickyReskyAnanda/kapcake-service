<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DiskonRequest extends FormRequest
{
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(
            response()->json([
                'status' => 'warning',
                'message' => $validator->errors()->all(),
            ], 422)
        );
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

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nama_diskon' => 'required|max:50',
            'jumlah' => 'required|numeric',
            'tipe_diskon' => 'required|string',
            'outlet_id' => 'required|numeric',
        ];
    }
}
