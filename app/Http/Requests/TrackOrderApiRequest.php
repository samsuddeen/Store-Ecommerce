<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator; 
class TrackOrderApiRequest extends FormRequest
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
            'email'=>'required',
            'refId'=>'required'
        ];


    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            [
                'error'=>true,
                'message'=>'Validation Error',
                'errors'=>$validator->errors()
            ]
        ));
    }
}
