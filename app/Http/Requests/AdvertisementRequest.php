<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdvertisementRequest extends FormRequest
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
            'title' => ['required', 'max:191'],
            'image' => ['required'],
            'mobile_image' => ['required'],
            'url' => ['nullable', 'url'],
            'size' => ['nullable'],
            'positions' => ['nullable', 'array'],
            'positions.*' => ['nullable', 'exists:positions,id'],
            'status'=>'required|in:active,inactive',
            'ad_type'=>'required',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge(
            [
                'image' => $this->image ?? 'https://www.elegantthemes.com/blog/wp-content/uploads/2021/02/facebook-paid-advertising-featured-image.jpg',
                'mobile_image'=>$this->mobile_image ?? $this->image
            ]
        );
    }
}
