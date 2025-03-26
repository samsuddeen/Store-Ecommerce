<?php

namespace App\Http\Requests;

use Faker\Provider\Lorem;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => ['required','unique:products,name'],
            'short_description' => ['required'],
            'price' => ['required'],
            'attribute' => ['nullable'],
            'value'=> ['nullable'],
            'image_name'=> ['required'],
            'sellersku' => ['required'],
            'key' => ['nullable'],
            'attributes'=>['nullable'],
            'quantity'=>['required'],
            'category_id' => ['required', 'exists:categories,id'],
            // 'color' => ['nullable', 'array'],
            // 'color.*' => ['nullab', 'exists:colors,id'],
            // 'image_color' => ['nullable', 'array'],
            // 'image_color.*' => ['required', 'exists:colors,id'],
            'publishStatus' => ['required'],
            'meta_title' => ['nullable'],
            'meta_keywords' => ['nullable'],
            'meta_description' => ['nullable'],
            'og_image'=>['nullable'],
            'seller_id'=>['nullable'],
            'product_for'=>'required|in:1,2,3'

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


