<?php
namespace App\Data\Policy\ArrayPolicy;

use App\Enum\Policy\PolicyEnum;

class PolicyData
{
    protected $data;
    public function getData()
    {
        $this->initializeData();
        return $this->data;
    }
    private function initializeData()
    {
        $this->data = [
            [
                'title'=>'Web Policy',
                'value'=>PolicyEnum::WEB_POLICY,
            ],
            [
                'title'=>'Return Policy',
                'value'=>PolicyEnum::RETURN_POLICY,
            ],
            [
                'title'=>'Other Policy',
                'value'=>PolicyEnum::OTHER_POLICY,
            ],
        ];
    }
}