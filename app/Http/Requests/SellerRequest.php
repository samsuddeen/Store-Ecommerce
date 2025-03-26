<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SellerRequest extends FormRequest
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
            'name'=>'required',
            'phone'=>'required',
            'email'=>'required|unique:users',
            'address'=>'required',
            'zip'=>'required',
            'province_id'=>'required|exists:provinces,id',
            'district_id'=>'required|exists:districts,id',
        ];
    }
}
