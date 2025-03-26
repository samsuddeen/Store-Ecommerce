<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PushNotificationRequest extends FormRequest
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
            //
            'title' => "required",
            'for'=>'required',
            'for'=>Rule::in([1,2,3,7,8]),
            'summary'=>'required',
            'description'=>'nullable',
            'status'=>'required',
            
        ];
    }

    public function messages()
    {
        return [
            "for.required" => "Please select Users.",
        ];
    }
}
