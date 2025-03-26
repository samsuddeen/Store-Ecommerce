<?php
namespace App\Traits;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;
trait SellerReviewTrait{

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json(
            [
                'validate'=>true,
                'msg'=>$validator->errors()
            ]
        ));
        
    }
}