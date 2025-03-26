<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends UpdateProductRequest
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
        $parents = Parent::rules();
        return array_merge($parents, [
            'user_id' => ['required']
        ]);
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'user_id' => auth()->id(),
            // 'images' => explode(',', $this->images),
            'publishStatus' => isset($this->publishStatus)
        ]);
    }
}
