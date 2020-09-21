<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BahanDapurRequest extends FormRequest
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
            'nama_bahan_dapur'  => 'required|string',
            'kategori_bahan_dapur_id'  => 'required|numeric',
            'is_inventarisasi'  => 'required|numeric',
            'stok'  => 'required|numeric',
            'stok_rendah'  => 'required|numeric',
            'satuan_id'  => 'required|numeric',
            'keterangan'  => 'nullable|string',
            'outlet_id'  => 'nullable|numeric'
        ];
    }
}
