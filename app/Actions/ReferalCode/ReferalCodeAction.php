<?php
namespace App\Actions\ReferalCode;

use App\Models\New_Customer;
use App\Models\RewardSection;
use App\Models\UserShareList;

class ReferalCodeAction{

    protected $new_customer=null;
    protected $referal_code=null;
    public function __construct(New_Customer $new_customer,$referal_code)
    {
        $this->new_customer=$new_customer;
        $this->referal_code=$referal_code;
    }

    public function storePoints()
    {
        $share_point=RewardSection::first();
        $data=[
            'share_from'=>$this->getCustomer(),
            'share_to'=>$this->new_customer->id,
            'points'=>$share_point->points,
            'referal_code'=>$this->referal_code
        ];
        UserShareList::insert($data);
    }

    public function getCustomer()
    {
        $customer=New_Customer::where('referal_code',$this->referal_code)->firstOrFail();
        return $customer->id;
    }
}