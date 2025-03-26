<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SMTPStoreRequest extends FormRequest
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
            'mail_user_name'=>'required|unique:s_m_t_p_s',
            'mail_driver'=>'nullable',
            'mail_host'=>'nullable',
            'mail_port'=>'nullable',
            'mail_password'=>'required',
            'mail_from_address'=>'nullable',
            'mail_from_name'=>'nullable',
            'mail_encryption'=>'nullable',
        ];
    }
}
