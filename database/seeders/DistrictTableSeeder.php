<?php

namespace Database\Seeders;

use App\Models\Admin\District;
use Illuminate\Database\Seeder;

class DistrictTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $tbl_district =   [
            [
                "id" => "1",
                "dist_id" => "9",
                "np_name" => "Sunsari",
                "province" => "1",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "2",
                "dist_id" => "10",
                "np_name" => "Morang",
                "province" => "1",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "3",
                "dist_id" => "1",
                "np_name" => "Taplejung",
                "province" => "1",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "4",
                "dist_id" => "2",
                "np_name" => "Panchthar",
                "province" => "1",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "5",
                "dist_id" => "3",
                "np_name" => "Ilam",
                "province" => "1",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "6",
                "dist_id" => "4",
                "np_name" => "Jhapa",
                "province" => "1",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "7",
                "dist_id" => "5",
                "np_name" => "Sankhuwasabha",
                "province" => "1",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "8",
                "dist_id" => "6",
                "np_name" => "Tehrathum",
                "province" => "1",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "9",
                "dist_id" => "7",
                "np_name" => "Bhojpur",
                "province" => "1",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "10",
                "dist_id" => "8",
                "np_name" => "Dhankuta",
                "province" => "1",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "11",
                "dist_id" => "11",
                "np_name" => "Solukhumbu",
                "province" => "1",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "12",
                "dist_id" => "12",
                "np_name" => "Khotang",
                "province" => "1",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "13",
                "dist_id" => "13",
                "np_name" => "Udayapur",
                "province" => "1",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "14",
                "dist_id" => "14",
                "np_name" => "Okhaldhunga",
                "province" => "1",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "15",
                "dist_id" => "17",
                "np_name" => "Dhanusha",
                "province" => "2",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "16",
                "dist_id" => "33",
                "np_name" => "Bara",
                "province" => "2",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "17",
                "dist_id" => "34",
                "np_name" => "Parsa",
                "province" => "2",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "18",
                "dist_id" => "15",
                "np_name" => "Saptari",
                "province" => "2",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "19",
                "dist_id" => "16",
                "np_name" => "Siraha",
                "province" => "2",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "20",
                "dist_id" => "18",
                "np_name" => "Mahottari",
                "province" => "2",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "21",
                "dist_id" => "19",
                "np_name" => "Sarlahi",
                "province" => "2",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "22",
                "dist_id" => "32",
                "np_name" => "Rautahat",
                "province" => "2",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "23",
                "dist_id" => "27",
                "np_name" => "Kathmandu",
                "province" => "3",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "24",
                "dist_id" => "28",
                "np_name" => "Lalitpur",
                "province" => "3",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "25",
                "dist_id" => "35",
                "np_name" => "Chitwan",
                "province" => "3",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "26",
                "dist_id" => "31",
                "np_name" => "Makwanpur",
                "province" => "3",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "27",
                "dist_id" => "20",
                "np_name" => "Sindhuli",
                "province" => "3",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "28",
                "dist_id" => "21",
                "np_name" => "Ramechhap",
                "province" => "3",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "29",
                "dist_id" => "22",
                "np_name" => "Dolakha",
                "province" => "3",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "30",
                "dist_id" => "23",
                "np_name" => "Sindhupalchowk",
                "province" => "3",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "31",
                "dist_id" => "25",
                "np_name" => "Dhading",
                "province" => "3",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "32",
                "dist_id" => "26",
                "np_name" => "Nuwakot",
                "province" => "3",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "33",
                "dist_id" => "29",
                "np_name" => "Bhaktapur",
                "province" => "3",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "34",
                "dist_id" => "30",
                "np_name" => "Kavrepalanchok",
                "province" => "3",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "35",
                "dist_id" => "24",
                "np_name" => "Rasuwa",
                "province" => "3",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "36",
                "dist_id" => "47",
                "np_name" => "Kaski",
                "province" => "4",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "37",
                "dist_id" => "76",
                "np_name" => "Nawalparasi",
                "province" => "4",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "38",
                "dist_id" => "42",
                "np_name" => "Syangja",
                "province" => "4",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "39",
                "dist_id" => "43",
                "np_name" => "tanahu",
                "province" => "4",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "40",
                "dist_id" => "44",
                "np_name" => "Gorkha",
                "province" => "4",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "41",
                "dist_id" => "46",
                "np_name" => "Lamjung",
                "province" => "4",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "42",
                "dist_id" => "48",
                "np_name" => "Parbat",
                "province" => "4",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "43",
                "dist_id" => "49",
                "np_name" => "Baglung",
                "province" => "4",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "44",
                "dist_id" => "50",
                "np_name" => "Myagdi",
                "province" => "4",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "45",
                "dist_id" => "45",
                "np_name" => "Manang",
                "province" => "4",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "46",
                "dist_id" => "51",
                "np_name" => "Mustang",
                "province" => "4",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "47",
                "dist_id" => "37",
                "np_name" => "Rupandehi",
                "province" => "5",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "48",
                "dist_id" => "60",
                "np_name" => "Dang",
                "province" => "5",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "49",
                "dist_id" => "62",
                "np_name" => "Banke",
                "province" => "5",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "50",
                "dist_id" => "36",
                "np_name" => "Nawalparasi",
                "province" => "5",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "51",
                "dist_id" => "38",
                "np_name" => "Kapilvastu",
                "province" => "5",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "52",
                "dist_id" => "39",
                "np_name" => "Arghakhanchi",
                "province" => "5",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "53",
                "dist_id" => "40",
                "np_name" => "Palpa",
                "province" => "5",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "54",
                "dist_id" => "41",
                "np_name" => "Gulmi",
                "province" => "5",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "55",
                "dist_id" => "58",
                "np_name" => "Rolpa",
                "province" => "5",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "56",
                "dist_id" => "59",
                "np_name" => "Pyuthan",
                "province" => "5",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "57",
                "dist_id" => "63",
                "np_name" => "Bardiya",
                "province" => "5",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "58",
                "dist_id" => "77",
                "np_name" => "Rukum",
                "province" => "5",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "59",
                "dist_id" => "52",
                "np_name" => "Mugu",
                "province" => "6",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "60",
                "dist_id" => "53",
                "np_name" => "Dolpa",
                "province" => "6",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "61",
                "dist_id" => "55",
                "np_name" => "Jumla",
                "province" => "6",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "62",
                "dist_id" => "56",
                "np_name" => "Kalikot",
                "province" => "6",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "63",
                "dist_id" => "57",
                "np_name" => "Rukum",
                "province" => "6",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "64",
                "dist_id" => "61",
                "np_name" => "Salyan",
                "province" => "6",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "65",
                "dist_id" => "64",
                "np_name" => "Surkhet",
                "province" => "6",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "66",
                "dist_id" => "65",
                "np_name" => "Jajarkot",
                "province" => "6",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "67",
                "dist_id" => "66",
                "np_name" => "Dailekh",
                "province" => "6",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "68",
                "dist_id" => "54",
                "np_name" => "Humla",
                "province" => "6",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "69",
                "dist_id" => "67",
                "np_name" => "Kailali",
                "province" => "7",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "70",
                "dist_id" => "68",
                "np_name" => "Doti",
                "province" => "7",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "71",
                "dist_id" => "69",
                "np_name" => "Achham",
                "province" => "7",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "72",
                "dist_id" => "70",
                "np_name" => "Bajura",
                "province" => "7",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "73",
                "dist_id" => "71",
                "np_name" => "Bajhang",
                "province" => "7",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "74",
                "dist_id" => "72",
                "np_name" => "Darchula",
                "province" => "7",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "75",
                "dist_id" => "73",
                "np_name" => "Baitadi",
                "province" => "7",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "76",
                "dist_id" => "74",
                "np_name" => "Dadeldhura",
                "province" => "7",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ],
            [
                "id" => "77",
                "dist_id" => "75",
                "np_name" => "Kanchanpur",
                "province" => "7",
                "created_at" => "2022-01-01",
                "updated_at" => "2022-01-01"
            ]
        ];
        \DB::table('districts')->insert($tbl_district);
    }
}
