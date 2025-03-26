<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RewardPointTableRequest extends FormRequest
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
            'point'=>'required|int',
            'value'=>'required|int',
            'currency'=>'required|in:nrs,usd',
            'currency_value'=>'required|int',
        ];
    }
}
