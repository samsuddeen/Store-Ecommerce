<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\SellerReviewTrait;
class SellerReviewRequest extends FormRequest
{
    use SellerReviewTrait;
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
            'review_id'=>'required|exists:reviews,id',
            'message'=>'required|string'
        ];
    }

   
}
