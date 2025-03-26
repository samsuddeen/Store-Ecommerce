<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use JetBrains\PhpStorm\ArrayShape;

class CategoryRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'title' => [
                'required',
                Rule::unique('categories', 'title')->ignore($this->category),
            ],
            'status'=>'required|in:0,1',
            'parent_id' => ['nullable', 'exists:categories,id'],
            'image' => ['nullable'],
            'slug' => ['required'],
            'attribute' => ['required', 'array'],
            'helpText' => ['required', 'array'],
            'value' => ['required', 'array'],
            'icon' => ['nullable'],
            'stock' => ['required', 'array'],
            'meta_title' => ['nullable'], 
            'meta_keywords' => ['nullable'],
            'meta_description' => ['nullable'],
            'og_image'=>['nullable'],

        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return void
     */
    public function prepareForValidation(): void
    {
        $this->merge(
            ['slug' => Str::slug($this->title)]
        );
    }
}
