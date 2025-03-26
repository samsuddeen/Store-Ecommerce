<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VatTax extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'created_by',
        'vat_percent',
        'tax_percent',
        'publishStatus',
        'created_year',        
    ];
}
