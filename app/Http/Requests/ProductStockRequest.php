<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStockRequest extends FormRequest
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
            'size' => ['required', 'array'],
            'size.*' => ['required', 'exists:product_sizes,id'],
            'color' => ['required', 'array'],
            'color.*' => ['required', 'exists:product_colors,color_id'],
            'stock' => ['required', 'array'],
            'stock.*' => ['required', 'numeric']
        ];
    }
}
