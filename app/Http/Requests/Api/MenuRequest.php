<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class MenuRequest extends FormRequest
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
            'data.nama_menu' => 'required', 
            'data.kategori_menu_id' => 'required', 
            'data.is_tipe_penjualan' => 'required|numeric',
            'data.is_inventarisasi' => 'required|numeric',
            'data.keterangan' => 'nullable', 
            'data.outlet_id' => 'required', 

            'gambar' => 'nullable', 
            'tipe_penjualan' => 'nullable', 
            'variasi' => 'required', 
            'item_tambahan' => 'nullable', 
        ];
    }

}
