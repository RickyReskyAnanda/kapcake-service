<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SupplierRequest extends FormRequest
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
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:500',
            'nomor_telpon' => 'required|max:20',
            'email' => 'nullable|email|max:100',
            'kode_pos' => 'nullable|string|min:5|max:6',
        ];
    }
}
