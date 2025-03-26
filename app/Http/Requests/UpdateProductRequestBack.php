<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequestBack extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return  $this->user()->can('update', $this->product);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'short_description' => ['required'],
            'price' => ['required'],
            'retailPrice' => ['required'],
            'currency' => ['required', 'max:5'],
            'sku' => ['required'],
            'packSize' => ['required'],
            'packSizeUnit' => ['required'],
            'packPerCarton' => ['required'],
            'keyword' => ['nullable'],
            'length' => ['required'],
            'lengthUnit' => ['required'],
            'width' => ['required'],
            'height' => ['required'],
            'weight' => ['required'],
            'thumbnail' => ['required'],
            'weightUnit' => ['required'],
            'brand_id' => ['required', 'exists:brands,id'],
            'country_id' => ['required', 'exists:countries,id'],
            'category_id' => ['required', 'exists:categories,id'],
            'material' => ['required'],
            'features' => ['required', 'array'],
            'colors' => ['nullable', 'array'],
            'colors.*' => ['required', 'exists:colors,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['required', 'exists:tags,id'],
            'sizes' => ['nullable', 'array'],
            'images' => ['required', 'array'],
            'publishStatus' => ['required'],
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'images' => explode(',', $this->images),
            'publishStatus' => isset($this->publishStatus)
        ]);
    }
}
