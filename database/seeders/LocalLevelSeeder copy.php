<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocalLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('locals')->insert([
        [
            "id"=> "9",
            "local_level_id"=> "9",
            "province_id"=> "3",
            "local_id"=> "3.101",
            "local_name"=> "काठमाण्डौं महानगरपालिका",
            "dist_id"=> "27",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "10",
            "local_level_id"=> "10",
            "province_id"=> "3",
            "local_id"=> "3.102",
            "local_name"=> "ललितपुर महानगरपालिका",
            "dist_id"=> "28",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "11",
            "local_level_id"=> "11",
            "province_id"=> "3",
            "local_id"=> "3.103",
            "local_name"=> "भरतपुर महानगरपालिका",
            "dist_id"=> "35",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "12",
            "local_level_id"=> "12",
            "province_id"=> "4",
            "local_id"=> "3.104",
            "local_name"=> "पोखरा महानगरपालिका",
            "dist_id"=> "47",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "13",
            "local_level_id"=> "13",
            "province_id"=> "1",
            "local_id"=> "3.111",
            "local_name"=> "ईटहरी उपमहानगरपालिका",
            "dist_id"=> "9",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "14",
            "local_level_id"=> "14",
            "province_id"=> "1",
            "local_id"=> "3.112",
            "local_name"=> "धरान उपमहानगरपालिका",
            "dist_id"=> "9",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "15",
            "local_level_id"=> "15",
            "province_id"=> "1",
            "local_id"=> "3.113",
            "local_name"=> "विराटनगर महानगरपालिका",
            "dist_id"=> "10",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "16",
            "local_level_id"=> "16",
            "province_id"=> "2",
            "local_id"=> "3.114",
            "local_name"=> "जनकपुर उपमहानगरपालिका",
            "dist_id"=> "17",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "17",
            "local_level_id"=> "17",
            "province_id"=> "3",
            "local_id"=> "3.115",
            "local_name"=> "हेटौंडा उपमहानगरपालिका",
            "dist_id"=> "31",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "18",
            "local_level_id"=> "18",
            "province_id"=> "2",
            "local_id"=> "3.116",
            "local_name"=> "कलैया उपमहानगरपालिका",
            "dist_id"=> "33",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "19",
            "local_level_id"=> "19",
            "province_id"=> "2",
            "local_id"=> "3.117",
            "local_name"=> "जितपुर-सिमरा उपमहानगरपालिका",
            "dist_id"=> "33",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "20",
            "local_level_id"=> "20",
            "province_id"=> "2",
            "local_id"=> "3.118",
            "local_name"=> "वीरगञ्ज महानगरपालिका",
            "dist_id"=> "34",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "21",
            "local_level_id"=> "21",
            "province_id"=> "5",
            "local_id"=> "3.119",
            "local_name"=> "बुटवल उपमहानगरपालिका",
            "dist_id"=> "37",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "22",
            "local_level_id"=> "22",
            "province_id"=> "5",
            "local_id"=> "3.12",
            "local_name"=> "घोराही उपमहानगरपालिका",
            "dist_id"=> "60",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "23",
            "local_level_id"=> "23",
            "province_id"=> "5",
            "local_id"=> "3.121",
            "local_name"=> "तुल्सीपुर उपमहानगरपालिका",
            "dist_id"=> "60",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "24",
            "local_level_id"=> "24",
            "province_id"=> "5",
            "local_id"=> "3.122",
            "local_name"=> "नेपालगञ्ज उपमहानगरपालिका",
            "dist_id"=> "62",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "25",
            "local_level_id"=> "25",
            "province_id"=> "7",
            "local_id"=> "3.123",
            "local_name"=> "धनगढी उपमहानगरपालिका",
            "dist_id"=> "67",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "26",
            "local_level_id"=> "26",
            "province_id"=> "1",
            "local_id"=> "3.131",
            "local_name"=> "फुङलिङ नगरपालिका",
            "dist_id"=> "1",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "27",
            "local_level_id"=> "27",
            "province_id"=> "1",
            "local_id"=> "3.132",
            "local_name"=> "फिदिम नगरपालिका",
            "dist_id"=> "2",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "28",
            "local_level_id"=> "28",
            "province_id"=> "1",
            "local_id"=> "3.133",
            "local_name"=> "इलाम नगरपालिका",
            "dist_id"=> "3",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "29",
            "local_level_id"=> "29",
            "province_id"=> "1",
            "local_id"=> "3.134",
            "local_name"=> "देउमाई नगरपालिका",
            "dist_id"=> "3",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "30",
            "local_level_id"=> "30",
            "province_id"=> "1",
            "local_id"=> "3.135",
            "local_name"=> "माई नगरपालिका",
            "dist_id"=> "3",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "31",
            "local_level_id"=> "31",
            "province_id"=> "1",
            "local_id"=> "3.136",
            "local_name"=> "सूर्योदय नगरपालिका",
            "dist_id"=> "3",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "32",
            "local_level_id"=> "32",
            "province_id"=> "1",
            "local_id"=> "3.137",
            "local_name"=> "अर्जुनधारा नगरपालिका",
            "dist_id"=> "4",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "33",
            "local_level_id"=> "33",
            "province_id"=> "1",
            "local_id"=> "3.138",
            "local_name"=> "कन्काई नगरपालिका",
            "dist_id"=> "4",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "34",
            "local_level_id"=> "34",
            "province_id"=> "1",
            "local_id"=> "3.139",
            "local_name"=> "गौरादह नगरपालिका",
            "dist_id"=> "4",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "35",
            "local_level_id"=> "35",
            "province_id"=> "1",
            "local_id"=> "3.14",
            "local_name"=> "दमक नगरपालिका",
            "dist_id"=> "4",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "36",
            "local_level_id"=> "36",
            "province_id"=> "1",
            "local_id"=> "3.141",
            "local_name"=> "विर्तामोड नगरपालिका",
            "dist_id"=> "4",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "37",
            "local_level_id"=> "37",
            "province_id"=> "1",
            "local_id"=> "3.142",
            "local_name"=> "भद्रपुर नगरपालिका",
            "dist_id"=> "4",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "38",
            "local_level_id"=> "38",
            "province_id"=> "1",
            "local_id"=> "3.143",
            "local_name"=> "मेचीनगर नगरपालिका",
            "dist_id"=> "4",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "39",
            "local_level_id"=> "39",
            "province_id"=> "1",
            "local_id"=> "3.144",
            "local_name"=> "शिवसताक्षी नगरपालिका",
            "dist_id"=> "4",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "40",
            "local_level_id"=> "40",
            "province_id"=> "1",
            "local_id"=> "3.145",
            "local_name"=> "खाँदवारी नगरपालिका",
            "dist_id"=> "5",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "41",
            "local_level_id"=> "41",
            "province_id"=> "1",
            "local_id"=> "3.146",
            "local_name"=> "चैनपुर नगरपालिका",
            "dist_id"=> "5",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "42",
            "local_level_id"=> "42",
            "province_id"=> "1",
            "local_id"=> "3.147",
            "local_name"=> "धर्मदेवी नगरपालिका",
            "dist_id"=> "5",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "43",
            "local_level_id"=> "43",
            "province_id"=> "1",
            "local_id"=> "3.148",
            "local_name"=> "पाँचखपन नगरपालिका",
            "dist_id"=> "5",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "44",
            "local_level_id"=> "44",
            "province_id"=> "1",
            "local_id"=> "3.149",
            "local_name"=> "मादी नगरपालिका",
            "dist_id"=> "5",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "45",
            "local_level_id"=> "45",
            "province_id"=> "1",
            "local_id"=> "3.15",
            "local_name"=> "म्याङलुङ नगरपालिका",
            "dist_id"=> "6",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "46",
            "local_level_id"=> "46",
            "province_id"=> "1",
            "local_id"=> "3.151",
            "local_name"=> "लालीगुराँस नगरपालिका",
            "dist_id"=> "6",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "47",
            "local_level_id"=> "47",
            "province_id"=> "1",
            "local_id"=> "3.152",
            "local_name"=> "भोजपुर नगरपालिका",
            "dist_id"=> "7",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "48",
            "local_level_id"=> "48",
            "province_id"=> "1",
            "local_id"=> "3.153",
            "local_name"=> "षडानन्द नगरपालिका",
            "dist_id"=> "7",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "49",
            "local_level_id"=> "49",
            "province_id"=> "1",
            "local_id"=> "3.154",
            "local_name"=> "धनकुटा नगरपालिका",
            "dist_id"=> "8",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "50",
            "local_level_id"=> "50",
            "province_id"=> "1",
            "local_id"=> "3.155",
            "local_name"=> "पाख्रीबास नगरपालिका",
            "dist_id"=> "8",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "51",
            "local_level_id"=> "51",
            "province_id"=> "1",
            "local_id"=> "3.156",
            "local_name"=> "महालक्ष्मी नगरपालिका",
            "dist_id"=> "8",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "52",
            "local_level_id"=> "52",
            "province_id"=> "1",
            "local_id"=> "3.157",
            "local_name"=> "इनरुवा नगरपालिका",
            "dist_id"=> "9",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "53",
            "local_level_id"=> "53",
            "province_id"=> "1",
            "local_id"=> "3.158",
            "local_name"=> "दुहवी नगरपालिका",
            "dist_id"=> "9",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "54",
            "local_level_id"=> "54",
            "province_id"=> "1",
            "local_id"=> "3.159",
            "local_name"=> "बराहक्षेत्र नगरपालिका",
            "dist_id"=> "9",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "55",
            "local_level_id"=> "55",
            "province_id"=> "1",
            "local_id"=> "3.16",
            "local_name"=> "रामधुनी नगरपालिका",
            "dist_id"=> "9",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "56",
            "local_level_id"=> "56",
            "province_id"=> "1",
            "local_id"=> "3.161",
            "local_name"=> "उर्लाबारी नगरपालिका",
            "dist_id"=> "10",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "57",
            "local_level_id"=> "57",
            "province_id"=> "1",
            "local_id"=> "3.162",
            "local_name"=> "पथरी शनिश्चरे नगरपालिका",
            "dist_id"=> "10",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "58",
            "local_level_id"=> "58",
            "province_id"=> "1",
            "local_id"=> "3.163",
            "local_name"=> "बेलबारी नगरपालिका",
            "dist_id"=> "10",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "59",
            "local_level_id"=> "59",
            "province_id"=> "1",
            "local_id"=> "3.164",
            "local_name"=> "रंगेली नगरपालिका",
            "dist_id"=> "10",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "60",
            "local_level_id"=> "60",
            "province_id"=> "1",
            "local_id"=> "3.165",
            "local_name"=> "रतुवामाई नगरपालिका",
            "dist_id"=> "10",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "61",
            "local_level_id"=> "61",
            "province_id"=> "1",
            "local_id"=> "3.166",
            "local_name"=> "लेटाङ नगरपालिका",
            "dist_id"=> "10",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "62",
            "local_level_id"=> "62",
            "province_id"=> "1",
            "local_id"=> "3.167",
            "local_name"=> "सुनवर्षी नगरपालिका",
            "dist_id"=> "10",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "63",
            "local_level_id"=> "63",
            "province_id"=> "1",
            "local_id"=> "3.168",
            "local_name"=> "सुन्दरहरैँचा नगरपालिका",
            "dist_id"=> "10",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "64",
            "local_level_id"=> "64",
            "province_id"=> "1",
            "local_id"=> "3.169",
            "local_name"=> "सोलुदुधकुण्ड नगरपालिका",
            "dist_id"=> "11",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "65",
            "local_level_id"=> "65",
            "province_id"=> "1",
            "local_id"=> "3.17",
            "local_name"=> "दिक्तेल रुपाकोट मझुवागढी नगरपालिका",
            "dist_id"=> "12",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "66",
            "local_level_id"=> "66",
            "province_id"=> "1",
            "local_id"=> "3.171",
            "local_name"=> "हलेसी तुवाचुङ नगरपालिका",
            "dist_id"=> "12",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "67",
            "local_level_id"=> "67",
            "province_id"=> "1",
            "local_id"=> "3.172",
            "local_name"=> "कटारी नगरपालिका",
            "dist_id"=> "13",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "68",
            "local_level_id"=> "68",
            "province_id"=> "1",
            "local_id"=> "3.173",
            "local_name"=> "चौदण्डीगढी नगरपालिका",
            "dist_id"=> "13",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "69",
            "local_level_id"=> "69",
            "province_id"=> "1",
            "local_id"=> "3.174",
            "local_name"=> "त्रियुगा नगरपालिका",
            "dist_id"=> "13",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "70",
            "local_level_id"=> "70",
            "province_id"=> "1",
            "local_id"=> "3.175",
            "local_name"=> "वेलका नगरपालिका",
            "dist_id"=> "13",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "71",
            "local_level_id"=> "71",
            "province_id"=> "1",
            "local_id"=> "3.176",
            "local_name"=> "सिद्धिचरण नगरपालिका",
            "dist_id"=> "14",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "72",
            "local_level_id"=> "72",
            "province_id"=> "2",
            "local_id"=> "3.177",
            "local_name"=> "कञ्चनरुप नगरपालिका",
            "dist_id"=> "15",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "73",
            "local_level_id"=> "73",
            "province_id"=> "2",
            "local_id"=> "3.178",
            "local_name"=> "खडक नगरपालिका",
            "dist_id"=> "15",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "74",
            "local_level_id"=> "74",
            "province_id"=> "2",
            "local_id"=> "3.179",
            "local_name"=> "डाक्नेश्वरी नगरपालिका",
            "dist_id"=> "15",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "75",
            "local_level_id"=> "75",
            "province_id"=> "2",
            "local_id"=> "3.18",
            "local_name"=> "राजविराज नगरपालिका",
            "dist_id"=> "15",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "76",
            "local_level_id"=> "76",
            "province_id"=> "2",
            "local_id"=> "3.181",
            "local_name"=> "बोदेबरसाईन नगरपालिका",
            "dist_id"=> "15",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "77",
            "local_level_id"=> "77",
            "province_id"=> "2",
            "local_id"=> "3.182",
            "local_name"=> "शम्भुनाथ नगरपालिका",
            "dist_id"=> "15",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "78",
            "local_level_id"=> "78",
            "province_id"=> "2",
            "local_id"=> "3.183",
            "local_name"=> "सुरुङ्गा नगरपालिका",
            "dist_id"=> "15",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "79",
            "local_level_id"=> "79",
            "province_id"=> "2",
            "local_id"=> "3.184",
            "local_name"=> "हनुमाननगर कंकालिनी नगरपालिका",
            "dist_id"=> "15",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "80",
            "local_level_id"=> "80",
            "province_id"=> "2",
            "local_id"=> "3.185",
            "local_name"=> "कल्याणपुर नगरपालिका",
            "dist_id"=> "16",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "81",
            "local_level_id"=> "81",
            "province_id"=> "2",
            "local_id"=> "3.186",
            "local_name"=> "गोलबजार नगरपालिका",
            "dist_id"=> "16",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "82",
            "local_level_id"=> "82",
            "province_id"=> "2",
            "local_id"=> "3.187",
            "local_name"=> "धनगढीमाई नगरपालिका",
            "dist_id"=> "16",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "83",
            "local_level_id"=> "83",
            "province_id"=> "2",
            "local_id"=> "3.188",
            "local_name"=> "मिर्चैया नगरपालिका",
            "dist_id"=> "16",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "84",
            "local_level_id"=> "84",
            "province_id"=> "2",
            "local_id"=> "3.189",
            "local_name"=> "लहान नगरपालिका",
            "dist_id"=> "16",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "85",
            "local_level_id"=> "85",
            "province_id"=> "2",
            "local_id"=> "3.19",
            "local_name"=> "सिरहा नगरपालिका",
            "dist_id"=> "16",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "86",
            "local_level_id"=> "86",
            "province_id"=> "2",
            "local_id"=> "3.191",
            "local_name"=> "सुखीपुर नगरपालिका",
            "dist_id"=> "16",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "87",
            "local_level_id"=> "87",
            "province_id"=> "2",
            "local_id"=> "3.192",
            "local_name"=> "क्षिरेश्वरनाथ नगरपालिका",
            "dist_id"=> "17",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "88",
            "local_level_id"=> "88",
            "province_id"=> "2",
            "local_id"=> "3.193",
            "local_name"=> "गणेशमान चारनाथ नगरपालिका",
            "dist_id"=> "17",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "89",
            "local_level_id"=> "89",
            "province_id"=> "2",
            "local_id"=> "3.194",
            "local_name"=> "धनुषाधाम नगरपालिका",
            "dist_id"=> "17",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "90",
            "local_level_id"=> "90",
            "province_id"=> "2",
            "local_id"=> "3.195",
            "local_name"=> "नगराइन नगरपालिका",
            "dist_id"=> "17",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "91",
            "local_level_id"=> "91",
            "province_id"=> "2",
            "local_id"=> "3.196",
            "local_name"=> "मिथिला नगरपालिका",
            "dist_id"=> "17",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "92",
            "local_level_id"=> "92",
            "province_id"=> "2",
            "local_id"=> "3.197",
            "local_name"=> "विदेह नगरपालिका",
            "dist_id"=> "17",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "93",
            "local_level_id"=> "93",
            "province_id"=> "2",
            "local_id"=> "3.198",
            "local_name"=> "सबैला नगरपालिका",
            "dist_id"=> "17",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "94",
            "local_level_id"=> "94",
            "province_id"=> "2",
            "local_id"=> "3.199",
            "local_name"=> "शहिदनगर नगरपालिका",
            "dist_id"=> "17",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "95",
            "local_level_id"=> "95",
            "province_id"=> "2",
            "local_id"=> "3.2",
            "local_name"=> "गौशाला नगरपालिका",
            "dist_id"=> "18",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "96",
            "local_level_id"=> "96",
            "province_id"=> "2",
            "local_id"=> "3.201",
            "local_name"=> "जलेश्वर नगरपालिका",
            "dist_id"=> "18",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "97",
            "local_level_id"=> "97",
            "province_id"=> "2",
            "local_id"=> "3.202",
            "local_name"=> "बर्दिबास नगरपालिका",
            "dist_id"=> "18",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "98",
            "local_level_id"=> "98",
            "province_id"=> "2",
            "local_id"=> "3.203",
            "local_name"=> "ईश्वरपुर नगरपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "99",
            "local_level_id"=> "99",
            "province_id"=> "2",
            "local_id"=> "3.204",
            "local_name"=> "गोडैटा नगरपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "100",
            "local_level_id"=> "100",
            "province_id"=> "2",
            "local_id"=> "3.205",
            "local_name"=> "मलंगवा नगरपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "101",
            "local_level_id"=> "101",
            "province_id"=> "2",
            "local_id"=> "3.206",
            "local_name"=> "लालबन्दी नगरपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "102",
            "local_level_id"=> "102",
            "province_id"=> "2",
            "local_id"=> "3.207",
            "local_name"=> "बरहथवा नगरपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "103",
            "local_level_id"=> "103",
            "province_id"=> "2",
            "local_id"=> "3.208",
            "local_name"=> "बलरा नगरपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "104",
            "local_level_id"=> "104",
            "province_id"=> "2",
            "local_id"=> "3.209",
            "local_name"=> "बागमती नगरपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "105",
            "local_level_id"=> "105",
            "province_id"=> "2",
            "local_id"=> "3.21",
            "local_name"=> "हरिपुर नगरपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "106",
            "local_level_id"=> "106",
            "province_id"=> "2",
            "local_id"=> "3.211",
            "local_name"=> "हरिवन नगरपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "107",
            "local_level_id"=> "107",
            "province_id"=> "2",
            "local_id"=> "3.212",
            "local_name"=> "हरिपुर्वा नगरपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "108",
            "local_level_id"=> "108",
            "province_id"=> "3",
            "local_id"=> "3.213",
            "local_name"=> "कमलामाई नगरपालिका",
            "dist_id"=> "20",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "109",
            "local_level_id"=> "109",
            "province_id"=> "3",
            "local_id"=> "3.214",
            "local_name"=> "दुधौली नगरपालिका",
            "dist_id"=> "20",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "110",
            "local_level_id"=> "110",
            "province_id"=> "3",
            "local_id"=> "3.215",
            "local_name"=> "मन्थली नगरपालिका",
            "dist_id"=> "21",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "111",
            "local_level_id"=> "111",
            "province_id"=> "3",
            "local_id"=> "3.216",
            "local_name"=> "रामेछाप नगरपालिका",
            "dist_id"=> "21",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "112",
            "local_level_id"=> "112",
            "province_id"=> "3",
            "local_id"=> "3.217",
            "local_name"=> "जिरी नगरपालिका",
            "dist_id"=> "22",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "113",
            "local_level_id"=> "113",
            "province_id"=> "3",
            "local_id"=> "3.218",
            "local_name"=> "भिमेश्वर नगरपालिका",
            "dist_id"=> "22",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "114",
            "local_level_id"=> "114",
            "province_id"=> "3",
            "local_id"=> "3.219",
            "local_name"=> "चौतारा साँगाचोकगढी नगरपालिका",
            "dist_id"=> "23",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "115",
            "local_level_id"=> "115",
            "province_id"=> "3",
            "local_id"=> "3.22",
            "local_name"=> "मेलम्ची नगरपालिका",
            "dist_id"=> "23",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "116",
            "local_level_id"=> "116",
            "province_id"=> "3",
            "local_id"=> "3.221",
            "local_name"=> "बाह्रविसे नगरपालिका",
            "dist_id"=> "23",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "117",
            "local_level_id"=> "117",
            "province_id"=> "3",
            "local_id"=> "3.222",
            "local_name"=> "धुनिवेशी नगरपालिका",
            "dist_id"=> "25",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "118",
            "local_level_id"=> "118",
            "province_id"=> "3",
            "local_id"=> "3.223",
            "local_name"=> "नीलकण्ठ नगरपालिका",
            "dist_id"=> "25",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "119",
            "local_level_id"=> "119",
            "province_id"=> "3",
            "local_id"=> "3.224",
            "local_name"=> "विदुर नगरपालिका",
            "dist_id"=> "26",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "120",
            "local_level_id"=> "120",
            "province_id"=> "3",
            "local_id"=> "3.225",
            "local_name"=> "बेलकोटगढी नगरपालिका",
            "dist_id"=> "26",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "121",
            "local_level_id"=> "121",
            "province_id"=> "3",
            "local_id"=> "3.226",
            "local_name"=> "कागेश्वरी मनोहरा नगरपालिका",
            "dist_id"=> "27",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "122",
            "local_level_id"=> "122",
            "province_id"=> "3",
            "local_id"=> "3.227",
            "local_name"=> "कीर्तिपुर नगरपालिका",
            "dist_id"=> "27",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "123",
            "local_level_id"=> "123",
            "province_id"=> "3",
            "local_id"=> "3.228",
            "local_name"=> "गोकर्णेश्वर नगरपालिका",
            "dist_id"=> "27",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "124",
            "local_level_id"=> "124",
            "province_id"=> "3",
            "local_id"=> "3.229",
            "local_name"=> "चन्द्रागिरी नगरपालिका",
            "dist_id"=> "27",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "125",
            "local_level_id"=> "125",
            "province_id"=> "3",
            "local_id"=> "3.23",
            "local_name"=> "टोखा नगरपालिका",
            "dist_id"=> "27",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "126",
            "local_level_id"=> "126",
            "province_id"=> "3",
            "local_id"=> "3.231",
            "local_name"=> "तारकेश्वर नगरपालिका",
            "dist_id"=> "27",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "127",
            "local_level_id"=> "127",
            "province_id"=> "3",
            "local_id"=> "3.232",
            "local_name"=> "दक्षिणकाली नगरपालिका",
            "dist_id"=> "27",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "128",
            "local_level_id"=> "128",
            "province_id"=> "3",
            "local_id"=> "3.233",
            "local_name"=> "नागार्जुन नगरपालिका",
            "dist_id"=> "27",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "129",
            "local_level_id"=> "129",
            "province_id"=> "3",
            "local_id"=> "3.234",
            "local_name"=> "बुढानिलकण्ठ नगरपालिका",
            "dist_id"=> "27",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "130",
            "local_level_id"=> "130",
            "province_id"=> "3",
            "local_id"=> "3.235",
            "local_name"=> "शंखरापुर नगरपालिका",
            "dist_id"=> "27",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "131",
            "local_level_id"=> "131",
            "province_id"=> "3",
            "local_id"=> "3.236",
            "local_name"=> "गोदावरी नगरपालिका",
            "dist_id"=> "28",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "132",
            "local_level_id"=> "132",
            "province_id"=> "3",
            "local_id"=> "3.237",
            "local_name"=> "महालक्ष्मी नगरपालिका",
            "dist_id"=> "28",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "133",
            "local_level_id"=> "133",
            "province_id"=> "3",
            "local_id"=> "3.238",
            "local_name"=> "चाँगुनारायण नगरपालिका",
            "dist_id"=> "29",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "134",
            "local_level_id"=> "134",
            "province_id"=> "3",
            "local_id"=> "3.239",
            "local_name"=> "भक्तपुर नगरपालिका",
            "dist_id"=> "29",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "135",
            "local_level_id"=> "135",
            "province_id"=> "3",
            "local_id"=> "3.24",
            "local_name"=> "मध्यपुर थिमी नगरपालिका",
            "dist_id"=> "29",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "136",
            "local_level_id"=> "136",
            "province_id"=> "3",
            "local_id"=> "3.241",
            "local_name"=> "सूर्यविनायक नगरपालिका",
            "dist_id"=> "29",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "137",
            "local_level_id"=> "137",
            "province_id"=> "3",
            "local_id"=> "3.242",
            "local_name"=> "धुलिखेल नगरपालिका",
            "dist_id"=> "30",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "138",
            "local_level_id"=> "138",
            "province_id"=> "3",
            "local_id"=> "3.243",
            "local_name"=> "नमोबुद्ध नगरपालिका",
            "dist_id"=> "30",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "139",
            "local_level_id"=> "139",
            "province_id"=> "3",
            "local_id"=> "3.244",
            "local_name"=> "पनौती नगरपालिका",
            "dist_id"=> "30",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "140",
            "local_level_id"=> "140",
            "province_id"=> "3",
            "local_id"=> "3.245",
            "local_name"=> "पाँचखाल नगरपालिका",
            "dist_id"=> "30",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "141",
            "local_level_id"=> "141",
            "province_id"=> "3",
            "local_id"=> "3.246",
            "local_name"=> "बनेपा नगरपालिका",
            "dist_id"=> "30",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "142",
            "local_level_id"=> "142",
            "province_id"=> "3",
            "local_id"=> "3.247",
            "local_name"=> "मण्डनदेउपुर नगरपालिका",
            "dist_id"=> "30",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "143",
            "local_level_id"=> "143",
            "province_id"=> "3",
            "local_id"=> "3.248",
            "local_name"=> "थाहा नगरपालिका",
            "dist_id"=> "31",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "144",
            "local_level_id"=> "144",
            "province_id"=> "2",
            "local_id"=> "3.249",
            "local_name"=> "गरुडा नगरपालिका",
            "dist_id"=> "32",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "145",
            "local_level_id"=> "145",
            "province_id"=> "2",
            "local_id"=> "3.25",
            "local_name"=> "गौर नगरपालिका",
            "dist_id"=> "32",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "146",
            "local_level_id"=> "146",
            "province_id"=> "2",
            "local_id"=> "3.251",
            "local_name"=> "चन्द्रपुर नगरपालिका",
            "dist_id"=> "32",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "147",
            "local_level_id"=> "147",
            "province_id"=> "2",
            "local_id"=> "3.252",
            "local_name"=> "कोल्हवी नगरपालिका",
            "dist_id"=> "33",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "148",
            "local_level_id"=> "148",
            "province_id"=> "2",
            "local_id"=> "3.253",
            "local_name"=> "निजगढ नगरपालिका",
            "dist_id"=> "33",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "149",
            "local_level_id"=> "149",
            "province_id"=> "2",
            "local_id"=> "3.254",
            "local_name"=> "महागढीमाई नगरपालिका",
            "dist_id"=> "33",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "150",
            "local_level_id"=> "150",
            "province_id"=> "2",
            "local_id"=> "3.255",
            "local_name"=> "सिम्रौनगढ नगरपालिका",
            "dist_id"=> "33",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "151",
            "local_level_id"=> "151",
            "province_id"=> "2",
            "local_id"=> "3.256",
            "local_name"=> "पोखरिया नगरपालिका",
            "dist_id"=> "34",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "152",
            "local_level_id"=> "152",
            "province_id"=> "3",
            "local_id"=> "3.257",
            "local_name"=> "कालिका नगरपालिका",
            "dist_id"=> "35",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "153",
            "local_level_id"=> "153",
            "province_id"=> "3",
            "local_id"=> "3.258",
            "local_name"=> "खैरहनी नगरपालिका",
            "dist_id"=> "35",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "154",
            "local_level_id"=> "154",
            "province_id"=> "3",
            "local_id"=> "3.259",
            "local_name"=> "माडी नगरपालिका",
            "dist_id"=> "35",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "155",
            "local_level_id"=> "155",
            "province_id"=> "3",
            "local_id"=> "3.26",
            "local_name"=> "रत्ननगर नगरपालिका",
            "dist_id"=> "35",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "156",
            "local_level_id"=> "156",
            "province_id"=> "3",
            "local_id"=> "3.261",
            "local_name"=> "राप्ती नगरपालिका",
            "dist_id"=> "35",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "157",
            "local_level_id"=> "157",
            "province_id"=> "4",
            "local_id"=> "3.262",
            "local_name"=> "कावासोती नगरपालिका",
            "dist_id"=> "76",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "158",
            "local_level_id"=> "158",
            "province_id"=> "4",
            "local_id"=> "3.263",
            "local_name"=> "गैंडाकोट नगरपालिका",
            "dist_id"=> "76",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "159",
            "local_level_id"=> "159",
            "province_id"=> "4",
            "local_id"=> "3.264",
            "local_name"=> "देवचुली नगरपालिका",
            "dist_id"=> "76",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "160",
            "local_level_id"=> "160",
            "province_id"=> "5",
            "local_id"=> "3.265",
            "local_name"=> "बर्दघाट नगरपालिका",
            "dist_id"=> "36",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "161",
            "local_level_id"=> "161",
            "province_id"=> "4",
            "local_id"=> "3.266",
            "local_name"=> "मध्यविन्दु नगरपालिका",
            "dist_id"=> "76",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "162",
            "local_level_id"=> "162",
            "province_id"=> "5",
            "local_id"=> "3.267",
            "local_name"=> "रामग्राम नगरपालिका",
            "dist_id"=> "36",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "163",
            "local_level_id"=> "163",
            "province_id"=> "5",
            "local_id"=> "3.268",
            "local_name"=> "सुनवल नगरपालिका",
            "dist_id"=> "36",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "164",
            "local_level_id"=> "164",
            "province_id"=> "5",
            "local_id"=> "3.269",
            "local_name"=> "तिलोत्तमा नगरपालिका",
            "dist_id"=> "37",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "165",
            "local_level_id"=> "165",
            "province_id"=> "5",
            "local_id"=> "3.27",
            "local_name"=> "देवदह नगरपालिका",
            "dist_id"=> "37",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "166",
            "local_level_id"=> "166",
            "province_id"=> "5",
            "local_id"=> "3.271",
            "local_name"=> "लुम्बिनी साँस्कृतिक नगरपालिका",
            "dist_id"=> "37",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "167",
            "local_level_id"=> "167",
            "province_id"=> "5",
            "local_id"=> "3.272",
            "local_name"=> "सिद्धार्थनगर नगरपालिका",
            "dist_id"=> "37",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "168",
            "local_level_id"=> "168",
            "province_id"=> "5",
            "local_id"=> "3.273",
            "local_name"=> "सैनामैना नगरपालिका",
            "dist_id"=> "37",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "169",
            "local_level_id"=> "169",
            "province_id"=> "5",
            "local_id"=> "3.274",
            "local_name"=> "कपिलवस्तु नगरपालिका",
            "dist_id"=> "38",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "170",
            "local_level_id"=> "170",
            "province_id"=> "5",
            "local_id"=> "3.275",
            "local_name"=> "कृष्णनगर नगरपालिका",
            "dist_id"=> "38",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "171",
            "local_level_id"=> "171",
            "province_id"=> "5",
            "local_id"=> "3.276",
            "local_name"=> "बाणगङ्गा नगरपालिका",
            "dist_id"=> "38",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "172",
            "local_level_id"=> "172",
            "province_id"=> "5",
            "local_id"=> "3.277",
            "local_name"=> "बुद्धभूमि नगरपालिका",
            "dist_id"=> "38",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "173",
            "local_level_id"=> "173",
            "province_id"=> "5",
            "local_id"=> "3.278",
            "local_name"=> "महाराजगञ्ज नगरपालिका",
            "dist_id"=> "38",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "174",
            "local_level_id"=> "174",
            "province_id"=> "5",
            "local_id"=> "3.279",
            "local_name"=> "शिवराज नगरपालिका",
            "dist_id"=> "38",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "175",
            "local_level_id"=> "175",
            "province_id"=> "5",
            "local_id"=> "3.28",
            "local_name"=> "भुमिकास्थान नगरपालिका",
            "dist_id"=> "39",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "176",
            "local_level_id"=> "176",
            "province_id"=> "5",
            "local_id"=> "3.281",
            "local_name"=> "शितगंगा नगरपालिका",
            "dist_id"=> "39",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "177",
            "local_level_id"=> "177",
            "province_id"=> "5",
            "local_id"=> "3.282",
            "local_name"=> "सन्धिखर्क नगरपालिका",
            "dist_id"=> "39",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "178",
            "local_level_id"=> "178",
            "province_id"=> "5",
            "local_id"=> "3.283",
            "local_name"=> "तानसेन नगरपालिका",
            "dist_id"=> "40",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "179",
            "local_level_id"=> "179",
            "province_id"=> "5",
            "local_id"=> "3.284",
            "local_name"=> "रामपुर नगरपालिका",
            "dist_id"=> "40",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "180",
            "local_level_id"=> "180",
            "province_id"=> "5",
            "local_id"=> "3.285",
            "local_name"=> "मुसिकोट नगरपालिका",
            "dist_id"=> "41",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "181",
            "local_level_id"=> "181",
            "province_id"=> "5",
            "local_id"=> "3.286",
            "local_name"=> "रेसुङ्गा नगरपालिका",
            "dist_id"=> "41",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "182",
            "local_level_id"=> "182",
            "province_id"=> "4",
            "local_id"=> "3.287",
            "local_name"=> "गल्याङ नगरपालिका",
            "dist_id"=> "42",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "183",
            "local_level_id"=> "183",
            "province_id"=> "4",
            "local_id"=> "3.288",
            "local_name"=> "चापाकोट नगरपालिका",
            "dist_id"=> "42",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "184",
            "local_level_id"=> "184",
            "province_id"=> "4",
            "local_id"=> "3.289",
            "local_name"=> "पुतलीबजार नगरपालिका",
            "dist_id"=> "42",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "185",
            "local_level_id"=> "185",
            "province_id"=> "4",
            "local_id"=> "3.29",
            "local_name"=> "भीरकोट नगरपालिका",
            "dist_id"=> "42",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "186",
            "local_level_id"=> "186",
            "province_id"=> "4",
            "local_id"=> "3.291",
            "local_name"=> "वालिङ नगरपालिका",
            "dist_id"=> "42",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "187",
            "local_level_id"=> "187",
            "province_id"=> "4",
            "local_id"=> "3.292",
            "local_name"=> "भानु नगरपालिका",
            "dist_id"=> "43",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "188",
            "local_level_id"=> "188",
            "province_id"=> "4",
            "local_id"=> "3.293",
            "local_name"=> "भिमाद नगरपालिका",
            "dist_id"=> "43",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "189",
            "local_level_id"=> "189",
            "province_id"=> "4",
            "local_id"=> "3.294",
            "local_name"=> "व्यास नगरपालिका",
            "dist_id"=> "43",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "190",
            "local_level_id"=> "190",
            "province_id"=> "4",
            "local_id"=> "3.295",
            "local_name"=> "शुक्लागण्डकी नगरपालिका",
            "dist_id"=> "43",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "191",
            "local_level_id"=> "191",
            "province_id"=> "4",
            "local_id"=> "3.296",
            "local_name"=> "गोरखा नगरपालिका",
            "dist_id"=> "44",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "192",
            "local_level_id"=> "192",
            "province_id"=> "4",
            "local_id"=> "3.297",
            "local_name"=> "पालुङटार नगरपालिका",
            "dist_id"=> "44",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "193",
            "local_level_id"=> "193",
            "province_id"=> "4",
            "local_id"=> "3.298",
            "local_name"=> "बेसीशहर नगरपालिका",
            "dist_id"=> "46",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "194",
            "local_level_id"=> "194",
            "province_id"=> "4",
            "local_id"=> "3.299",
            "local_name"=> "मध्यनेपाल नगरपालिका",
            "dist_id"=> "46",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "195",
            "local_level_id"=> "195",
            "province_id"=> "4",
            "local_id"=> "3.3",
            "local_name"=> "राईनास नगरपालिका",
            "dist_id"=> "46",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "196",
            "local_level_id"=> "196",
            "province_id"=> "4",
            "local_id"=> "3.301",
            "local_name"=> "सुन्दरबजार नगरपालिका",
            "dist_id"=> "46",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "197",
            "local_level_id"=> "197",
            "province_id"=> "4",
            "local_id"=> "3.302",
            "local_name"=> "कुश्मा नगरपालिका",
            "dist_id"=> "48",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "198",
            "local_level_id"=> "198",
            "province_id"=> "4",
            "local_id"=> "3.303",
            "local_name"=> "फलेवास नगरपालिका",
            "dist_id"=> "48",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "199",
            "local_level_id"=> "199",
            "province_id"=> "4",
            "local_id"=> "3.304",
            "local_name"=> "गल्कोट नगरपालिका",
            "dist_id"=> "49",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "200",
            "local_level_id"=> "200",
            "province_id"=> "4",
            "local_id"=> "3.305",
            "local_name"=> "जैमिनी नगरपालिका",
            "dist_id"=> "49",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "201",
            "local_level_id"=> "201",
            "province_id"=> "4",
            "local_id"=> "3.306",
            "local_name"=> "ढोरपाटन नगरपालिका",
            "dist_id"=> "49",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "202",
            "local_level_id"=> "202",
            "province_id"=> "4",
            "local_id"=> "3.307",
            "local_name"=> "बाग्लुङ नगरपालिका",
            "dist_id"=> "49",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "203",
            "local_level_id"=> "203",
            "province_id"=> "4",
            "local_id"=> "3.308",
            "local_name"=> "बेनी नगरपालिका",
            "dist_id"=> "50",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "204",
            "local_level_id"=> "204",
            "province_id"=> "6",
            "local_id"=> "3.309",
            "local_name"=> "छायाँनाथ रारा नगरपालिका",
            "dist_id"=> "52",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "205",
            "local_level_id"=> "205",
            "province_id"=> "6",
            "local_id"=> "3.31",
            "local_name"=> "ठूलीभेरी नगरपालिका",
            "dist_id"=> "53",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "206",
            "local_level_id"=> "206",
            "province_id"=> "6",
            "local_id"=> "3.311",
            "local_name"=> "त्रिपुरासुन्दरी नगरपालिका",
            "dist_id"=> "53",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "207",
            "local_level_id"=> "207",
            "province_id"=> "6",
            "local_id"=> "3.312",
            "local_name"=> "चन्दननाथ नगरपालिका",
            "dist_id"=> "55",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "208",
            "local_level_id"=> "208",
            "province_id"=> "6",
            "local_id"=> "3.313",
            "local_name"=> "खाँडाचक्र नगरपालिका",
            "dist_id"=> "56",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "209",
            "local_level_id"=> "209",
            "province_id"=> "6",
            "local_id"=> "3.314",
            "local_name"=> "तिलागुफा नगरपालिका",
            "dist_id"=> "56",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "210",
            "local_level_id"=> "210",
            "province_id"=> "6",
            "local_id"=> "3.315",
            "local_name"=> "रास्कोट नगरपालिका",
            "dist_id"=> "56",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "211",
            "local_level_id"=> "211",
            "province_id"=> "6",
            "local_id"=> "3.316",
            "local_name"=> "आठबिसकोट नगरपालिका",
            "dist_id"=> "57",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "212",
            "local_level_id"=> "212",
            "province_id"=> "6",
            "local_id"=> "3.317",
            "local_name"=> "चौरजहारी नगरपालिका",
            "dist_id"=> "57",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "213",
            "local_level_id"=> "213",
            "province_id"=> "6",
            "local_id"=> "3.318",
            "local_name"=> "मुसिकोट नगरपालिका",
            "dist_id"=> "57",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "214",
            "local_level_id"=> "214",
            "province_id"=> "5",
            "local_id"=> "3.319",
            "local_name"=> "रोल्पा नगरपालिका",
            "dist_id"=> "58",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "215",
            "local_level_id"=> "215",
            "province_id"=> "5",
            "local_id"=> "3.32",
            "local_name"=> "प्यूठान नगरपालिका",
            "dist_id"=> "59",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "216",
            "local_level_id"=> "216",
            "province_id"=> "5",
            "local_id"=> "3.321",
            "local_name"=> "स्वर्गद्वारी नगरपालिका",
            "dist_id"=> "59",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "217",
            "local_level_id"=> "217",
            "province_id"=> "5",
            "local_id"=> "3.322",
            "local_name"=> "लमही नगरपालिका",
            "dist_id"=> "60",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "218",
            "local_level_id"=> "218",
            "province_id"=> "6",
            "local_id"=> "3.323",
            "local_name"=> "बागचौर नगरपालिका",
            "dist_id"=> "61",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "219",
            "local_level_id"=> "219",
            "province_id"=> "6",
            "local_id"=> "3.324",
            "local_name"=> "बनगाड कुपिण्डे नगरपालिका",
            "dist_id"=> "61",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "220",
            "local_level_id"=> "220",
            "province_id"=> "6",
            "local_id"=> "3.325",
            "local_name"=> "शारदा नगरपालिका",
            "dist_id"=> "61",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "221",
            "local_level_id"=> "221",
            "province_id"=> "5",
            "local_id"=> "3.326",
            "local_name"=> "कोहलपुर नगरपालिका",
            "dist_id"=> "62",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "222",
            "local_level_id"=> "222",
            "province_id"=> "5",
            "local_id"=> "3.327",
            "local_name"=> "गुलरिया नगरपालिका",
            "dist_id"=> "63",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "223",
            "local_level_id"=> "223",
            "province_id"=> "5",
            "local_id"=> "3.328",
            "local_name"=> "ठाकुरबाबा नगरपालिका",
            "dist_id"=> "63",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "224",
            "local_level_id"=> "224",
            "province_id"=> "5",
            "local_id"=> "3.329",
            "local_name"=> "बाँसगढी नगरपालिका",
            "dist_id"=> "63",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "225",
            "local_level_id"=> "225",
            "province_id"=> "5",
            "local_id"=> "3.33",
            "local_name"=> "मधुवन नगरपालिका",
            "dist_id"=> "63",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "226",
            "local_level_id"=> "226",
            "province_id"=> "5",
            "local_id"=> "3.331",
            "local_name"=> "राजापुर नगरपालिका",
            "dist_id"=> "63",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "227",
            "local_level_id"=> "227",
            "province_id"=> "5",
            "local_id"=> "3.332",
            "local_name"=> "बारबर्दिया नगरपालिका",
            "dist_id"=> "63",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "228",
            "local_level_id"=> "228",
            "province_id"=> "6",
            "local_id"=> "3.333",
            "local_name"=> "गुर्भाकोट नगरपालिका",
            "dist_id"=> "64",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "229",
            "local_level_id"=> "229",
            "province_id"=> "6",
            "local_id"=> "3.334",
            "local_name"=> "पञ्चपुरी नगरपालिका",
            "dist_id"=> "64",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "230",
            "local_level_id"=> "230",
            "province_id"=> "6",
            "local_id"=> "3.335",
            "local_name"=> "भेरीगंगा नगरपालिका",
            "dist_id"=> "64",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "231",
            "local_level_id"=> "231",
            "province_id"=> "6",
            "local_id"=> "3.336",
            "local_name"=> "लेकबेसी नगरपालिका",
            "dist_id"=> "64",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "232",
            "local_level_id"=> "232",
            "province_id"=> "6",
            "local_id"=> "3.337",
            "local_name"=> "वीरेन्द्रनगर नगरपालिका",
            "dist_id"=> "64",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "233",
            "local_level_id"=> "233",
            "province_id"=> "6",
            "local_id"=> "3.338",
            "local_name"=> "छेडागाड नगरपालिका",
            "dist_id"=> "65",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "234",
            "local_level_id"=> "234",
            "province_id"=> "6",
            "local_id"=> "3.339",
            "local_name"=> "नलगाड नगरपालिका",
            "dist_id"=> "65",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "235",
            "local_level_id"=> "235",
            "province_id"=> "6",
            "local_id"=> "3.34",
            "local_name"=> "भेरी नगरपालिका",
            "dist_id"=> "65",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "236",
            "local_level_id"=> "236",
            "province_id"=> "6",
            "local_id"=> "3.341",
            "local_name"=> "आठबीस नगरपालिका",
            "dist_id"=> "66",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "237",
            "local_level_id"=> "237",
            "province_id"=> "6",
            "local_id"=> "3.342",
            "local_name"=> "चामुण्डा बिन्द्रासैनी नगरपालिका",
            "dist_id"=> "66",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "238",
            "local_level_id"=> "238",
            "province_id"=> "6",
            "local_id"=> "3.343",
            "local_name"=> "दुल्लु नगरपालिका",
            "dist_id"=> "66",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "239",
            "local_level_id"=> "239",
            "province_id"=> "6",
            "local_id"=> "3.344",
            "local_name"=> "नारायण नगरपालिका",
            "dist_id"=> "66",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "240",
            "local_level_id"=> "240",
            "province_id"=> "7",
            "local_id"=> "3.345",
            "local_name"=> "गोदावरी नगरपालिका",
            "dist_id"=> "67",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "241",
            "local_level_id"=> "241",
            "province_id"=> "7",
            "local_id"=> "3.346",
            "local_name"=> "गौरीगङ्गा नगरपालिका",
            "dist_id"=> "67",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "242",
            "local_level_id"=> "242",
            "province_id"=> "7",
            "local_id"=> "3.347",
            "local_name"=> "घोडाघोडी नगरपालिका",
            "dist_id"=> "67",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "243",
            "local_level_id"=> "243",
            "province_id"=> "7",
            "local_id"=> "3.348",
            "local_name"=> "टिकापुर नगरपालिका",
            "dist_id"=> "67",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "244",
            "local_level_id"=> "244",
            "province_id"=> "7",
            "local_id"=> "3.349",
            "local_name"=> "भजनी नगरपालिका",
            "dist_id"=> "67",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "245",
            "local_level_id"=> "245",
            "province_id"=> "7",
            "local_id"=> "3.35",
            "local_name"=> "लम्किचुहा नगरपालिका",
            "dist_id"=> "67",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "246",
            "local_level_id"=> "246",
            "province_id"=> "7",
            "local_id"=> "3.351",
            "local_name"=> "दिपायल सिलगढी नगरपालिका",
            "dist_id"=> "68",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "247",
            "local_level_id"=> "247",
            "province_id"=> "7",
            "local_id"=> "3.352",
            "local_name"=> "शिखर नगरपालिका",
            "dist_id"=> "68",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "248",
            "local_level_id"=> "248",
            "province_id"=> "7",
            "local_id"=> "3.353",
            "local_name"=> "कमलबजार नगरपालिका",
            "dist_id"=> "69",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "249",
            "local_level_id"=> "249",
            "province_id"=> "7",
            "local_id"=> "3.354",
            "local_name"=> "पंचदेवल विनायक नगरपालिका",
            "dist_id"=> "69",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "250",
            "local_level_id"=> "250",
            "province_id"=> "7",
            "local_id"=> "3.355",
            "local_name"=> "मंगलसेन नगरपालिका",
            "dist_id"=> "69",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "251",
            "local_level_id"=> "251",
            "province_id"=> "7",
            "local_id"=> "3.356",
            "local_name"=> "साँफेबगर नगरपालिका",
            "dist_id"=> "69",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "252",
            "local_level_id"=> "252",
            "province_id"=> "7",
            "local_id"=> "3.357",
            "local_name"=> "त्रिवेणी नगरपालिका",
            "dist_id"=> "70",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "253",
            "local_level_id"=> "253",
            "province_id"=> "7",
            "local_id"=> "3.358",
            "local_name"=> "बडीमालिका नगरपालिका",
            "dist_id"=> "70",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "254",
            "local_level_id"=> "254",
            "province_id"=> "7",
            "local_id"=> "3.359",
            "local_name"=> "बुढीगंगा नगरपालिका",
            "dist_id"=> "70",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "255",
            "local_level_id"=> "255",
            "province_id"=> "7",
            "local_id"=> "3.36",
            "local_name"=> "बुढीनन्दा नगरपालिका",
            "dist_id"=> "70",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "256",
            "local_level_id"=> "256",
            "province_id"=> "7",
            "local_id"=> "3.361",
            "local_name"=> "जयपृथ्वी नगरपालिका",
            "dist_id"=> "71",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "257",
            "local_level_id"=> "257",
            "province_id"=> "7",
            "local_id"=> "3.362",
            "local_name"=> "बुङ्गल नगरपालिका",
            "dist_id"=> "71",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "258",
            "local_level_id"=> "258",
            "province_id"=> "7",
            "local_id"=> "3.363",
            "local_name"=> "महाकाली नगरपालिका",
            "dist_id"=> "72",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "259",
            "local_level_id"=> "259",
            "province_id"=> "7",
            "local_id"=> "3.364",
            "local_name"=> "शैल्यशिखर नगरपालिका",
            "dist_id"=> "72",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "260",
            "local_level_id"=> "260",
            "province_id"=> "7",
            "local_id"=> "3.365",
            "local_name"=> "दशरथचन्द नगरपालिका",
            "dist_id"=> "73",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "261",
            "local_level_id"=> "261",
            "province_id"=> "7",
            "local_id"=> "3.366",
            "local_name"=> "पाटन नगरपालिका",
            "dist_id"=> "73",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "262",
            "local_level_id"=> "262",
            "province_id"=> "7",
            "local_id"=> "3.367",
            "local_name"=> "पुर्चौडी नगरपालिका",
            "dist_id"=> "73",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "263",
            "local_level_id"=> "263",
            "province_id"=> "7",
            "local_id"=> "3.368",
            "local_name"=> "मेलौली नगरपालिका",
            "dist_id"=> "73",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "264",
            "local_level_id"=> "264",
            "province_id"=> "7",
            "local_id"=> "3.369",
            "local_name"=> "अमरगढी नगरपालिका",
            "dist_id"=> "74",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "265",
            "local_level_id"=> "265",
            "province_id"=> "7",
            "local_id"=> "3.37",
            "local_name"=> "परशुराम नगरपालिका",
            "dist_id"=> "74",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "266",
            "local_level_id"=> "266",
            "province_id"=> "7",
            "local_id"=> "3.371",
            "local_name"=> "कृष्णपुर नगरपालिका",
            "dist_id"=> "75",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "267",
            "local_level_id"=> "267",
            "province_id"=> "7",
            "local_id"=> "3.372",
            "local_name"=> "पुनर्वास नगरपालिका",
            "dist_id"=> "75",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "268",
            "local_level_id"=> "268",
            "province_id"=> "7",
            "local_id"=> "3.373",
            "local_name"=> "बेदकोट नगरपालिका",
            "dist_id"=> "75",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "269",
            "local_level_id"=> "269",
            "province_id"=> "7",
            "local_id"=> "3.374",
            "local_name"=> "बेलौरी नगरपालिका",
            "dist_id"=> "75",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "270",
            "local_level_id"=> "270",
            "province_id"=> "7",
            "local_id"=> "3.375",
            "local_name"=> "भिमदत्त नगरपालिका",
            "dist_id"=> "75",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "271",
            "local_level_id"=> "271",
            "province_id"=> "7",
            "local_id"=> "3.376",
            "local_name"=> "दोधारा चाँदनी नगरपालिका",
            "dist_id"=> "75",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "272",
            "local_level_id"=> "272",
            "province_id"=> "7",
            "local_id"=> "3.377",
            "local_name"=> "शुक्लाफाँटा नगरपालिका",
            "dist_id"=> "75",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "273",
            "local_level_id"=> "273",
            "province_id"=> "2",
            "local_id"=> "3.378",
            "local_name"=> "राजदेवी नगरपालिका",
            "dist_id"=> "32",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "274",
            "local_level_id"=> "274",
            "province_id"=> "1",
            "local_id"=> "3.401",
            "local_name"=> "आठराई त्रिवेणी गाउँपालिका",
            "dist_id"=> "1",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "275",
            "local_level_id"=> "275",
            "province_id"=> "1",
            "local_id"=> "3.402",
            "local_name"=> "फक्ताङलुङ गाउँपालिका",
            "dist_id"=> "1",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "276",
            "local_level_id"=> "276",
            "province_id"=> "1",
            "local_id"=> "3.403",
            "local_name"=> "मिक्वाखोला गाउँपालिका",
            "dist_id"=> "1",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "277",
            "local_level_id"=> "277",
            "province_id"=> "1",
            "local_id"=> "3.404",
            "local_name"=> "मेरिङदेन गाउँपालिका",
            "dist_id"=> "1",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "278",
            "local_level_id"=> "278",
            "province_id"=> "1",
            "local_id"=> "3.405",
            "local_name"=> "मैवाखोला गाउँपालिका",
            "dist_id"=> "1",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "279",
            "local_level_id"=> "279",
            "province_id"=> "1",
            "local_id"=> "3.406",
            "local_name"=> "पाथीभरा याङवरक गाउँपालिका",
            "dist_id"=> "1",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "280",
            "local_level_id"=> "280",
            "province_id"=> "1",
            "local_id"=> "3.407",
            "local_name"=> "सिदिङ्वा गाउँपालिका",
            "dist_id"=> "1",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "281",
            "local_level_id"=> "281",
            "province_id"=> "1",
            "local_id"=> "3.408",
            "local_name"=> "सिरीजङ्घा गाउँपालिका",
            "dist_id"=> "1",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "282",
            "local_level_id"=> "282",
            "province_id"=> "1",
            "local_id"=> "3.409",
            "local_name"=> "कुम्मायक गाउँपालिका",
            "dist_id"=> "2",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "283",
            "local_level_id"=> "283",
            "province_id"=> "1",
            "local_id"=> "3.41",
            "local_name"=> "तुम्वेवा गाउँपालिका",
            "dist_id"=> "2",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "284",
            "local_level_id"=> "284",
            "province_id"=> "1",
            "local_id"=> "3.411",
            "local_name"=> "फालेलुङ गाउँपालिका",
            "dist_id"=> "2",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "285",
            "local_level_id"=> "285",
            "province_id"=> "1",
            "local_id"=> "3.412",
            "local_name"=> "फाल्गुनन्द गाउँपालिका",
            "dist_id"=> "2",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "286",
            "local_level_id"=> "286",
            "province_id"=> "1",
            "local_id"=> "3.413",
            "local_name"=> "मिक्लाजुङ गाउँपालिका",
            "dist_id"=> "2",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "287",
            "local_level_id"=> "287",
            "province_id"=> "1",
            "local_id"=> "3.414",
            "local_name"=> "याङवरक गाउँपालिका",
            "dist_id"=> "2",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "288",
            "local_level_id"=> "288",
            "province_id"=> "1",
            "local_id"=> "3.415",
            "local_name"=> "हिलिहाङ गाउँपालिका",
            "dist_id"=> "2",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "289",
            "local_level_id"=> "289",
            "province_id"=> "1",
            "local_id"=> "3.416",
            "local_name"=> "चुलाचुली गाउँपालिका",
            "dist_id"=> "3",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "290",
            "local_level_id"=> "290",
            "province_id"=> "1",
            "local_id"=> "3.417",
            "local_name"=> "फाकफोकथुम गाउँपालिका",
            "dist_id"=> "3",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "291",
            "local_level_id"=> "291",
            "province_id"=> "1",
            "local_id"=> "3.418",
            "local_name"=> "माईजोगमाई गाउँपालिका",
            "dist_id"=> "3",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "292",
            "local_level_id"=> "292",
            "province_id"=> "1",
            "local_id"=> "3.419",
            "local_name"=> "माङसेबुङ गाउँपालिका",
            "dist_id"=> "3",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "293",
            "local_level_id"=> "293",
            "province_id"=> "1",
            "local_id"=> "3.42",
            "local_name"=> "रोङ गाउँपालिका",
            "dist_id"=> "3",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "294",
            "local_level_id"=> "294",
            "province_id"=> "1",
            "local_id"=> "3.421",
            "local_name"=> "सन्दकपुर गाउँपालिका",
            "dist_id"=> "3",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "295",
            "local_level_id"=> "295",
            "province_id"=> "1",
            "local_id"=> "3.422",
            "local_name"=> "कचनकवल गाउँपालिका",
            "dist_id"=> "4",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "296",
            "local_level_id"=> "296",
            "province_id"=> "1",
            "local_id"=> "3.423",
            "local_name"=> "कमल गाउँपालिका",
            "dist_id"=> "4",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "297",
            "local_level_id"=> "297",
            "province_id"=> "1",
            "local_id"=> "3.424",
            "local_name"=> "गौरिगंज गाउँपालिका",
            "dist_id"=> "4",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "298",
            "local_level_id"=> "298",
            "province_id"=> "1",
            "local_id"=> "3.425",
            "local_name"=> "झापा गाउँपालिका",
            "dist_id"=> "4",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "299",
            "local_level_id"=> "299",
            "province_id"=> "1",
            "local_id"=> "3.426",
            "local_name"=> "बाह्रदशी गाउँपालिका",
            "dist_id"=> "4",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "300",
            "local_level_id"=> "300",
            "province_id"=> "1",
            "local_id"=> "3.427",
            "local_name"=> "बुद्धशान्ति गाउँपालिका",
            "dist_id"=> "4",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "301",
            "local_level_id"=> "301",
            "province_id"=> "1",
            "local_id"=> "3.428",
            "local_name"=> "हल्दीबारी गाउँपालिका",
            "dist_id"=> "4",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "302",
            "local_level_id"=> "302",
            "province_id"=> "1",
            "local_id"=> "3.429",
            "local_name"=> "चिचिला गाउँपालिका",
            "dist_id"=> "5",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "303",
            "local_level_id"=> "303",
            "province_id"=> "1",
            "local_id"=> "3.43",
            "local_name"=> "भोटखोला गाउँपालिका",
            "dist_id"=> "5",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "304",
            "local_level_id"=> "304",
            "province_id"=> "1",
            "local_id"=> "3.431",
            "local_name"=> "मकालु गाउँपालिका",
            "dist_id"=> "5",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "305",
            "local_level_id"=> "305",
            "province_id"=> "1",
            "local_id"=> "3.432",
            "local_name"=> "सभापोखरी गाउँपालिका",
            "dist_id"=> "5",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "306",
            "local_level_id"=> "306",
            "province_id"=> "1",
            "local_id"=> "3.433",
            "local_name"=> "सिलीचोङ गाउँपालिका",
            "dist_id"=> "5",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "307",
            "local_level_id"=> "307",
            "province_id"=> "1",
            "local_id"=> "3.434",
            "local_name"=> "आठराई गाउँपालिका",
            "dist_id"=> "6",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "308",
            "local_level_id"=> "308",
            "province_id"=> "1",
            "local_id"=> "3.435",
            "local_name"=> "छथर गाउँपालिका",
            "dist_id"=> "6",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "309",
            "local_level_id"=> "309",
            "province_id"=> "1",
            "local_id"=> "3.436",
            "local_name"=> "फेदाप गाउँपालिका",
            "dist_id"=> "6",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "310",
            "local_level_id"=> "310",
            "province_id"=> "1",
            "local_id"=> "3.437",
            "local_name"=> "मेन्छयायेम गाउँपालिका",
            "dist_id"=> "6",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "311",
            "local_level_id"=> "311",
            "province_id"=> "1",
            "local_id"=> "3.438",
            "local_name"=> "अरुण गाउँपालिका",
            "dist_id"=> "7",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "312",
            "local_level_id"=> "312",
            "province_id"=> "1",
            "local_id"=> "3.439",
            "local_name"=> "आमचोक गाउँपालिका",
            "dist_id"=> "7",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "313",
            "local_level_id"=> "313",
            "province_id"=> "1",
            "local_id"=> "3.44",
            "local_name"=> "टेम्केमैयुङ गाउँपालिका",
            "dist_id"=> "7",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "314",
            "local_level_id"=> "314",
            "province_id"=> "1",
            "local_id"=> "3.441",
            "local_name"=> "पौवादुङमा गाउँपालिका",
            "dist_id"=> "7",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "315",
            "local_level_id"=> "315",
            "province_id"=> "1",
            "local_id"=> "3.442",
            "local_name"=> "रामप्रसादराई गाउँपालिका",
            "dist_id"=> "7",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "316",
            "local_level_id"=> "316",
            "province_id"=> "1",
            "local_id"=> "3.443",
            "local_name"=> "साल्पासिलिछो गाउँपालिका",
            "dist_id"=> "7",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "317",
            "local_level_id"=> "317",
            "province_id"=> "1",
            "local_id"=> "3.444",
            "local_name"=> "हतुवागढी गाउँपालिका",
            "dist_id"=> "7",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "318",
            "local_level_id"=> "318",
            "province_id"=> "1",
            "local_id"=> "3.445",
            "local_name"=> "सहिदभुमि गाउँपालिका",
            "dist_id"=> "8",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "319",
            "local_level_id"=> "319",
            "province_id"=> "1",
            "local_id"=> "3.446",
            "local_name"=> "चौबिसे गाउँपालिका",
            "dist_id"=> "8",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "320",
            "local_level_id"=> "320",
            "province_id"=> "1",
            "local_id"=> "3.447",
            "local_name"=> "छथर जोरपाटी गाउँपालिका",
            "dist_id"=> "8",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "321",
            "local_level_id"=> "321",
            "province_id"=> "1",
            "local_id"=> "3.448",
            "local_name"=> "साँगुरीगढी गाउँपालिका",
            "dist_id"=> "8",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "322",
            "local_level_id"=> "322",
            "province_id"=> "1",
            "local_id"=> "3.449",
            "local_name"=> "कोशी गाउँपालिका",
            "dist_id"=> "9",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "323",
            "local_level_id"=> "323",
            "province_id"=> "1",
            "local_id"=> "3.45",
            "local_name"=> "गढी गाउँपालिका",
            "dist_id"=> "9",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "324",
            "local_level_id"=> "324",
            "province_id"=> "1",
            "local_id"=> "3.451",
            "local_name"=> "देवानगञ्ज गाउँपालिका",
            "dist_id"=> "9",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "325",
            "local_level_id"=> "325",
            "province_id"=> "1",
            "local_id"=> "3.452",
            "local_name"=> "बर्जु गाउँपालिका",
            "dist_id"=> "9",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "326",
            "local_level_id"=> "326",
            "province_id"=> "1",
            "local_id"=> "3.453",
            "local_name"=> "भोक्राहा नरसिंह गाउँपालिका",
            "dist_id"=> "9",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "327",
            "local_level_id"=> "327",
            "province_id"=> "1",
            "local_id"=> "3.454",
            "local_name"=> "हरिनगर गाउँपालिका",
            "dist_id"=> "9",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "328",
            "local_level_id"=> "328",
            "province_id"=> "1",
            "local_id"=> "3.455",
            "local_name"=> "कटहरी गाउँपालिका",
            "dist_id"=> "10",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "329",
            "local_level_id"=> "329",
            "province_id"=> "1",
            "local_id"=> "3.456",
            "local_name"=> "कानेपोखरी गाउँपालिका",
            "dist_id"=> "10",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "330",
            "local_level_id"=> "330",
            "province_id"=> "1",
            "local_id"=> "3.457",
            "local_name"=> "केराबारी गाउँपालिका",
            "dist_id"=> "10",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "331",
            "local_level_id"=> "331",
            "province_id"=> "1",
            "local_id"=> "3.458",
            "local_name"=> "ग्रामथान गाउँपालिका",
            "dist_id"=> "10",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "332",
            "local_level_id"=> "332",
            "province_id"=> "1",
            "local_id"=> "3.459",
            "local_name"=> "जहदा गाउँपालिका",
            "dist_id"=> "10",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "333",
            "local_level_id"=> "333",
            "province_id"=> "1",
            "local_id"=> "3.46",
            "local_name"=> "धनपालथान गाउँपालिका",
            "dist_id"=> "10",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "334",
            "local_level_id"=> "334",
            "province_id"=> "1",
            "local_id"=> "3.461",
            "local_name"=> "बुढीगंगा गाउँपालिका",
            "dist_id"=> "10",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "335",
            "local_level_id"=> "335",
            "province_id"=> "1",
            "local_id"=> "3.462",
            "local_name"=> "मिक्लाजुङ्ग गाउँपालिका",
            "dist_id"=> "10",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "336",
            "local_level_id"=> "336",
            "province_id"=> "1",
            "local_id"=> "3.463",
            "local_name"=> "खुम्बु पासाङल्हामु गाउँपालिका",
            "dist_id"=> "11",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "337",
            "local_level_id"=> "337",
            "province_id"=> "1",
            "local_id"=> "3.464",
            "local_name"=> "माप्य दुधकोशी गाउँपालिका",
            "dist_id"=> "11",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "338",
            "local_level_id"=> "338",
            "province_id"=> "1",
            "local_id"=> "3.465",
            "local_name"=> "थुलुङ दुधकोशी गाउँपालिका",
            "dist_id"=> "11",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "339",
            "local_level_id"=> "339",
            "province_id"=> "1",
            "local_id"=> "3.466",
            "local_name"=> "नेचासल्यान गाउँपालिका",
            "dist_id"=> "11",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "340",
            "local_level_id"=> "340",
            "province_id"=> "1",
            "local_id"=> "3.467",
            "local_name"=> "माहाकुलुङ गाउँपालिका",
            "dist_id"=> "11",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "341",
            "local_level_id"=> "341",
            "province_id"=> "1",
            "local_id"=> "3.468",
            "local_name"=> "लिखु पिके गाउँपालिका",
            "dist_id"=> "11",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "342",
            "local_level_id"=> "342",
            "province_id"=> "1",
            "local_id"=> "3.469",
            "local_name"=> "सोताङ गाउँपालिका",
            "dist_id"=> "11",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "343",
            "local_level_id"=> "343",
            "province_id"=> "1",
            "local_id"=> "3.47",
            "local_name"=> "ऐसेलुखर्क गाउँपालिका",
            "dist_id"=> "12",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "344",
            "local_level_id"=> "344",
            "province_id"=> "1",
            "local_id"=> "3.471",
            "local_name"=> "केपिलासगढी गाउँपालिका",
            "dist_id"=> "12",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "345",
            "local_level_id"=> "345",
            "province_id"=> "1",
            "local_id"=> "3.472",
            "local_name"=> "खोटेहाङ गाउँपालिका",
            "dist_id"=> "12",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "346",
            "local_level_id"=> "346",
            "province_id"=> "1",
            "local_id"=> "3.473",
            "local_name"=> "जन्तेढुङ्गा गाउँपालिका",
            "dist_id"=> "12",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "347",
            "local_level_id"=> "347",
            "province_id"=> "1",
            "local_id"=> "3.474",
            "local_name"=> "दिप्रुङ चुइचुम्मा गाउँपालिका",
            "dist_id"=> "12",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "348",
            "local_level_id"=> "348",
            "province_id"=> "1",
            "local_id"=> "3.475",
            "local_name"=> "रावा वेसी गाउँपालिका",
            "dist_id"=> "12",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "349",
            "local_level_id"=> "349",
            "province_id"=> "1",
            "local_id"=> "3.476",
            "local_name"=> "वराहपोखरी गाउँपालिका",
            "dist_id"=> "12",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "350",
            "local_level_id"=> "350",
            "province_id"=> "1",
            "local_id"=> "3.477",
            "local_name"=> "साकेला गाउँपालिका",
            "dist_id"=> "12",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "351",
            "local_level_id"=> "351",
            "province_id"=> "1",
            "local_id"=> "3.478",
            "local_name"=> "उदयपुरगढी गाउँपालिका",
            "dist_id"=> "13",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "352",
            "local_level_id"=> "352",
            "province_id"=> "1",
            "local_id"=> "3.479",
            "local_name"=> "ताप्ली गाउँपालिका",
            "dist_id"=> "13",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "353",
            "local_level_id"=> "353",
            "province_id"=> "1",
            "local_id"=> "3.48",
            "local_name"=> "रौतामाई गाउँपालिका",
            "dist_id"=> "13",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "354",
            "local_level_id"=> "354",
            "province_id"=> "1",
            "local_id"=> "3.481",
            "local_name"=> "लिम्चुङबुङ गाउँपालिका",
            "dist_id"=> "13",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "355",
            "local_level_id"=> "355",
            "province_id"=> "1",
            "local_id"=> "3.482",
            "local_name"=> "खिजीदेम्वा गाउँपालिका",
            "dist_id"=> "14",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "356",
            "local_level_id"=> "356",
            "province_id"=> "1",
            "local_id"=> "3.483",
            "local_name"=> "चम्पादेवी गाउँपालिका",
            "dist_id"=> "14",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "357",
            "local_level_id"=> "357",
            "province_id"=> "1",
            "local_id"=> "3.484",
            "local_name"=> "चिशंखुगढी गाउँपालिका",
            "dist_id"=> "14",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "358",
            "local_level_id"=> "358",
            "province_id"=> "1",
            "local_id"=> "3.485",
            "local_name"=> "मानेभञ्ज्याङ गाउँपालिका",
            "dist_id"=> "14",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "359",
            "local_level_id"=> "359",
            "province_id"=> "1",
            "local_id"=> "3.486",
            "local_name"=> "मोलुङ गाउँपालिका",
            "dist_id"=> "14",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "360",
            "local_level_id"=> "360",
            "province_id"=> "1",
            "local_id"=> "3.487",
            "local_name"=> "लिखु गाउँपालिका",
            "dist_id"=> "14",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "361",
            "local_level_id"=> "361",
            "province_id"=> "1",
            "local_id"=> "3.488",
            "local_name"=> "सुनकोशी गाउँपालिका",
            "dist_id"=> "14",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "362",
            "local_level_id"=> "362",
            "province_id"=> "2",
            "local_id"=> "3.489",
            "local_name"=> "अग्नीसाइर कृष्णासवरन गाउँपालिका",
            "dist_id"=> "15",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "363",
            "local_level_id"=> "363",
            "province_id"=> "2",
            "local_id"=> "3.49",
            "local_name"=> "छिन्नमस्ता गाउँपालिका",
            "dist_id"=> "15",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "364",
            "local_level_id"=> "364",
            "province_id"=> "2",
            "local_id"=> "3.491",
            "local_name"=> "तिरहुत गाउँपालिका",
            "dist_id"=> "15",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "365",
            "local_level_id"=> "365",
            "province_id"=> "2",
            "local_id"=> "3.492",
            "local_name"=> "तिलाठी कोईलाडी गाउँपालिका ",
            "dist_id"=> "15",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "366",
            "local_level_id"=> "366",
            "province_id"=> "2",
            "local_id"=> "3.493",
            "local_name"=> "बिष्णुपुर गाउँपालिका",
            "dist_id"=> "15",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "367",
            "local_level_id"=> "367",
            "province_id"=> "2",
            "local_id"=> "3.494",
            "local_name"=> "राजगढ गाउँपालिका",
            "dist_id"=> "15",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "368",
            "local_level_id"=> "368",
            "province_id"=> "2",
            "local_id"=> "3.495",
            "local_name"=> "महादेवा गाउँपालिका",
            "dist_id"=> "15",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "369",
            "local_level_id"=> "369",
            "province_id"=> "2",
            "local_id"=> "3.496",
            "local_name"=> "रुपनी गाउँपालिका",
            "dist_id"=> "15",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "370",
            "local_level_id"=> "370",
            "province_id"=> "2",
            "local_id"=> "3.497",
            "local_name"=> "सप्तकोशी नगरपालिका",
            "dist_id"=> "15",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "371",
            "local_level_id"=> "371",
            "province_id"=> "2",
            "local_id"=> "3.498",
            "local_name"=> "अर्नमा गाउँपालिका",
            "dist_id"=> "16",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "372",
            "local_level_id"=> "372",
            "province_id"=> "2",
            "local_id"=> "3.499",
            "local_name"=> "औरही गाउँपालिका",
            "dist_id"=> "16",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "373",
            "local_level_id"=> "373",
            "province_id"=> "2",
            "local_id"=> "3.5",
            "local_name"=> "कर्जन्हा नगरपालिका",
            "dist_id"=> "16",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "374",
            "local_level_id"=> "374",
            "province_id"=> "2",
            "local_id"=> "3.501",
            "local_name"=> "नरहा गाउँपालिका",
            "dist_id"=> "16",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "375",
            "local_level_id"=> "375",
            "province_id"=> "2",
            "local_id"=> "3.502",
            "local_name"=> "नवराजपुर गाउँपालिका",
            "dist_id"=> "16",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "376",
            "local_level_id"=> "376",
            "province_id"=> "2",
            "local_id"=> "3.503",
            "local_name"=> "बरियारपट्टी गाउँपालिका",
            "dist_id"=> "16",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "377",
            "local_level_id"=> "377",
            "province_id"=> "2",
            "local_id"=> "3.504",
            "local_name"=> "भगवानपुर गाउँपालिका",
            "dist_id"=> "16",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "378",
            "local_level_id"=> "378",
            "province_id"=> "2",
            "local_id"=> "3.505",
            "local_name"=> "लक्ष्मीपुर पतारी गाउँपालिका",
            "dist_id"=> "16",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "379",
            "local_level_id"=> "379",
            "province_id"=> "2",
            "local_id"=> "3.506",
            "local_name"=> "विष्णुपुर गाउँपालिका",
            "dist_id"=> "16",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "380",
            "local_level_id"=> "380",
            "province_id"=> "2",
            "local_id"=> "3.507",
            "local_name"=> "सखुवानन्कारकट्टी गाउँपालिका",
            "dist_id"=> "16",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "381",
            "local_level_id"=> "381",
            "province_id"=> "2",
            "local_id"=> "3.508",
            "local_name"=> "औरही गाउँपालिका",
            "dist_id"=> "17",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "382",
            "local_level_id"=> "382",
            "province_id"=> "2",
            "local_id"=> "3.509",
            "local_name"=> "कमला नगरपालिका",
            "dist_id"=> "17",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "383",
            "local_level_id"=> "383",
            "province_id"=> "2",
            "local_id"=> "3.51",
            "local_name"=> "जनकनन्दिनी गाउँपालिका",
            "dist_id"=> "17",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "384",
            "local_level_id"=> "384",
            "province_id"=> "2",
            "local_id"=> "3.511",
            "local_name"=> "बटेश्वर गाउँपालिका",
            "dist_id"=> "17",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "385",
            "local_level_id"=> "385",
            "province_id"=> "2",
            "local_id"=> "3.512",
            "local_name"=> "मिथिला विहारी नगरपालिका",
            "dist_id"=> "17",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "386",
            "local_level_id"=> "386",
            "province_id"=> "2",
            "local_id"=> "3.513",
            "local_name"=> "मुखियापट्टी मुसहरनिया गाउँपालिका",
            "dist_id"=> "17",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "387",
            "local_level_id"=> "387",
            "province_id"=> "2",
            "local_id"=> "3.514",
            "local_name"=> "लक्ष्मीनिया गाउँपालिका",
            "dist_id"=> "17",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "388",
            "local_level_id"=> "388",
            "province_id"=> "2",
            "local_id"=> "3.515",
            "local_name"=> "हंसपुर नगरपालिका",
            "dist_id"=> "17",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "389",
            "local_level_id"=> "389",
            "province_id"=> "2",
            "local_id"=> "3.516",
            "local_name"=> "एकडारा गाउँपालिका",
            "dist_id"=> "18",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "390",
            "local_level_id"=> "390",
            "province_id"=> "2",
            "local_id"=> "3.517",
            "local_name"=> "औरही नगरपालिका",
            "dist_id"=> "18",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "391",
            "local_level_id"=> "391",
            "province_id"=> "2",
            "local_id"=> "3.518",
            "local_name"=> "पिपरा गाउँपालिका",
            "dist_id"=> "18",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "392",
            "local_level_id"=> "392",
            "province_id"=> "2",
            "local_id"=> "3.519",
            "local_name"=> "बलवा नगरपालिका",
            "dist_id"=> "18",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "393",
            "local_level_id"=> "393",
            "province_id"=> "2",
            "local_id"=> "3.52",
            "local_name"=> "भँगाहा नगरपालिका",
            "dist_id"=> "18",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "394",
            "local_level_id"=> "394",
            "province_id"=> "2",
            "local_id"=> "3.521",
            "local_name"=> "मटिहानी नगरपालिका",
            "dist_id"=> "18",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "395",
            "local_level_id"=> "395",
            "province_id"=> "2",
            "local_id"=> "3.522",
            "local_name"=> "मनरा सिसवा नगरपालिका",
            "dist_id"=> "18",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "396",
            "local_level_id"=> "396",
            "province_id"=> "2",
            "local_id"=> "3.523",
            "local_name"=> "महोत्तरी गाउँपालिका",
            "dist_id"=> "18",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "397",
            "local_level_id"=> "397",
            "province_id"=> "2",
            "local_id"=> "3.524",
            "local_name"=> "रामगोपालपुर नगरपालिका",
            "dist_id"=> "18",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "398",
            "local_level_id"=> "398",
            "province_id"=> "2",
            "local_id"=> "3.525",
            "local_name"=> "लोहरपट्टी नगरपालिका",
            "dist_id"=> "18",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "399",
            "local_level_id"=> "399",
            "province_id"=> "2",
            "local_id"=> "3.526",
            "local_name"=> "साम्सी गाउँपालिका",
            "dist_id"=> "18",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "400",
            "local_level_id"=> "400",
            "province_id"=> "2",
            "local_id"=> "3.527",
            "local_name"=> "सोनमा गाउँपालिका",
            "dist_id"=> "18",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "401",
            "local_level_id"=> "401",
            "province_id"=> "2",
            "local_id"=> "3.528",
            "local_name"=> "कबिलासी नगरपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "402",
            "local_level_id"=> "402",
            "province_id"=> "2",
            "local_id"=> "3.529",
            "local_name"=> "चक्रघट्टा गाउँपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "403",
            "local_level_id"=> "403",
            "province_id"=> "2",
            "local_id"=> "3.53",
            "local_name"=> "चन्द्रनगर गाउँपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "404",
            "local_level_id"=> "404",
            "province_id"=> "2",
            "local_id"=> "3.531",
            "local_name"=> "धनकौल गाउँपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "405",
            "local_level_id"=> "405",
            "province_id"=> "2",
            "local_id"=> "3.532",
            "local_name"=> "ब्रह्मपुरी गाउँपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "406",
            "local_level_id"=> "406",
            "province_id"=> "2",
            "local_id"=> "3.533",
            "local_name"=> "रामनगर गाउँपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "407",
            "local_level_id"=> "407",
            "province_id"=> "2",
            "local_id"=> "3.534",
            "local_name"=> "विष्णु गाउँपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "408",
            "local_level_id"=> "408",
            "province_id"=> "3",
            "local_id"=> "3.535",
            "local_name"=> "गोलन्जोर गाउँपालिका",
            "dist_id"=> "20",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "409",
            "local_level_id"=> "409",
            "province_id"=> "3",
            "local_id"=> "3.536",
            "local_name"=> "घ्याङलेख गाउँपालिका",
            "dist_id"=> "20",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "410",
            "local_level_id"=> "410",
            "province_id"=> "3",
            "local_id"=> "3.537",
            "local_name"=> "तीनपाटन गाउँपालिका",
            "dist_id"=> "20",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "411",
            "local_level_id"=> "411",
            "province_id"=> "3",
            "local_id"=> "3.538",
            "local_name"=> "फिक्कल गाउँपालिका",
            "dist_id"=> "20",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "412",
            "local_level_id"=> "412",
            "province_id"=> "3",
            "local_id"=> "3.539",
            "local_name"=> "मरिण गाउँपालिका",
            "dist_id"=> "20",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "413",
            "local_level_id"=> "413",
            "province_id"=> "3",
            "local_id"=> "3.54",
            "local_name"=> "सुनकोशी गाउँपालिका",
            "dist_id"=> "20",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "414",
            "local_level_id"=> "414",
            "province_id"=> "3",
            "local_id"=> "3.541",
            "local_name"=> "हरिहरपुरगढी गाउँपालिका",
            "dist_id"=> "20",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "415",
            "local_level_id"=> "415",
            "province_id"=> "3",
            "local_id"=> "3.542",
            "local_name"=> "उमाकुण्ड गाउँपालिका",
            "dist_id"=> "21",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "416",
            "local_level_id"=> "416",
            "province_id"=> "3",
            "local_id"=> "3.543",
            "local_name"=> "खाँडादेवी गाउँपालिका",
            "dist_id"=> "21",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "417",
            "local_level_id"=> "417",
            "province_id"=> "3",
            "local_id"=> "3.544",
            "local_name"=> "गोकुलगङ्गा गाउँपालिका",
            "dist_id"=> "21",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "418",
            "local_level_id"=> "418",
            "province_id"=> "3",
            "local_id"=> "3.545",
            "local_name"=> "दोरम्बा शैलुङ गाउँपालिका",
            "dist_id"=> "21",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2021-05-27"
       ],
        [
            "id"=> "419",
            "local_level_id"=> "419",
            "province_id"=> "3",
            "local_id"=> "3.546",
            "local_name"=> "लिखु तामाकोशी गाउँपालिका",
            "dist_id"=> "21",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "420",
            "local_level_id"=> "420",
            "province_id"=> "3",
            "local_id"=> "3.547",
            "local_name"=> "सुनापति गाउँपालिका",
            "dist_id"=> "21",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "421",
            "local_level_id"=> "421",
            "province_id"=> "3",
            "local_id"=> "3.548",
            "local_name"=> "कालिन्चोक गाउँपालिका",
            "dist_id"=> "22",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "422",
            "local_level_id"=> "422",
            "province_id"=> "3",
            "local_id"=> "3.549",
            "local_name"=> "गौरीशङ्कर गाउँपालिका",
            "dist_id"=> "22",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "423",
            "local_level_id"=> "423",
            "province_id"=> "3",
            "local_id"=> "3.55",
            "local_name"=> "तामाकोशी गाउँपालिका",
            "dist_id"=> "22",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "424",
            "local_level_id"=> "424",
            "province_id"=> "3",
            "local_id"=> "3.551",
            "local_name"=> "वैतेश्वर गाउँपालिका",
            "dist_id"=> "22",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "425",
            "local_level_id"=> "425",
            "province_id"=> "3",
            "local_id"=> "3.552",
            "local_name"=> "मेलुङ्ग गाउँपालिका",
            "dist_id"=> "22",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "426",
            "local_level_id"=> "426",
            "province_id"=> "3",
            "local_id"=> "3.553",
            "local_name"=> "विगु गाउँपालिका",
            "dist_id"=> "22",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "427",
            "local_level_id"=> "427",
            "province_id"=> "3",
            "local_id"=> "3.554",
            "local_name"=> "शैलुङ्ग गाउँपालिका",
            "dist_id"=> "22",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "428",
            "local_level_id"=> "428",
            "province_id"=> "3",
            "local_id"=> "3.555",
            "local_name"=> "ईन्द्रावती गाउँपालिका",
            "dist_id"=> "23",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "429",
            "local_level_id"=> "429",
            "province_id"=> "3",
            "local_id"=> "3.556",
            "local_name"=> "जुगल गाउँपालिका",
            "dist_id"=> "23",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "430",
            "local_level_id"=> "430",
            "province_id"=> "3",
            "local_id"=> "3.557",
            "local_name"=> "त्रिपुरासुन्दरी गाउँपालिका",
            "dist_id"=> "23",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "431",
            "local_level_id"=> "431",
            "province_id"=> "3",
            "local_id"=> "3.558",
            "local_name"=> "पाँचपोखरी थाङपाल गाउँपालिका",
            "dist_id"=> "23",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "432",
            "local_level_id"=> "432",
            "province_id"=> "3",
            "local_id"=> "3.559",
            "local_name"=> "बलेफी गाउँपालिका",
            "dist_id"=> "23",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "433",
            "local_level_id"=> "433",
            "province_id"=> "3",
            "local_id"=> "3.56",
            "local_name"=> "भोटेकोशी गाउँपालिका",
            "dist_id"=> "23",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "434",
            "local_level_id"=> "434",
            "province_id"=> "3",
            "local_id"=> "3.561",
            "local_name"=> "लिसंखु पाखर गाउँपालिका",
            "dist_id"=> "23",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "435",
            "local_level_id"=> "435",
            "province_id"=> "3",
            "local_id"=> "3.562",
            "local_name"=> "सुनकोशी गाउँपालिका",
            "dist_id"=> "23",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "436",
            "local_level_id"=> "436",
            "province_id"=> "3",
            "local_id"=> "3.563",
            "local_name"=> "हेलम्बु गाउँपालिका",
            "dist_id"=> "23",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "437",
            "local_level_id"=> "437",
            "province_id"=> "3",
            "local_id"=> "3.564",
            "local_name"=> "उत्तरगया गाउँपालिका",
            "dist_id"=> "24",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "438",
            "local_level_id"=> "438",
            "province_id"=> "3",
            "local_id"=> "3.565",
            "local_name"=> "कालिका गाउँपालिका",
            "dist_id"=> "24",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "439",
            "local_level_id"=> "439",
            "province_id"=> "3",
            "local_id"=> "3.566",
            "local_name"=> "गोसाईंकुण्ड गाउँपालिका",
            "dist_id"=> "24",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "440",
            "local_level_id"=> "440",
            "province_id"=> "3",
            "local_id"=> "3.567",
            "local_name"=> "नौकुण्ड गाउँपालिका",
            "dist_id"=> "24",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "441",
            "local_level_id"=> "441",
            "province_id"=> "3",
            "local_id"=> "3.568",
            "local_name"=> "आमाछोदिङमो गाउँपालिका",
            "dist_id"=> "24",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "442",
            "local_level_id"=> "442",
            "province_id"=> "3",
            "local_id"=> "3.569",
            "local_name"=> "खनियाबास गाउँपालिका",
            "dist_id"=> "25",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "443",
            "local_level_id"=> "443",
            "province_id"=> "3",
            "local_id"=> "3.57",
            "local_name"=> "गङ्गाजमुना गाउँपालिका",
            "dist_id"=> "25",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "444",
            "local_level_id"=> "444",
            "province_id"=> "3",
            "local_id"=> "3.571",
            "local_name"=> "गजुरी गाउँपालिका",
            "dist_id"=> "25",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "445",
            "local_level_id"=> "445",
            "province_id"=> "3",
            "local_id"=> "3.572",
            "local_name"=> "गल्छी गाउँपालिका",
            "dist_id"=> "25",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "446",
            "local_level_id"=> "446",
            "province_id"=> "3",
            "local_id"=> "3.573",
            "local_name"=> "ज्वालामूखी गाउँपालिका",
            "dist_id"=> "25",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "447",
            "local_level_id"=> "447",
            "province_id"=> "3",
            "local_id"=> "3.574",
            "local_name"=> "त्रिपुरासुन्दरी गाउँपालिका",
            "dist_id"=> "25",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "448",
            "local_level_id"=> "448",
            "province_id"=> "3",
            "local_id"=> "3.575",
            "local_name"=> "थाक्रे गाउँपालिका",
            "dist_id"=> "25",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "449",
            "local_level_id"=> "449",
            "province_id"=> "3",
            "local_id"=> "3.576",
            "local_name"=> "नेत्रावती डबजोङ गाउँपालिका",
            "dist_id"=> "25",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "450",
            "local_level_id"=> "450",
            "province_id"=> "3",
            "local_id"=> "3.577",
            "local_name"=> "बेनीघाट रोराङ्ग गाउँपालिका",
            "dist_id"=> "25",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "451",
            "local_level_id"=> "451",
            "province_id"=> "3",
            "local_id"=> "3.578",
            "local_name"=> "रुवी भ्याली गाउँपालिका",
            "dist_id"=> "25",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "452",
            "local_level_id"=> "452",
            "province_id"=> "3",
            "local_id"=> "3.579",
            "local_name"=> "सिद्धलेक गाउँपालिका",
            "dist_id"=> "25",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "453",
            "local_level_id"=> "453",
            "province_id"=> "3",
            "local_id"=> "3.58",
            "local_name"=> "ककनी गाउँपालिका",
            "dist_id"=> "26",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "454",
            "local_level_id"=> "454",
            "province_id"=> "3",
            "local_id"=> "3.581",
            "local_name"=> "किस्पाङ गाउँपालिका",
            "dist_id"=> "26",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "455",
            "local_level_id"=> "455",
            "province_id"=> "3",
            "local_id"=> "3.582",
            "local_name"=> "तादी गाउँपालिका",
            "dist_id"=> "26",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "456",
            "local_level_id"=> "456",
            "province_id"=> "3",
            "local_id"=> "3.583",
            "local_name"=> "तारकेश्वर गाउँपालिका",
            "dist_id"=> "26",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "457",
            "local_level_id"=> "457",
            "province_id"=> "3",
            "local_id"=> "3.584",
            "local_name"=> "दुप्चेश्वर गाउँपालिका",
            "dist_id"=> "26",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "458",
            "local_level_id"=> "458",
            "province_id"=> "3",
            "local_id"=> "3.585",
            "local_name"=> "पञ्चकन्या गाउँपालिका",
            "dist_id"=> "26",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "459",
            "local_level_id"=> "459",
            "province_id"=> "3",
            "local_id"=> "3.586",
            "local_name"=> "म्यागङ गाउँपालिका",
            "dist_id"=> "26",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "460",
            "local_level_id"=> "460",
            "province_id"=> "3",
            "local_id"=> "3.587",
            "local_name"=> "लिखु गाउँपालिका",
            "dist_id"=> "26",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "461",
            "local_level_id"=> "461",
            "province_id"=> "3",
            "local_id"=> "3.588",
            "local_name"=> "शिवपुरी गाउँपालिका",
            "dist_id"=> "26",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "462",
            "local_level_id"=> "462",
            "province_id"=> "3",
            "local_id"=> "3.589",
            "local_name"=> "सुर्यगढी गाउँपालिका",
            "dist_id"=> "26",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "463",
            "local_level_id"=> "463",
            "province_id"=> "3",
            "local_id"=> "3.59",
            "local_name"=> "कोन्ज्योसोम गाउँपालिका",
            "dist_id"=> "28",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "464",
            "local_level_id"=> "464",
            "province_id"=> "3",
            "local_id"=> "3.591",
            "local_name"=> "बागमती गाउँपालिका",
            "dist_id"=> "28",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "465",
            "local_level_id"=> "465",
            "province_id"=> "3",
            "local_id"=> "3.592",
            "local_name"=> "महाङ्काल गाउँपालिका",
            "dist_id"=> "28",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "466",
            "local_level_id"=> "466",
            "province_id"=> "3",
            "local_id"=> "3.593",
            "local_name"=> "खानीखोला गाउँपालिका",
            "dist_id"=> "30",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "467",
            "local_level_id"=> "467",
            "province_id"=> "3",
            "local_id"=> "3.594",
            "local_name"=> "चौंरीदेउराली गाउँपालिका",
            "dist_id"=> "30",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "468",
            "local_level_id"=> "468",
            "province_id"=> "3",
            "local_id"=> "3.595",
            "local_name"=> "तेमाल गाउँपालिका",
            "dist_id"=> "30",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "469",
            "local_level_id"=> "469",
            "province_id"=> "3",
            "local_id"=> "3.596",
            "local_name"=> "बेथानचोक गाउँपालिका",
            "dist_id"=> "30",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "470",
            "local_level_id"=> "470",
            "province_id"=> "3",
            "local_id"=> "3.597",
            "local_name"=> "भुम्लु गाउँपालिका",
            "dist_id"=> "30",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "471",
            "local_level_id"=> "471",
            "province_id"=> "3",
            "local_id"=> "3.598",
            "local_name"=> "महाभारत गाउँपालिका",
            "dist_id"=> "30",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "472",
            "local_level_id"=> "472",
            "province_id"=> "3",
            "local_id"=> "3.599",
            "local_name"=> "रोशी गाउँपालिका",
            "dist_id"=> "30",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "473",
            "local_level_id"=> "473",
            "province_id"=> "3",
            "local_id"=> "3.6",
            "local_name"=> "ईन्द्रसरोवर गाउँपालिका",
            "dist_id"=> "31",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "474",
            "local_level_id"=> "474",
            "province_id"=> "3",
            "local_id"=> "3.601",
            "local_name"=> "कैलाश गाउँपालिका",
            "dist_id"=> "31",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "475",
            "local_level_id"=> "475",
            "province_id"=> "3",
            "local_id"=> "3.602",
            "local_name"=> "बकैया गाउँपालिका",
            "dist_id"=> "31",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "476",
            "local_level_id"=> "476",
            "province_id"=> "3",
            "local_id"=> "3.603",
            "local_name"=> "बाग्मती गाउँपालिका",
            "dist_id"=> "31",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "477",
            "local_level_id"=> "477",
            "province_id"=> "3",
            "local_id"=> "3.604",
            "local_name"=> "भीमफेदी गाउँपालिका",
            "dist_id"=> "31",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "478",
            "local_level_id"=> "478",
            "province_id"=> "3",
            "local_id"=> "3.605",
            "local_name"=> "मकवानपुरगढी गाउँपालिका",
            "dist_id"=> "31",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "479",
            "local_level_id"=> "479",
            "province_id"=> "3",
            "local_id"=> "3.606",
            "local_name"=> "मनहरी गाउँपालिका",
            "dist_id"=> "31",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "480",
            "local_level_id"=> "480",
            "province_id"=> "3",
            "local_id"=> "3.607",
            "local_name"=> "राक्सिराङ्ग गाउँपालिका",
            "dist_id"=> "31",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "481",
            "local_level_id"=> "481",
            "province_id"=> "2",
            "local_id"=> "3.608",
            "local_name"=> "ईशनाथ नगरपालिका",
            "dist_id"=> "32",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "482",
            "local_level_id"=> "482",
            "province_id"=> "2",
            "local_id"=> "3.609",
            "local_name"=> "कटहरीया नगरपालिका",
            "dist_id"=> "32",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "483",
            "local_level_id"=> "483",
            "province_id"=> "2",
            "local_id"=> "3.61",
            "local_name"=> "गढीमाई नगरपालिका",
            "dist_id"=> "32",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "484",
            "local_level_id"=> "484",
            "province_id"=> "2",
            "local_id"=> "3.611",
            "local_name"=> "गजुरा नगरपालिका",
            "dist_id"=> "32",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "485",
            "local_level_id"=> "485",
            "province_id"=> "2",
            "local_id"=> "3.612",
            "local_name"=> "दुर्गाभगवती गाउँपालिका",
            "dist_id"=> "32",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "486",
            "local_level_id"=> "486",
            "province_id"=> "2",
            "local_id"=> "3.613",
            "local_name"=> "देवाही गोनाही नगरपालिका",
            "dist_id"=> "32",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "487",
            "local_level_id"=> "487",
            "province_id"=> "2",
            "local_id"=> "3.614",
            "local_name"=> "परोहा नगरपालिका",
            "dist_id"=> "32",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "488",
            "local_level_id"=> "488",
            "province_id"=> "2",
            "local_id"=> "3.615",
            "local_name"=> "फतुवा विजयपुर नगरपालिका",
            "dist_id"=> "32",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "489",
            "local_level_id"=> "489",
            "province_id"=> "2",
            "local_id"=> "3.616",
            "local_name"=> "बौधीमाई नगरपालिका",
            "dist_id"=> "32",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "490",
            "local_level_id"=> "490",
            "province_id"=> "2",
            "local_id"=> "3.617",
            "local_name"=> "माधवनारायण नगरपालिका",
            "dist_id"=> "32",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "491",
            "local_level_id"=> "491",
            "province_id"=> "2",
            "local_id"=> "3.618",
            "local_name"=> "मौलापुर नगरपालिका",
            "dist_id"=> "32",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "492",
            "local_level_id"=> "492",
            "province_id"=> "2",
            "local_id"=> "3.619",
            "local_name"=> "राजपुर नगरपालिका",
            "dist_id"=> "32",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "493",
            "local_level_id"=> "493",
            "province_id"=> "2",
            "local_id"=> "3.62",
            "local_name"=> "वृन्दावन नगरपालिका",
            "dist_id"=> "32",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "494",
            "local_level_id"=> "494",
            "province_id"=> "2",
            "local_id"=> "3.621",
            "local_name"=> "आदर्श कोतवाल गाउँपालिका",
            "dist_id"=> "33",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "495",
            "local_level_id"=> "495",
            "province_id"=> "2",
            "local_id"=> "3.622",
            "local_name"=> "करैयामाई गाउँपालिका",
            "dist_id"=> "33",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "496",
            "local_level_id"=> "496",
            "province_id"=> "2",
            "local_id"=> "3.623",
            "local_name"=> "देवताल गाउँपालिका",
            "dist_id"=> "33",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "497",
            "local_level_id"=> "497",
            "province_id"=> "2",
            "local_id"=> "3.624",
            "local_name"=> "पचरौता नगरपालिका",
            "dist_id"=> "33",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "498",
            "local_level_id"=> "498",
            "province_id"=> "2",
            "local_id"=> "3.625",
            "local_name"=> "परवानीपुर गाउँपालिका",
            "dist_id"=> "33",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "499",
            "local_level_id"=> "499",
            "province_id"=> "2",
            "local_id"=> "3.626",
            "local_name"=> "प्रसौनी गाउँपालिका",
            "dist_id"=> "33",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "500",
            "local_level_id"=> "500",
            "province_id"=> "2",
            "local_id"=> "3.627",
            "local_name"=> "फेटा गाउँपालिका",
            "dist_id"=> "33",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "501",
            "local_level_id"=> "501",
            "province_id"=> "2",
            "local_id"=> "3.628",
            "local_name"=> "बारागढी गाउँपालिका",
            "dist_id"=> "33",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "502",
            "local_level_id"=> "502",
            "province_id"=> "2",
            "local_id"=> "3.629",
            "local_name"=> "सुवर्ण गाउँपालिका",
            "dist_id"=> "33",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "503",
            "local_level_id"=> "503",
            "province_id"=> "2",
            "local_id"=> "3.63",
            "local_name"=> "छिपहरमाई गाउँपालिका",
            "dist_id"=> "34",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "504",
            "local_level_id"=> "504",
            "province_id"=> "2",
            "local_id"=> "3.631",
            "local_name"=> "जगरनाथपुर गाउँपालिका",
            "dist_id"=> "34",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "505",
            "local_level_id"=> "505",
            "province_id"=> "2",
            "local_id"=> "3.632",
            "local_name"=> "धोबीनी गाउँपालिका",
            "dist_id"=> "34",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "506",
            "local_level_id"=> "506",
            "province_id"=> "2",
            "local_id"=> "3.633",
            "local_name"=> "पकाहा मैनपुर गाउँपालिका",
            "dist_id"=> "34",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "507",
            "local_level_id"=> "507",
            "province_id"=> "2",
            "local_id"=> "3.634",
            "local_name"=> "पटेर्वा सुगौली गाउँपालिका",
            "dist_id"=> "34",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "508",
            "local_level_id"=> "508",
            "province_id"=> "2",
            "local_id"=> "3.635",
            "local_name"=> "पर्सागढी नगरपालिका",
            "dist_id"=> "34",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "509",
            "local_level_id"=> "509",
            "province_id"=> "2",
            "local_id"=> "3.636",
            "local_name"=> "बहुदरमाई नगरपालिका",
            "dist_id"=> "34",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "510",
            "local_level_id"=> "510",
            "province_id"=> "2",
            "local_id"=> "3.638",
            "local_name"=> "बिन्दबासिनी गाउँपालिका",
            "dist_id"=> "34",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "511",
            "local_level_id"=> "511",
            "province_id"=> "2",
            "local_id"=> "3.639",
            "local_name"=> "सखुवा प्रसौनी गाउँपालिका",
            "dist_id"=> "34",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "512",
            "local_level_id"=> "512",
            "province_id"=> "2",
            "local_id"=> "3.64",
            "local_name"=> "ठोरी गाउँपालिका",
            "dist_id"=> "34",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "513",
            "local_level_id"=> "513",
            "province_id"=> "3",
            "local_id"=> "3.641",
            "local_name"=> "इच्छाकामना गाउँपालिका",
            "dist_id"=> "35",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "514",
            "local_level_id"=> "514",
            "province_id"=> "5",
            "local_id"=> "3.642",
            "local_name"=> "सुस्ता गाउँपालिका",
            "dist_id"=> "36",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "515",
            "local_level_id"=> "515",
            "province_id"=> "5",
            "local_id"=> "3.643",
            "local_name"=> "पाल्हीनन्दन गाउँपालिका",
            "dist_id"=> "36",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "516",
            "local_level_id"=> "516",
            "province_id"=> "5",
            "local_id"=> "3.644",
            "local_name"=> "प्रतापपुर गाउँपालिका",
            "dist_id"=> "36",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "517",
            "local_level_id"=> "517",
            "province_id"=> "4",
            "local_id"=> "3.645",
            "local_name"=> "बौदीकाली गाउँपालिका",
            "dist_id"=> "76",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "518",
            "local_level_id"=> "518",
            "province_id"=> "4",
            "local_id"=> "3.646",
            "local_name"=> "बुलिङटार गाउँपालिका",
            "dist_id"=> "76",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "519",
            "local_level_id"=> "519",
            "province_id"=> "4",
            "local_id"=> "3.647",
            "local_name"=> "विनयी त्रिवेणी गाउँपालिका",
            "dist_id"=> "76",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "520",
            "local_level_id"=> "520",
            "province_id"=> "5",
            "local_id"=> "3.648",
            "local_name"=> "सरावल गाउँपालिका",
            "dist_id"=> "36",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "521",
            "local_level_id"=> "521",
            "province_id"=> "4",
            "local_id"=> "3.649",
            "local_name"=> "हुप्सेकोट गाउँपालिका",
            "dist_id"=> "76",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "522",
            "local_level_id"=> "522",
            "province_id"=> "5",
            "local_id"=> "3.65",
            "local_name"=> "ओमसतिया गाउँपालिका",
            "dist_id"=> "37",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "523",
            "local_level_id"=> "523",
            "province_id"=> "5",
            "local_id"=> "3.651",
            "local_name"=> "कञ्चन गाउँपालिका",
            "dist_id"=> "37",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "524",
            "local_level_id"=> "524",
            "province_id"=> "5",
            "local_id"=> "3.652",
            "local_name"=> "कोटहीमाई गाउँपालिका",
            "dist_id"=> "37",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "525",
            "local_level_id"=> "525",
            "province_id"=> "5",
            "local_id"=> "3.653",
            "local_name"=> "गैडहवा गाउँपालिका",
            "dist_id"=> "37",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "526",
            "local_level_id"=> "526",
            "province_id"=> "5",
            "local_id"=> "3.654",
            "local_name"=> "मर्चवारी गाउँपालिका",
            "dist_id"=> "37",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "527",
            "local_level_id"=> "527",
            "province_id"=> "5",
            "local_id"=> "3.655",
            "local_name"=> "मायादेवी गाउँपालिका",
            "dist_id"=> "37",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "528",
            "local_level_id"=> "528",
            "province_id"=> "5",
            "local_id"=> "3.656",
            "local_name"=> "रोहिणी गाउँपालिका",
            "dist_id"=> "37",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "529",
            "local_level_id"=> "529",
            "province_id"=> "5",
            "local_id"=> "3.657",
            "local_name"=> "सुद्धोदन गाउँपालिका",
            "dist_id"=> "37",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "530",
            "local_level_id"=> "530",
            "province_id"=> "5",
            "local_id"=> "3.658",
            "local_name"=> "सम्मरीमाई गाउँपालिका",
            "dist_id"=> "37",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "531",
            "local_level_id"=> "531",
            "province_id"=> "5",
            "local_id"=> "3.659",
            "local_name"=> "सियारी गाउँपालिका",
            "dist_id"=> "37",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "532",
            "local_level_id"=> "532",
            "province_id"=> "5",
            "local_id"=> "3.66",
            "local_name"=> "मायादेवी गाउँपालिका",
            "dist_id"=> "38",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "533",
            "local_level_id"=> "533",
            "province_id"=> "5",
            "local_id"=> "3.661",
            "local_name"=> "यसोधरा गाउँपालिका",
            "dist_id"=> "38",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "534",
            "local_level_id"=> "534",
            "province_id"=> "5",
            "local_id"=> "3.662",
            "local_name"=> "विजयनगर गाउँपालिका",
            "dist_id"=> "38",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "535",
            "local_level_id"=> "535",
            "province_id"=> "5",
            "local_id"=> "3.663",
            "local_name"=> "शुद्धोधन गाउँपालिका",
            "dist_id"=> "38",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "536",
            "local_level_id"=> "536",
            "province_id"=> "5",
            "local_id"=> "3.664",
            "local_name"=> "छत्रदेव गाउँपालिका",
            "dist_id"=> "39",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "537",
            "local_level_id"=> "537",
            "province_id"=> "5",
            "local_id"=> "3.665",
            "local_name"=> "पाणिनी गाउँपालिका",
            "dist_id"=> "39",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "538",
            "local_level_id"=> "538",
            "province_id"=> "5",
            "local_id"=> "3.666",
            "local_name"=> "मालारानी गाउँपालिका",
            "dist_id"=> "39",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "539",
            "local_level_id"=> "539",
            "province_id"=> "5",
            "local_id"=> "3.667",
            "local_name"=> "तिनाउ गाउँपालिका",
            "dist_id"=> "40",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "540",
            "local_level_id"=> "540",
            "province_id"=> "5",
            "local_id"=> "3.668",
            "local_name"=> "निस्दी गाउँपालिका",
            "dist_id"=> "40",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "541",
            "local_level_id"=> "541",
            "province_id"=> "5",
            "local_id"=> "3.669",
            "local_name"=> "पूर्वखोला गाउँपालिका",
            "dist_id"=> "40",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "542",
            "local_level_id"=> "542",
            "province_id"=> "5",
            "local_id"=> "3.67",
            "local_name"=> "बगनासकाली गाउँपालिका",
            "dist_id"=> "40",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "543",
            "local_level_id"=> "543",
            "province_id"=> "5",
            "local_id"=> "3.671",
            "local_name"=> "माथागढी गाउँपालिका",
            "dist_id"=> "40",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "544",
            "local_level_id"=> "544",
            "province_id"=> "5",
            "local_id"=> "3.672",
            "local_name"=> "रम्भा गाउँपालिका",
            "dist_id"=> "40",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "545",
            "local_level_id"=> "545",
            "province_id"=> "5",
            "local_id"=> "3.673",
            "local_name"=> "रिब्दीकोट गाउँपालिका",
            "dist_id"=> "40",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "546",
            "local_level_id"=> "546",
            "province_id"=> "5",
            "local_id"=> "3.674",
            "local_name"=> "रैनादेवी छहरा गाउँपालिका",
            "dist_id"=> "40",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "547",
            "local_level_id"=> "547",
            "province_id"=> "5",
            "local_id"=> "3.675",
            "local_name"=> "इस्मा गाउँपालिका",
            "dist_id"=> "41",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "548",
            "local_level_id"=> "548",
            "province_id"=> "5",
            "local_id"=> "3.676",
            "local_name"=> "कालीगण्डकी गाउँपालिका",
            "dist_id"=> "41",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "549",
            "local_level_id"=> "549",
            "province_id"=> "5",
            "local_id"=> "3.677",
            "local_name"=> "गुल्मी दरबार गाउँपालिका",
            "dist_id"=> "41",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "550",
            "local_level_id"=> "550",
            "province_id"=> "5",
            "local_id"=> "3.678",
            "local_name"=> "चन्द्रकोट गाउँपालिका",
            "dist_id"=> "41",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "551",
            "local_level_id"=> "551",
            "province_id"=> "5",
            "local_id"=> "3.679",
            "local_name"=> "छत्रकोट गाउँपालिका",
            "dist_id"=> "41",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "552",
            "local_level_id"=> "552",
            "province_id"=> "5",
            "local_id"=> "3.68",
            "local_name"=> "धुर्कोट गाउँपालिका",
            "dist_id"=> "41",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "553",
            "local_level_id"=> "553",
            "province_id"=> "5",
            "local_id"=> "3.681",
            "local_name"=> "मदाने गाउँपालिका",
            "dist_id"=> "41",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "554",
            "local_level_id"=> "554",
            "province_id"=> "5",
            "local_id"=> "3.682",
            "local_name"=> "मालिका गाउँपालिका",
            "dist_id"=> "41",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "555",
            "local_level_id"=> "555",
            "province_id"=> "5",
            "local_id"=> "3.683",
            "local_name"=> "रुरुक्षेत्र गाउँपालिका",
            "dist_id"=> "41",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "556",
            "local_level_id"=> "556",
            "province_id"=> "5",
            "local_id"=> "3.684",
            "local_name"=> "सत्यवती गाउँपालिका",
            "dist_id"=> "41",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "557",
            "local_level_id"=> "557",
            "province_id"=> "4",
            "local_id"=> "3.685",
            "local_name"=> "अर्जुनचौपारी गाउँपालिका",
            "dist_id"=> "42",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "558",
            "local_level_id"=> "558",
            "province_id"=> "4",
            "local_id"=> "3.686",
            "local_name"=> "आँधिखोला गाउँपालिका",
            "dist_id"=> "42",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "559",
            "local_level_id"=> "559",
            "province_id"=> "4",
            "local_id"=> "3.687",
            "local_name"=> "कालीगण्डकी गाउँपालिका",
            "dist_id"=> "42",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "560",
            "local_level_id"=> "560",
            "province_id"=> "4",
            "local_id"=> "3.688",
            "local_name"=> "फेदीखोला गाउँपालिका",
            "dist_id"=> "42",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "561",
            "local_level_id"=> "561",
            "province_id"=> "4",
            "local_id"=> "3.689",
            "local_name"=> "विरुवा गाउँपालिका",
            "dist_id"=> "42",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "562",
            "local_level_id"=> "562",
            "province_id"=> "4",
            "local_id"=> "3.69",
            "local_name"=> "हरिनास गाउँपालिका",
            "dist_id"=> "42",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "563",
            "local_level_id"=> "563",
            "province_id"=> "4",
            "local_id"=> "3.691",
            "local_name"=> "आँबुखैरेनी गाउँपालिका",
            "dist_id"=> "43",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "564",
            "local_level_id"=> "564",
            "province_id"=> "4",
            "local_id"=> "3.692",
            "local_name"=> "ऋषिङ्ग गाउँपालिका",
            "dist_id"=> "43",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "565",
            "local_level_id"=> "565",
            "province_id"=> "4",
            "local_id"=> "3.693",
            "local_name"=> "घिरिङ गाउँपालिका",
            "dist_id"=> "43",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "566",
            "local_level_id"=> "566",
            "province_id"=> "4",
            "local_id"=> "3.694",
            "local_name"=> "देवघाट गाउँपालिका",
            "dist_id"=> "43",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "567",
            "local_level_id"=> "567",
            "province_id"=> "4",
            "local_id"=> "3.695",
            "local_name"=> "म्याग्दे गाउँपालिका",
            "dist_id"=> "43",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "568",
            "local_level_id"=> "568",
            "province_id"=> "4",
            "local_id"=> "3.696",
            "local_name"=> "बन्दिपुर गाउँपालिका",
            "dist_id"=> "43",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "569",
            "local_level_id"=> "569",
            "province_id"=> "4",
            "local_id"=> "3.697",
            "local_name"=> "अजिरकोट गाउँपालिका",
            "dist_id"=> "44",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "570",
            "local_level_id"=> "570",
            "province_id"=> "4",
            "local_id"=> "3.698",
            "local_name"=> "आरूघाट गाउँपालिका",
            "dist_id"=> "44",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "571",
            "local_level_id"=> "571",
            "province_id"=> "4",
            "local_id"=> "3.699",
            "local_name"=> "गण्डकी गाउँपालिका",
            "dist_id"=> "44",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "572",
            "local_level_id"=> "572",
            "province_id"=> "4",
            "local_id"=> "3.7",
            "local_name"=> "चुमनुव्री गाउँपालिका",
            "dist_id"=> "44",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "573",
            "local_level_id"=> "573",
            "province_id"=> "4",
            "local_id"=> "3.701",
            "local_name"=> "धार्चे गाउँपालिका",
            "dist_id"=> "44",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "574",
            "local_level_id"=> "574",
            "province_id"=> "4",
            "local_id"=> "3.702",
            "local_name"=> "भिमसेनथापा गाउँपालिका",
            "dist_id"=> "44",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "575",
            "local_level_id"=> "575",
            "province_id"=> "4",
            "local_id"=> "3.703",
            "local_name"=> "शहिद लखन गाउँपालिका",
            "dist_id"=> "44",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "576",
            "local_level_id"=> "576",
            "province_id"=> "4",
            "local_id"=> "3.704",
            "local_name"=> "सिरानचोक गाउँपालिका",
            "dist_id"=> "44",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "577",
            "local_level_id"=> "577",
            "province_id"=> "4",
            "local_id"=> "3.705",
            "local_name"=> "बारपाक सुलिकोट गाउँपालिका",
            "dist_id"=> "44",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "578",
            "local_level_id"=> "578",
            "province_id"=> "4",
            "local_id"=> "3.706",
            "local_name"=> "चामे गाउँपालिका",
            "dist_id"=> "45",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "579",
            "local_level_id"=> "579",
            "province_id"=> "4",
            "local_id"=> "3.707",
            "local_name"=> "नार्पा भूमि गाउँपालिका",
            "dist_id"=> "45",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "580",
            "local_level_id"=> "580",
            "province_id"=> "4",
            "local_id"=> "3.708",
            "local_name"=> "नासोँ गाउँपालिका",
            "dist_id"=> "45",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "581",
            "local_level_id"=> "581",
            "province_id"=> "4",
            "local_id"=> "3.709",
            "local_name"=> "मनाङ ङिस्याङ गाउँपालिका",
            "dist_id"=> "45",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "582",
            "local_level_id"=> "582",
            "province_id"=> "4",
            "local_id"=> "3.71",
            "local_name"=> "क्व्होलासोथार गाउँपालिका",
            "dist_id"=> "46",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "583",
            "local_level_id"=> "583",
            "province_id"=> "4",
            "local_id"=> "3.711",
            "local_name"=> "दूधपोखरी गाउँपालिका",
            "dist_id"=> "46",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "584",
            "local_level_id"=> "584",
            "province_id"=> "4",
            "local_id"=> "3.712",
            "local_name"=> "दोर्दी गाउँपालिका",
            "dist_id"=> "46",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "585",
            "local_level_id"=> "585",
            "province_id"=> "4",
            "local_id"=> "3.713",
            "local_name"=> "मर्स्याङदी गाउँपालिका",
            "dist_id"=> "46",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "586",
            "local_level_id"=> "586",
            "province_id"=> "4",
            "local_id"=> "3.714",
            "local_name"=> "अन्नपूर्ण गाउँपालिका",
            "dist_id"=> "47",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "587",
            "local_level_id"=> "587",
            "province_id"=> "4",
            "local_id"=> "3.715",
            "local_name"=> "माछापुच्छ्रे गाउँपालिका",
            "dist_id"=> "47",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "588",
            "local_level_id"=> "588",
            "province_id"=> "4",
            "local_id"=> "3.716",
            "local_name"=> "मादी गाउँपालिका",
            "dist_id"=> "47",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "589",
            "local_level_id"=> "589",
            "province_id"=> "4",
            "local_id"=> "3.717",
            "local_name"=> "रूपा गाउँपालिका",
            "dist_id"=> "47",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "590",
            "local_level_id"=> "590",
            "province_id"=> "4",
            "local_id"=> "3.718",
            "local_name"=> "जलजला गाउँपालिका",
            "dist_id"=> "48",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "591",
            "local_level_id"=> "591",
            "province_id"=> "4",
            "local_id"=> "3.719",
            "local_name"=> "पैयूँ गाउँपालिका",
            "dist_id"=> "48",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "592",
            "local_level_id"=> "592",
            "province_id"=> "4",
            "local_id"=> "3.72",
            "local_name"=> "महाशिला गाउँपालिका",
            "dist_id"=> "48",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "593",
            "local_level_id"=> "593",
            "province_id"=> "4",
            "local_id"=> "3.721",
            "local_name"=> "मोदी गाउँपालिका",
            "dist_id"=> "48",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "594",
            "local_level_id"=> "594",
            "province_id"=> "4",
            "local_id"=> "3.722",
            "local_name"=> "विहादी गाउँपालिका",
            "dist_id"=> "48",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "595",
            "local_level_id"=> "595",
            "province_id"=> "4",
            "local_id"=> "3.723",
            "local_name"=> "काठेखोला गाउँपालिका",
            "dist_id"=> "49",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "596",
            "local_level_id"=> "596",
            "province_id"=> "4",
            "local_id"=> "3.724",
            "local_name"=> "तमानखोला गाउँपालिका",
            "dist_id"=> "49",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "597",
            "local_level_id"=> "597",
            "province_id"=> "4",
            "local_id"=> "3.725",
            "local_name"=> "ताराखोला गाउँपालिका",
            "dist_id"=> "49",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "598",
            "local_level_id"=> "598",
            "province_id"=> "4",
            "local_id"=> "3.726",
            "local_name"=> "निसीखोला गाउँपालिका",
            "dist_id"=> "49",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "599",
            "local_level_id"=> "599",
            "province_id"=> "4",
            "local_id"=> "3.727",
            "local_name"=> "वडिगाड गाउँपालिका",
            "dist_id"=> "49",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "600",
            "local_level_id"=> "600",
            "province_id"=> "4",
            "local_id"=> "3.728",
            "local_name"=> "वरेङ गाउँपालिका",
            "dist_id"=> "49",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "601",
            "local_level_id"=> "601",
            "province_id"=> "4",
            "local_id"=> "3.729",
            "local_name"=> "अन्नपूर्ण गाउँपालिका",
            "dist_id"=> "50",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "602",
            "local_level_id"=> "602",
            "province_id"=> "4",
            "local_id"=> "3.73",
            "local_name"=> "धवलागिरी गाउँपालिका",
            "dist_id"=> "50",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "603",
            "local_level_id"=> "603",
            "province_id"=> "4",
            "local_id"=> "3.731",
            "local_name"=> "मंगला गाउँपालिका",
            "dist_id"=> "50",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "604",
            "local_level_id"=> "604",
            "province_id"=> "4",
            "local_id"=> "3.732",
            "local_name"=> "मालिका गाउँपालिका",
            "dist_id"=> "50",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "605",
            "local_level_id"=> "605",
            "province_id"=> "4",
            "local_id"=> "3.733",
            "local_name"=> "रघुगंगा गाउँपालिका",
            "dist_id"=> "50",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "606",
            "local_level_id"=> "606",
            "province_id"=> "4",
            "local_id"=> "3.734",
            "local_name"=> "घरपझोङ गाउँपालिका",
            "dist_id"=> "51",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "607",
            "local_level_id"=> "607",
            "province_id"=> "4",
            "local_id"=> "3.735",
            "local_name"=> "थासाङ गाउँपालिका",
            "dist_id"=> "51",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "608",
            "local_level_id"=> "608",
            "province_id"=> "4",
            "local_id"=> "3.736",
            "local_name"=> "लो-घेकर दामोदरकुण्ड गाउँपालिका",
            "dist_id"=> "51",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "609",
            "local_level_id"=> "609",
            "province_id"=> "4",
            "local_id"=> "3.737",
            "local_name"=> "लोमन्थाङ गाउँपालिका",
            "dist_id"=> "51",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "610",
            "local_level_id"=> "610",
            "province_id"=> "4",
            "local_id"=> "3.738",
            "local_name"=> "वारागुङ मुक्तिक्षेत्र गाउँपालिका",
            "dist_id"=> "51",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "611",
            "local_level_id"=> "611",
            "province_id"=> "6",
            "local_id"=> "3.739",
            "local_name"=> "खत्याड गाउँपालिका",
            "dist_id"=> "52",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "612",
            "local_level_id"=> "612",
            "province_id"=> "6",
            "local_id"=> "3.74",
            "local_name"=> "मुगूम कार्मारोङ गाउँपालिका",
            "dist_id"=> "52",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "613",
            "local_level_id"=> "613",
            "province_id"=> "6",
            "local_id"=> "3.741",
            "local_name"=> "सोरु गाउँपालिका",
            "dist_id"=> "52",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "614",
            "local_level_id"=> "614",
            "province_id"=> "6",
            "local_id"=> "3.742",
            "local_name"=> "काईके गाउँपालिका",
            "dist_id"=> "53",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "615",
            "local_level_id"=> "615",
            "province_id"=> "6",
            "local_id"=> "3.743",
            "local_name"=> "छार्का ताङसोङ गाउँपालिका",
            "dist_id"=> "53",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "616",
            "local_level_id"=> "616",
            "province_id"=> "6",
            "local_id"=> "3.744",
            "local_name"=> "जगदुल्ला गाउँपालिका",
            "dist_id"=> "53",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "617",
            "local_level_id"=> "617",
            "province_id"=> "6",
            "local_id"=> "3.745",
            "local_name"=> "डोल्पो बुद्ध गाउँपालिका",
            "dist_id"=> "53",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "618",
            "local_level_id"=> "618",
            "province_id"=> "6",
            "local_id"=> "3.746",
            "local_name"=> "मुड्केचुला गाउँपालिका",
            "dist_id"=> "53",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "619",
            "local_level_id"=> "619",
            "province_id"=> "6",
            "local_id"=> "3.747",
            "local_name"=> "शे फोक्सुण्डो गाउँपालिका",
            "dist_id"=> "53",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "620",
            "local_level_id"=> "620",
            "province_id"=> "6",
            "local_id"=> "3.748",
            "local_name"=> "अदानचुली गाउँपालिका",
            "dist_id"=> "54",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "621",
            "local_level_id"=> "621",
            "province_id"=> "6",
            "local_id"=> "3.749",
            "local_name"=> "खार्पुनाथ गाउँपालिका",
            "dist_id"=> "54",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "622",
            "local_level_id"=> "622",
            "province_id"=> "6",
            "local_id"=> "3.75",
            "local_name"=> "चंखेली गाउँपालिका",
            "dist_id"=> "54",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "623",
            "local_level_id"=> "623",
            "province_id"=> "6",
            "local_id"=> "3.751",
            "local_name"=> "ताँजाकोट गाउँपालिका",
            "dist_id"=> "54",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "624",
            "local_level_id"=> "624",
            "province_id"=> "6",
            "local_id"=> "3.752",
            "local_name"=> "नाम्खा गाउँपालिका",
            "dist_id"=> "54",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "625",
            "local_level_id"=> "625",
            "province_id"=> "6",
            "local_id"=> "3.753",
            "local_name"=> "सर्केगाड गाउँपालिका",
            "dist_id"=> "54",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "626",
            "local_level_id"=> "626",
            "province_id"=> "6",
            "local_id"=> "3.754",
            "local_name"=> "सिमकोट गाउँपालिका",
            "dist_id"=> "54",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "627",
            "local_level_id"=> "627",
            "province_id"=> "6",
            "local_id"=> "3.755",
            "local_name"=> "कनकासुन्दरी गाउँपालिका",
            "dist_id"=> "55",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "628",
            "local_level_id"=> "628",
            "province_id"=> "6",
            "local_id"=> "3.756",
            "local_name"=> "गुठिचौर गाउँपालिका",
            "dist_id"=> "55",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "629",
            "local_level_id"=> "629",
            "province_id"=> "6",
            "local_id"=> "3.757",
            "local_name"=> "तातोपानी गाउँपालिका",
            "dist_id"=> "55",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "630",
            "local_level_id"=> "630",
            "province_id"=> "6",
            "local_id"=> "3.758",
            "local_name"=> "तिला गाउँपालिका",
            "dist_id"=> "55",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "631",
            "local_level_id"=> "631",
            "province_id"=> "6",
            "local_id"=> "3.759",
            "local_name"=> "पातारासी गाउँपालिका",
            "dist_id"=> "55",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "632",
            "local_level_id"=> "632",
            "province_id"=> "6",
            "local_id"=> "3.76",
            "local_name"=> "सिंजा गाउँपालिका",
            "dist_id"=> "55",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "633",
            "local_level_id"=> "633",
            "province_id"=> "6",
            "local_id"=> "3.761",
            "local_name"=> "हिमा गाउँपालिका",
            "dist_id"=> "55",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "634",
            "local_level_id"=> "634",
            "province_id"=> "6",
            "local_id"=> "3.762",
            "local_name"=> "शुभ कालिका गाउँपालिका",
            "dist_id"=> "56",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "635",
            "local_level_id"=> "635",
            "province_id"=> "6",
            "local_id"=> "3.763",
            "local_name"=> "नरहरिनाथ गाउँपालिका",
            "dist_id"=> "56",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "636",
            "local_level_id"=> "636",
            "province_id"=> "6",
            "local_id"=> "3.764",
            "local_name"=> "पचालझरना गाउँपलिका",
            "dist_id"=> "56",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "637",
            "local_level_id"=> "637",
            "province_id"=> "6",
            "local_id"=> "3.765",
            "local_name"=> "पलाता गाउँपालिका",
            "dist_id"=> "56",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "638",
            "local_level_id"=> "638",
            "province_id"=> "6",
            "local_id"=> "3.766",
            "local_name"=> "महावै गाउँपालिका",
            "dist_id"=> "56",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "639",
            "local_level_id"=> "639",
            "province_id"=> "6",
            "local_id"=> "3.767",
            "local_name"=> "सान्नी त्रिवेणी गाउँपालिका",
            "dist_id"=> "56",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "640",
            "local_level_id"=> "640",
            "province_id"=> "6",
            "local_id"=> "3.768",
            "local_name"=> "त्रिवेणी गाउँपालिका",
            "dist_id"=> "57",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "641",
            "local_level_id"=> "641",
            "province_id"=> "5",
            "local_id"=> "3.769",
            "local_name"=> "पुथा उत्तरगंगा गाउँपालिका",
            "dist_id"=> "77",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "642",
            "local_level_id"=> "642",
            "province_id"=> "6",
            "local_id"=> "3.77",
            "local_name"=> "बाँफिकोट गाउँपालिका",
            "dist_id"=> "57",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "643",
            "local_level_id"=> "643",
            "province_id"=> "5",
            "local_id"=> "3.771",
            "local_name"=> "भूमे गाउँपालिका",
            "dist_id"=> "77",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "644",
            "local_level_id"=> "644",
            "province_id"=> "6",
            "local_id"=> "3.772",
            "local_name"=> "सानीभेरी गाउँपालिका",
            "dist_id"=> "57",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "645",
            "local_level_id"=> "645",
            "province_id"=> "5",
            "local_id"=> "3.773",
            "local_name"=> "सिस्ने गाउँपालिका",
            "dist_id"=> "77",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "646",
            "local_level_id"=> "646",
            "province_id"=> "5",
            "local_id"=> "3.774",
            "local_name"=> "त्रिवेणी गाउँपालिका",
            "dist_id"=> "58",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "647",
            "local_level_id"=> "647",
            "province_id"=> "5",
            "local_id"=> "3.775",
            "local_name"=> "थवाङ गाउँपालिका",
            "dist_id"=> "58",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "648",
            "local_level_id"=> "648",
            "province_id"=> "5",
            "local_id"=> "3.776",
            "local_name"=> "परिवर्तन गाउँपालिका",
            "dist_id"=> "58",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "649",
            "local_level_id"=> "649",
            "province_id"=> "5",
            "local_id"=> "3.777",
            "local_name"=> "माडी गाउँपालिका",
            "dist_id"=> "58",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "650",
            "local_level_id"=> "650",
            "province_id"=> "5",
            "local_id"=> "3.778",
            "local_name"=> "रुन्टीगढी गाउँपालिका",
            "dist_id"=> "58",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "651",
            "local_level_id"=> "651",
            "province_id"=> "5",
            "local_id"=> "3.779",
            "local_name"=> "लुङग्री गाउँपालिका",
            "dist_id"=> "58",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "652",
            "local_level_id"=> "652",
            "province_id"=> "5",
            "local_id"=> "3.78",
            "local_name"=> "गंगादेव गाउँपालिका",
            "dist_id"=> "58",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "653",
            "local_level_id"=> "653",
            "province_id"=> "5",
            "local_id"=> "3.781",
            "local_name"=> "सुनछहरी गाउँपालिका",
            "dist_id"=> "58",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "654",
            "local_level_id"=> "654",
            "province_id"=> "5",
            "local_id"=> "3.782",
            "local_name"=> "सुनिल स्मृति गाउँपालिका",
            "dist_id"=> "58",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "655",
            "local_level_id"=> "655",
            "province_id"=> "5",
            "local_id"=> "3.783",
            "local_name"=> "ऐरावती गाउँपालिका",
            "dist_id"=> "59",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "656",
            "local_level_id"=> "656",
            "province_id"=> "5",
            "local_id"=> "3.784",
            "local_name"=> "गौमुखी गाउँपालिका",
            "dist_id"=> "59",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "657",
            "local_level_id"=> "657",
            "province_id"=> "5",
            "local_id"=> "3.785",
            "local_name"=> "झिमरुक गाउँपालिका",
            "dist_id"=> "59",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "658",
            "local_level_id"=> "658",
            "province_id"=> "5",
            "local_id"=> "3.786",
            "local_name"=> "नौबहिनी गाउँपालिका",
            "dist_id"=> "59",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "659",
            "local_level_id"=> "659",
            "province_id"=> "5",
            "local_id"=> "3.787",
            "local_name"=> "मल्लरानी गाउँपालिका",
            "dist_id"=> "59",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "660",
            "local_level_id"=> "660",
            "province_id"=> "5",
            "local_id"=> "3.788",
            "local_name"=> "माण्डवी गाउँपालिका",
            "dist_id"=> "59",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "661",
            "local_level_id"=> "661",
            "province_id"=> "5",
            "local_id"=> "3.789",
            "local_name"=> "सरुमारानी गाउँपालिका",
            "dist_id"=> "59",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "662",
            "local_level_id"=> "662",
            "province_id"=> "5",
            "local_id"=> "3.79",
            "local_name"=> "गढवा गाउँपालिका",
            "dist_id"=> "60",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "663",
            "local_level_id"=> "663",
            "province_id"=> "5",
            "local_id"=> "3.791",
            "local_name"=> "दंगीशरण गाउँपालिका",
            "dist_id"=> "60",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "664",
            "local_level_id"=> "664",
            "province_id"=> "5",
            "local_id"=> "3.792",
            "local_name"=> "बंगलाचुली गाउँपालिका",
            "dist_id"=> "60",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "665",
            "local_level_id"=> "665",
            "province_id"=> "5",
            "local_id"=> "3.793",
            "local_name"=> "बबई गाउँपालिका",
            "dist_id"=> "60",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "666",
            "local_level_id"=> "666",
            "province_id"=> "5",
            "local_id"=> "3.794",
            "local_name"=> "राजपुर गाउँपालिका",
            "dist_id"=> "60",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "667",
            "local_level_id"=> "667",
            "province_id"=> "5",
            "local_id"=> "3.795",
            "local_name"=> "राप्ती गाउँपालिका",
            "dist_id"=> "60",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "668",
            "local_level_id"=> "668",
            "province_id"=> "5",
            "local_id"=> "3.796",
            "local_name"=> "शान्तिनगर गाउँपालिका",
            "dist_id"=> "60",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "669",
            "local_level_id"=> "669",
            "province_id"=> "6",
            "local_id"=> "3.797",
            "local_name"=> "कपुरकोट गाउँपालिका",
            "dist_id"=> "61",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "670",
            "local_level_id"=> "670",
            "province_id"=> "6",
            "local_id"=> "3.798",
            "local_name"=> "कालिमाटी गाउँपालिका",
            "dist_id"=> "61",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "671",
            "local_level_id"=> "671",
            "province_id"=> "6",
            "local_id"=> "3.799",
            "local_name"=> "कुमाख मालिका गाउँपालिका",
            "dist_id"=> "61",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "672",
            "local_level_id"=> "672",
            "province_id"=> "6",
            "local_id"=> "3.8",
            "local_name"=> "छत्रेश्वरी गाउँपालिका",
            "dist_id"=> "61",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "673",
            "local_level_id"=> "673",
            "province_id"=> "6",
            "local_id"=> "3.801",
            "local_name"=> "सिद्ध कुमाख गाउँपालिका",
            "dist_id"=> "61",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "674",
            "local_level_id"=> "674",
            "province_id"=> "6",
            "local_id"=> "3.802",
            "local_name"=> "त्रिवेणी गाउँपालिका",
            "dist_id"=> "61",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "675",
            "local_level_id"=> "675",
            "province_id"=> "6",
            "local_id"=> "3.803",
            "local_name"=> "दार्मा गाउँपालिका",
            "dist_id"=> "61",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "676",
            "local_level_id"=> "676",
            "province_id"=> "5",
            "local_id"=> "3.804",
            "local_name"=> "खजुरा गाउँपालिका",
            "dist_id"=> "62",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "677",
            "local_level_id"=> "677",
            "province_id"=> "5",
            "local_id"=> "3.805",
            "local_name"=> "जानकी गाउँपालिका",
            "dist_id"=> "62",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "678",
            "local_level_id"=> "678",
            "province_id"=> "5",
            "local_id"=> "3.806",
            "local_name"=> "डुडुवा गाउँपालिका",
            "dist_id"=> "62",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "679",
            "local_level_id"=> "679",
            "province_id"=> "5",
            "local_id"=> "3.807",
            "local_name"=> "नरैनापुर गाउँपालिका",
            "dist_id"=> "62",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "680",
            "local_level_id"=> "680",
            "province_id"=> "5",
            "local_id"=> "3.808",
            "local_name"=> "बैजनाथ गाउँपालिका",
            "dist_id"=> "62",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "681",
            "local_level_id"=> "681",
            "province_id"=> "5",
            "local_id"=> "3.809",
            "local_name"=> "राप्तीसोनारी गाउँपालिका",
            "dist_id"=> "62",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "682",
            "local_level_id"=> "682",
            "province_id"=> "5",
            "local_id"=> "3.81",
            "local_name"=> "गेरुवा गाउँपालिका",
            "dist_id"=> "63",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "683",
            "local_level_id"=> "683",
            "province_id"=> "5",
            "local_id"=> "3.811",
            "local_name"=> "बढैयाताल गाउँपालिका",
            "dist_id"=> "63",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "684",
            "local_level_id"=> "684",
            "province_id"=> "6",
            "local_id"=> "3.812",
            "local_name"=> "चिङ्गाड गाउँपालिका",
            "dist_id"=> "64",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "685",
            "local_level_id"=> "685",
            "province_id"=> "6",
            "local_id"=> "3.813",
            "local_name"=> "चौकुने गाउँपालिका",
            "dist_id"=> "64",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "686",
            "local_level_id"=> "686",
            "province_id"=> "6",
            "local_id"=> "3.814",
            "local_name"=> "बराहताल गाउँपालिका",
            "dist_id"=> "64",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "687",
            "local_level_id"=> "687",
            "province_id"=> "6",
            "local_id"=> "3.815",
            "local_name"=> "सिम्ता गाउँपालिका",
            "dist_id"=> "64",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "688",
            "local_level_id"=> "688",
            "province_id"=> "6",
            "local_id"=> "3.816",
            "local_name"=> "कुसे गाउँपालिका",
            "dist_id"=> "65",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "689",
            "local_level_id"=> "689",
            "province_id"=> "6",
            "local_id"=> "3.817",
            "local_name"=> "जुनीचाँदे गाउँपालिका",
            "dist_id"=> "65",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "690",
            "local_level_id"=> "690",
            "province_id"=> "6",
            "local_id"=> "3.818",
            "local_name"=> "बारेकोट गाउँपालिका",
            "dist_id"=> "65",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "691",
            "local_level_id"=> "691",
            "province_id"=> "6",
            "local_id"=> "3.819",
            "local_name"=> "शिवालय गाउँपालिका",
            "dist_id"=> "65",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "692",
            "local_level_id"=> "692",
            "province_id"=> "6",
            "local_id"=> "3.82",
            "local_name"=> "गुराँस गाउँपालिका",
            "dist_id"=> "66",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "693",
            "local_level_id"=> "693",
            "province_id"=> "6",
            "local_id"=> "3.821",
            "local_name"=> "ठाँटीकाँध गाउँपालिका",
            "dist_id"=> "66",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "694",
            "local_level_id"=> "694",
            "province_id"=> "6",
            "local_id"=> "3.822",
            "local_name"=> "डुङ्गेश्वर गाउँपालिका",
            "dist_id"=> "66",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "695",
            "local_level_id"=> "695",
            "province_id"=> "6",
            "local_id"=> "3.823",
            "local_name"=> "नौमुले गाउँपालिका",
            "dist_id"=> "66",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "696",
            "local_level_id"=> "696",
            "province_id"=> "6",
            "local_id"=> "3.824",
            "local_name"=> "भगवतीमाई गाउँपालिका",
            "dist_id"=> "66",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "697",
            "local_level_id"=> "697",
            "province_id"=> "6",
            "local_id"=> "3.825",
            "local_name"=> "भैरवी गाउँपालिका",
            "dist_id"=> "66",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "698",
            "local_level_id"=> "698",
            "province_id"=> "6",
            "local_id"=> "3.826",
            "local_name"=> "महाबु गाउँपालिका",
            "dist_id"=> "66",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "699",
            "local_level_id"=> "699",
            "province_id"=> "7",
            "local_id"=> "3.827",
            "local_name"=> "कैलारी गाउँपालिका",
            "dist_id"=> "67",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "700",
            "local_level_id"=> "700",
            "province_id"=> "7",
            "local_id"=> "3.828",
            "local_name"=> "चुरे गाउँपालिका",
            "dist_id"=> "67",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "701",
            "local_level_id"=> "701",
            "province_id"=> "7",
            "local_id"=> "3.829",
            "local_name"=> "जानकी गाउँपालिका",
            "dist_id"=> "67",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "702",
            "local_level_id"=> "702",
            "province_id"=> "7",
            "local_id"=> "3.83",
            "local_name"=> "जोशीपुर गाउँपालिका",
            "dist_id"=> "67",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "703",
            "local_level_id"=> "703",
            "province_id"=> "7",
            "local_id"=> "3.831",
            "local_name"=> "बर्दगोरिया गाउँपालिका",
            "dist_id"=> "67",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "704",
            "local_level_id"=> "704",
            "province_id"=> "7",
            "local_id"=> "3.832",
            "local_name"=> "मोहन्याल गाउँपालिका",
            "dist_id"=> "67",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "705",
            "local_level_id"=> "705",
            "province_id"=> "7",
            "local_id"=> "3.833",
            "local_name"=> "आदर्श गाउँपालिका",
            "dist_id"=> "68",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "706",
            "local_level_id"=> "706",
            "province_id"=> "7",
            "local_id"=> "3.834",
            "local_name"=> "के.आई.सिंह गाउँपालिका",
            "dist_id"=> "68",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "707",
            "local_level_id"=> "707",
            "province_id"=> "7",
            "local_id"=> "3.835",
            "local_name"=> "जोरायल गाउँपालिका",
            "dist_id"=> "68",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "708",
            "local_level_id"=> "708",
            "province_id"=> "7",
            "local_id"=> "3.836",
            "local_name"=> "पूर्वीचौकी गाउँपालिका",
            "dist_id"=> "68",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "709",
            "local_level_id"=> "709",
            "province_id"=> "7",
            "local_id"=> "3.837",
            "local_name"=> "बडीकेदार गाउँपालिका",
            "dist_id"=> "68",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "710",
            "local_level_id"=> "710",
            "province_id"=> "7",
            "local_id"=> "3.838",
            "local_name"=> "बोगटान फुड्सिल गाउँपालिका",
            "dist_id"=> "68",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "711",
            "local_level_id"=> "711",
            "province_id"=> "7",
            "local_id"=> "3.839",
            "local_name"=> "सायल गाउँपालिका",
            "dist_id"=> "68",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "712",
            "local_level_id"=> "712",
            "province_id"=> "7",
            "local_id"=> "3.84",
            "local_name"=> "चौरपाटी गाउँपालिका",
            "dist_id"=> "69",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "713",
            "local_level_id"=> "713",
            "province_id"=> "7",
            "local_id"=> "3.841",
            "local_name"=> "ढकारी गाउँपालिका",
            "dist_id"=> "69",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "714",
            "local_level_id"=> "714",
            "province_id"=> "7",
            "local_id"=> "3.842",
            "local_name"=> "तुर्माखाँद गाउँपालिका",
            "dist_id"=> "69",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "715",
            "local_level_id"=> "715",
            "province_id"=> "7",
            "local_id"=> "3.843",
            "local_name"=> "बान्नीगढी जयगढ गाउँपालिका",
            "dist_id"=> "69",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "716",
            "local_level_id"=> "716",
            "province_id"=> "7",
            "local_id"=> "3.844",
            "local_name"=> "मेल्लेख गाउँपालिका",
            "dist_id"=> "69",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "717",
            "local_level_id"=> "717",
            "province_id"=> "7",
            "local_id"=> "3.845",
            "local_name"=> "रामारोशन गाउँपालिका",
            "dist_id"=> "69",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "718",
            "local_level_id"=> "718",
            "province_id"=> "7",
            "local_id"=> "3.846",
            "local_name"=> "गौमुल गाउँपालिका",
            "dist_id"=> "70",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "719",
            "local_level_id"=> "719",
            "province_id"=> "7",
            "local_id"=> "3.847",
            "local_name"=> "खप्तड छेडेदह गाउँपालिका",
            "dist_id"=> "70",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "720",
            "local_level_id"=> "720",
            "province_id"=> "7",
            "local_id"=> "3.848",
            "local_name"=> "जगन्नाथ गाउँपालिका",
            "dist_id"=> "70",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "721",
            "local_level_id"=> "721",
            "province_id"=> "7",
            "local_id"=> "3.849",
            "local_name"=> "स्वामीकार्तिक खापर गाउँपालिका",
            "dist_id"=> "70",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "722",
            "local_level_id"=> "722",
            "province_id"=> "7",
            "local_id"=> "3.85",
            "local_name"=> "हिमाली गाउँपालिका",
            "dist_id"=> "70",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "723",
            "local_level_id"=> "723",
            "province_id"=> "7",
            "local_id"=> "3.851",
            "local_name"=> "साईपाल गाउँपालिका",
            "dist_id"=> "71",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "724",
            "local_level_id"=> "724",
            "province_id"=> "7",
            "local_id"=> "3.852",
            "local_name"=> "केदारस्युँ गाउँपालिका",
            "dist_id"=> "71",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "725",
            "local_level_id"=> "725",
            "province_id"=> "7",
            "local_id"=> "3.853",
            "local_name"=> "खप्तडछान्ना गाउँपालिका",
            "dist_id"=> "71",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "726",
            "local_level_id"=> "726",
            "province_id"=> "7",
            "local_id"=> "3.854",
            "local_name"=> "छबिसपाथिभेरा गाँउपालिका",
            "dist_id"=> "71",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "727",
            "local_level_id"=> "727",
            "province_id"=> "7",
            "local_id"=> "3.855",
            "local_name"=> "तलकोट गाउँपालिका",
            "dist_id"=> "71",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "728",
            "local_level_id"=> "728",
            "province_id"=> "7",
            "local_id"=> "3.856",
            "local_name"=> "थलारा गाउँपालिका",
            "dist_id"=> "71",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "729",
            "local_level_id"=> "729",
            "province_id"=> "7",
            "local_id"=> "3.857",
            "local_name"=> "दुर्गाथली गाउँपालिका",
            "dist_id"=> "71",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "730",
            "local_level_id"=> "730",
            "province_id"=> "7",
            "local_id"=> "3.858",
            "local_name"=> "मष्टा गाउँपालिका",
            "dist_id"=> "71",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "731",
            "local_level_id"=> "731",
            "province_id"=> "7",
            "local_id"=> "3.859",
            "local_name"=> "बित्थडचिर गाउँपालिका",
            "dist_id"=> "71",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "732",
            "local_level_id"=> "732",
            "province_id"=> "7",
            "local_id"=> "3.86",
            "local_name"=> "सूर्मा गाउँपालिका",
            "dist_id"=> "71",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "733",
            "local_level_id"=> "733",
            "province_id"=> "7",
            "local_id"=> "3.861",
            "local_name"=> "अपिहिमाल गाउँपालिका",
            "dist_id"=> "72",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "734",
            "local_level_id"=> "734",
            "province_id"=> "7",
            "local_id"=> "3.862",
            "local_name"=> "दुहुँ गाउँपालिका",
            "dist_id"=> "72",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "735",
            "local_level_id"=> "735",
            "province_id"=> "7",
            "local_id"=> "3.863",
            "local_name"=> "नौगाड गाउँपालिका",
            "dist_id"=> "72",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "736",
            "local_level_id"=> "736",
            "province_id"=> "7",
            "local_id"=> "3.864",
            "local_name"=> "व्याँस गाउँपालिका",
            "dist_id"=> "72",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "737",
            "local_level_id"=> "737",
            "province_id"=> "7",
            "local_id"=> "3.865",
            "local_name"=> "मार्मा गाउँपालिका",
            "dist_id"=> "72",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "738",
            "local_level_id"=> "738",
            "province_id"=> "7",
            "local_id"=> "3.866",
            "local_name"=> "मालिकार्जुन गाउँपालिका",
            "dist_id"=> "72",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "739",
            "local_level_id"=> "739",
            "province_id"=> "7",
            "local_id"=> "3.867",
            "local_name"=> "लेकम गाउँपालिका",
            "dist_id"=> "72",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "740",
            "local_level_id"=> "740",
            "province_id"=> "7",
            "local_id"=> "3.868",
            "local_name"=> "डिलासैनी गाउँपालिका",
            "dist_id"=> "73",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "741",
            "local_level_id"=> "741",
            "province_id"=> "7",
            "local_id"=> "3.869",
            "local_name"=> "दोगडाकेदार गाउँपालिका",
            "dist_id"=> "73",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "742",
            "local_level_id"=> "742",
            "province_id"=> "7",
            "local_id"=> "3.87",
            "local_name"=> "पञ्चेश्वर गाउँपालिका",
            "dist_id"=> "73",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "743",
            "local_level_id"=> "743",
            "province_id"=> "7",
            "local_id"=> "3.871",
            "local_name"=> "शिवनाथ गाउँपालिका",
            "dist_id"=> "73",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "744",
            "local_level_id"=> "744",
            "province_id"=> "7",
            "local_id"=> "3.872",
            "local_name"=> "सिगास गाउँपालिका",
            "dist_id"=> "73",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "745",
            "local_level_id"=> "745",
            "province_id"=> "7",
            "local_id"=> "3.873",
            "local_name"=> "सुर्नया गाउँपालिका",
            "dist_id"=> "73",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "746",
            "local_level_id"=> "746",
            "province_id"=> "7",
            "local_id"=> "3.874",
            "local_name"=> "अजयमेरु गाउँपालिका",
            "dist_id"=> "74",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "747",
            "local_level_id"=> "747",
            "province_id"=> "7",
            "local_id"=> "3.875",
            "local_name"=> "आलिताल गाउँपालिका",
            "dist_id"=> "74",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "748",
            "local_level_id"=> "748",
            "province_id"=> "7",
            "local_id"=> "3.876",
            "local_name"=> "गन्यापधुरा गाउँपालिका",
            "dist_id"=> "74",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "749",
            "local_level_id"=> "749",
            "province_id"=> "7",
            "local_id"=> "3.877",
            "local_name"=> "नवदुर्गा गाउँपालिका",
            "dist_id"=> "74",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "750",
            "local_level_id"=> "750",
            "province_id"=> "7",
            "local_id"=> "3.878",
            "local_name"=> "भागेश्वर गाउँपालिका",
            "dist_id"=> "74",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "751",
            "local_level_id"=> "751",
            "province_id"=> "7",
            "local_id"=> "3.879",
            "local_name"=> "बेलडाँडी गाउँपालिका",
            "dist_id"=> "75",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "752",
            "local_level_id"=> "752",
            "province_id"=> "7",
            "local_id"=> "3.88",
            "local_name"=> "लालझाडी गाउँपालिका",
            "dist_id"=> "75",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "753",
            "local_level_id"=> "753",
            "province_id"=> "2",
            "local_id"=> "3.881",
            "local_name"=> "बलान-विहुल गाउँपालिका",
            "dist_id"=> "15",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "754",
            "local_level_id"=> "754",
            "province_id"=> "2",
            "local_id"=> "3.882",
            "local_name"=> "धनौजी गाउँपालिका",
            "dist_id"=> "17",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "755",
            "local_level_id"=> "755",
            "province_id"=> "2",
            "local_id"=> "3.883",
            "local_name"=> "बसबरीया गाउँपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "756",
            "local_level_id"=> "756",
            "province_id"=> "2",
            "local_id"=> "3.884",
            "local_name"=> "कौडेना गाउँपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "757",
            "local_level_id"=> "757",
            "province_id"=> "2",
            "local_id"=> "3.885",
            "local_name"=> "पर्सा गाउँपालिका",
            "dist_id"=> "19",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "758",
            "local_level_id"=> "758",
            "province_id"=> "2",
            "local_id"=> "3.886",
            "local_name"=> "यमुनामाई गाउँपालिका",
            "dist_id"=> "32",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "759",
            "local_level_id"=> "759",
            "province_id"=> "2",
            "local_id"=> "3.887",
            "local_name"=> "विश्रामपुर गाउँपालिका",
            "dist_id"=> "33",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "760",
            "local_level_id"=> "760",
            "province_id"=> "2",
            "local_id"=> "3.888",
            "local_name"=> "कालिकामाई गाउँपालिका",
            "dist_id"=> "34",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
       ],
        [
            "id"=> "761",
            "local_level_id"=> "761",
            "province_id"=> "2",
            "local_id"=> "3.889",
            "local_name"=> "जिराभवानी गाउँपालिका",
            "dist_id"=> "34",
            "created_at"=> "2020-01-02",
            "updated_at"=> "2020-01-02"
        ],
        ]);
    }
}
