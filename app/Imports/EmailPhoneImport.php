<?php

namespace App\Imports;

use App\Models\ImportEmailPhone;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmailPhoneImport implements ToModel ,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ImportEmailPhone([
            'phone' => $row['phone'],
            'email' => $row['email'],
        ]);
    }
}
