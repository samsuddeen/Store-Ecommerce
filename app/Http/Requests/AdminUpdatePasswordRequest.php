<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
class AdminUpdatePasswordRequest extends FormRequest
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
            'current_password'=>'required',
            'password'=>'required|confirmed|min:8',
        ];
    }

    public function failedValidation(Validator $validate)
    {
        throw new HttpResponseException(response()->json([
            'validation'=>true,
            'data'=>'Validation Error',
            'msg'=>$validate->errors()
        ]));
    }
}
