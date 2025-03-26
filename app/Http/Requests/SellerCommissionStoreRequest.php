<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellerCommissionStoreRequest extends FormRequest
{
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
            'title'=>'required|string',
            'percent'=>'required|int',
            'category_id*'=>'nullable|exists:categories,id',
            'brand_id*'=>'nullable|exists:brands,id'
        ];
    }
}
