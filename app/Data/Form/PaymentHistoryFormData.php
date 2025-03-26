<?php
namespace App\Data\Form;


class PaymentHistoryFormData
{
    function __construct(
        public string $from_model,
        public int $from_id,
        public string $to_model,
        public int $to_id,
        public string $reason_model,
        public int $reason_id,
        public ?string $method_model,
        public ?int $method_id,
        public ?int $method,
        public string $title,
        public ?string $url,
        public ?string $summary,
        public ?bool $is_read,
        public ?bool $is_received,
    )
    {
        
    }
    public function getData()
    {
        return collect($this)->toArray();
    }
}