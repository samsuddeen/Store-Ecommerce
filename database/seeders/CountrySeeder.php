<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CountrySeeder extends Seeder
{
    /**   * Run the database seeds.   *   * @return void   */  public function run()
    {
        $countries = [
            [
                "id" => 1,
                "name" => "Afghanistan",
                "slug" => "afghanistan",
                "iso_2" => "AF",
            ],
            [
                "id" => 2,
                "name" => "Albania",
                "slug" => "albania",
                "iso_2" => "AL",
            ],          [
                "id" => 3,
                "name" => "Algeria",
                "slug" => "algeria",
                "iso_2" => "DZ",
            ],          [
                "id" => 4, "name" => "American Samoa", "slug" => "american-samoa", "iso_2" => "AS",
            ],          [
                "id" => 5, "name" => "Andorra", "slug" => "andorra", "iso_2" => "AD",
            ],          [
                "id" => 6, "name" => "Angola", "slug" => "angola", "iso_2" => "AO",
            ],          [
                "id" => 7, "name" => "Anguilla", "slug" => "anguilla", "iso_2" => "AI",
            ],          [
                "id" => 8, "name" => "Antigua", "slug" => "antigua", "iso_2" => "AG",
            ],          [
                "id" => 9, "name" => "Argentina", "slug" => "argentina", "iso_2" => "AR",
            ],          [
                "id" => 10, "name" => "Armenia", "slug" => "armenia", "iso_2" => "AM",
            ],          [
                "id" => 11, "name" => "Aruba", "slug" => "aruba", "iso_2" => "AW",
            ],          [
                "id" => 12, "name" => "Australia", "slug" => "australia", "iso_2" => "AU",
            ],          [
                "id" => 13, "name" => "Austria", "slug" => "austria", "iso_2" => "AT",
            ],          [
                "id" => 14, "name" => "Azerbaijan", "slug" => "azerbaijan", "iso_2" => "AZ",
            ],          [
                "id" => 15, "name" => "Bahamas", "slug" => "bahamas", "iso_2" => "BS",
            ],          [
                "id" => 16, "name" => "Bahrain", "slug" => "bahrain", "iso_2" => "BH",
            ],          [
                "id" => 17, "name" => "Bangladesh", "slug" => "bangladesh", "iso_2" => "BD",
            ],          [
                "id" => 18, "name" => "Barbados", "slug" => "barbados", "iso_2" => "BB",
            ],          [
                "id" => 19, "name" => "Belarus", "slug" => "belarus", "iso_2" => "BY",
            ],          [
                "id" => 20, "name" => "Belgium", "slug" => "belgium", "iso_2" => "BE",
            ],          [
                "id" => 21, "name" => "Belize", "slug" => "belize", "iso_2" => "BZ",
            ],          [
                "id" => 22, "name" => "Benin", "slug" => "benin", "iso_2" => "BJ",
            ],          [
                "id" => 23, "name" => "Bermuda", "slug" => "bermuda", "iso_2" => "BM",
            ],          [
                "id" => 24, "name" => "Bhutan", "slug" => "bhutan", "iso_2" => "BT",
            ],          [
                "id" => 25, "name" => "Bolivia", "slug" => "bolivia", "iso_2" => "BO",
            ],          [
                "id" => 26, "name" => "Bonaire", "slug" => "bonaire", "iso_2" => "XB",
            ],          [
                "id" => 27, "name" => "Bosnia", "slug" => "bosnia", "iso_2" => "BA",
            ],          [
                "id" => 28, "name" => "Botswana", "slug" => "botswana", "iso_2" => "BW",
            ],          [
                "id" => 29, "name" => "Brazil", "slug" => "brazil", "iso_2" => "BR",
            ],          [
                "id" => 30, "name" => "Brunei", "slug" => "brunei", "iso_2" => "BN",
            ],          [
                "id" => 31, "name" => "Bulgaria", "slug" => "bulgaria", "iso_2" => "BG",
            ],          [
                "id" => 32, "name" => "Burkina Faso", "slug" => "burkina-faso", "iso_2" => "BF",
            ],          [
                "id" => 33, "name" => "Burundi", "slug" => "burundi", "iso_2" => "BI",
            ],          [
                "id" => 34, "name" => "Cambodia", "slug" => "cambodia", "iso_2" => "KH",
            ],          [
                "id" => 35, "name" => "Cameroon", "slug" => "cameroon", "iso_2" => "CM",
            ],          [
                "id" => 36, "name" => "Canada", "slug" => "canada", "iso_2" => "CA",
            ],          [
                "id" => 37, "name" => "Canary Islands, The", "slug" => "canary-islands-the", "iso_2" => "IC",
            ],          [
                "id" => 38, "name" => "Cape Verde", "slug" => "cape-verde", "iso_2" => "CV",
            ],          [
                "id" => 39, "name" => "Cayman Islands", "slug" => "cayman-islands", "iso_2" => "KY",
            ],          [
                "id" => 40, "name" => "Central African", "slug" => "central-african", "iso_2" => "CF",
            ],          [
                "id" => 41, "name" => "Chad", "slug" => "chad", "iso_2" => "TD",
            ],          [
                "id" => 42, "name" => "Chile", "slug" => "chile", "iso_2" => "CL",
            ],          [
                "id" => 43, "name" => "China", "slug" => "china", "iso_2" => "CN",
            ],          [
                "id" => 44, "name" => "Colombia", "slug" => "colombia", "iso_2" => "CO",
            ],          [
                "id" => 45, "name" => "Comoros", "slug" => "comoros", "iso_2" => "KM",
            ],          [
                "id" => 46, "name" => "Congo", "slug" => "congo", "iso_2" => "CG",
            ],          [
                "id" => 47, "name" => "Congo, DPR", "slug" => "congo-dpr", "iso_2" => "CD",
            ],          [
                "id" => 48, "name" => "Cook Islands", "slug" => "cook-islands", "iso_2" => "CK",
            ],          [
                "id" => 49, "name" => "Costa Rica", "slug" => "costa-rica", "iso_2" => "CR",
            ],          [
                "id" => 50, "name" => "Cote D Ivoire", "slug" => "cote-d-ivoire", "iso_2" => "CI",
            ],          [
                "id" => 51, "name" => "Croatia", "slug" => "croatia", "iso_2" => "HR",
            ],          [
                "id" => 52, "name" => "Cuba", "slug" => "cuba", "iso_2" => "CU",
            ],          [
                "id" => 53, "name" => "Curacao", "slug" => "curacao", "iso_2" => "XC",
            ],          [
                "id" => 54, "name" => "Cyprus", "slug" => "cyprus", "iso_2" => "CY",
            ],          [
                "id" => 55, "name" => "Czech Rep., The", "slug" => "czech-rep-the", "iso_2" => "CZ",
            ],          [
                "id" => 56, "name" => "Denmark", "slug" => "denmark", "iso_2" => "DK",
            ],          [
                "id" => 57, "name" => "Djibouti", "slug" => "djibouti", "iso_2" => "DJ",
            ],          [
                "id" => 58, "name" => "Dominica", "slug" => "dominica", "iso_2" => "DM",
            ],          [
                "id" => 59, "name" => "Dominican Rep.", "slug" => "dominican-rep", "iso_2" => "DO",
            ],          [
                "id" => 60, "name" => "East Timor", "slug" => "east-timor", "iso_2" => "TL",
            ],          [
                "id" => 61, "name" => "Ecuador", "slug" => "ecuador", "iso_2" => "EC",
            ],          [
                "id" => 62, "name" => "Egypt", "slug" => "egypt", "iso_2" => "EG",
            ],          [
                "id" => 63, "name" => "El Salvador", "slug" => "el-salvador", "iso_2" => "SV",
            ],          [
                "id" => 64, "name" => "Eritrea", "slug" => "eritrea", "iso_2" => "ER",
            ],          [
                "id" => 65, "name" => "Estonia", "slug" => "estonia", "iso_2" => "EE",
            ],          [
                "id" => 66, "name" => "Ethiopia", "slug" => "ethiopia", "iso_2" => "ET",
            ],          [
                "id" => 67, "name" => "Falkland Islands", "slug" => "falkland-islands", "iso_2" => "FK",
            ],          [
                "id" => 68, "name" => "Faroe Islands", "slug" => "faroe-islands", "iso_2" => "FO",
            ],          [
                "id" => 69, "name" => "Fiji", "slug" => "fiji", "iso_2" => "FJ",
            ],          [
                "id" => 70, "name" => "Finland", "slug" => "finland", "iso_2" => "FI",
            ],          [
                "id" => 71, "name" => "France", "slug" => "france", "iso_2" => "FR",
            ],          [
                "id" => 72, "name" => "French Guyana", "slug" => "french-guyana", "iso_2" => "GF",
            ],          [
                "id" => 73, "name" => "Gabon", "slug" => "gabon", "iso_2" => "GA",
            ],          [
                "id" => 74, "name" => "Gambia", "slug" => "gambia", "iso_2" => "GM",
            ],          [
                "id" => 75, "name" => "Georgia", "slug" => "georgia", "iso_2" => "GE",
            ],          [
                "id" => 76, "name" => "Germany", "slug" => "germany", "iso_2" => "DE",
            ],          [
                "id" => 77, "name" => "Ghana", "slug" => "ghana", "iso_2" => "GH",
            ],          [
                "id" => 78, "name" => "Gibraltar", "slug" => "gibraltar", "iso_2" => "GI",
            ],          [
                "id" => 79, "name" => "Greece", "slug" => "greece", "iso_2" => "GR",
            ],          [
                "id" => 80, "name" => "Greenland", "slug" => "greenland", "iso_2" => "GL",
            ],          [
                "id" => 81, "name" => "Grenada", "slug" => "grenada", "iso_2" => "GD",
            ],          [
                "id" => 82, "name" => "Guadeloupe", "slug" => "guadeloupe", "iso_2" => "GP",
            ],          [
                "id" => 83, "name" => "Guam", "slug" => "guam", "iso_2" => "GU",
            ],          [
                "id" => 84, "name" => "Guatemala", "slug" => "guatemala", "iso_2" => "GT",
            ],          [
                "id" => 85, "name" => "Guernsey", "slug" => "guernsey", "iso_2" => "GG",
            ],          [
                "id" => 86, "name" => "Guinea Rep.", "slug" => "guinea-rep", "iso_2" => "GN",
            ],          [
                "id" => 87, "name" => "Guinea-Bissau", "slug" => "guinea-bissau", "iso_2" => "GW",
            ],          [
                "id" => 88, "name" => "Guinea-Equatorial", "slug" => "guinea-equatorial", "iso_2" => "GQ",
            ],          [
                "id" => 89, "name" => "Guyana (British)", "slug" => "guyana-british", "iso_2" => "GY",
            ],          [
                "id" => 90, "name" => "Haiti", "slug" => "haiti", "iso_2" => "HT",
            ],          [
                "id" => 91, "name" => "Honduras", "slug" => "honduras", "iso_2" => "HN",
            ],          [
                "id" => 92, "name" => "Hong Kong", "slug" => "hong-kong", "iso_2" => "HK",
            ],          [
                "id" => 93, "name" => "Hungary", "slug" => "hungary", "iso_2" => "HU",
            ],          [
                "id" => 94, "name" => "Iceland", "slug" => "iceland", "iso_2" => "IS",
            ],          [
                "id" => 95, "name" => "India", "slug" => "india", "iso_2" => "IN",
            ],          [
                "id" => 96, "name" => "Indonesia", "slug" => "indonesia", "iso_2" => "ID",
            ],          [
                "id" => 97, "name" => "Iran", "slug" => "iran", "iso_2" => "IR",
            ],          [
                "id" => 98, "name" => "Iraq", "slug" => "iraq", "iso_2" => "IQ",
            ],          [
                "id" => 99, "name" => "Israel", "slug" => "israel", "iso_2" => "IL",
            ],          [
                "id" => 100, "name" => "Ireland, Rep. Of", "slug" => "ireland-rep-of", "iso_2" => "IE",
            ],          [
                "id" => 101, "name" => "Italy", "slug" => "italy", "iso_2" => "IT",
            ],          [
                "id" => 102, "name" => "Jamaica", "slug" => "jamaica", "iso_2" => "JM",
            ],          [
                "id" => 103, "name" => "Japan", "slug" => "japan", "iso_2" => "JP",
            ],          [
                "id" => 104, "name" => "Jersey", "slug" => "jersey", "iso_2" => "JE",
            ],          [
                "id" => 105, "name" => "Jordan", "slug" => "jordan", "iso_2" => "JO",
            ],          [
                "id" => 106, "name" => "Kazakhstan", "slug" => "kazakhstan", "iso_2" => "KZ",
            ],          [
                "id" => 107, "name" => "Kenya", "slug" => "kenya", "iso_2" => "KE",
            ],          [
                "id" => 108, "name" => "Kiribati", "slug" => "kiribati", "iso_2" => "KI",
            ],          [
                "id" => 109, "name" => "Korea,  D.P.R Of", "slug" => "korea-dpr-of", "iso_2" => "KP",
            ],          [
                "id" => 110, "name" => "Korea, Rep. Of", "slug" => "korea-rep-of", "iso_2" => "KR",
            ],          [
                "id" => 111, "name" => "Kosovo", "slug" => "kosovo", "iso_2" => "KV",
            ],          [
                "id" => 112, "name" => "Kuwait", "slug" => "kuwait", "iso_2" => "KW",
            ],          [
                "id" => 113, "name" => "Kyrgyzstan", "slug" => "kyrgyzstan", "iso_2" => "KG",
            ],          [
                "id" => 114, "name" => "Laos", "slug" => "laos", "iso_2" => "LA",
            ],          [
                "id" => 115, "name" => "Latvia", "slug" => "latvia", "iso_2" => "LV",
            ],          [
                "id" => 116, "name" => "Lebanon", "slug" => "lebanon", "iso_2" => "LB",
            ],          [
                "id" => 117, "name" => "Lesotho", "slug" => "lesotho", "iso_2" => "LS",
            ],          [
                "id" => 118, "name" => "Liberia", "slug" => "liberia", "iso_2" => "LR",
            ],          [
                "id" => 119, "name" => "Libya", "slug" => "libya", "iso_2" => "LY",
            ],          [
                "id" => 120, "name" => "Liechtenstein", "slug" => "liechtenstein", "iso_2" => "LI",
            ],          [
                "id" => 121, "name" => "Lithuania", "slug" => "lithuania", "iso_2" => "LT",
            ],          [
                "id" => 122, "name" => "Luxembourg", "slug" => "luxembourg", "iso_2" => "LU",
            ],          [
                "id" => 123, "name" => "Macau", "slug" => "macau", "iso_2" => "MO",
            ],          [
                "id" => 124, "name" => "Madagascar", "slug" => "madagascar", "iso_2" => "MG",
            ],          [
                "id" => 125, "name" => "Malawi", "slug" => "malawi", "iso_2" => "MW",
            ],          [
                "id" => 126, "name" => "Malaysia", "slug" => "malaysia", "iso_2" => "MY",
            ],          [
                "id" => 127, "name" => "Maldives", "slug" => "maldives", "iso_2" => "MV",
            ],          [
                "id" => 128, "name" => "Mali", "slug" => "mali", "iso_2" => "ML",
            ],          [
                "id" => 129, "name" => "Malta", "slug" => "malta", "iso_2" => "MT",
            ],          [
                "id" => 130, "name" => "Mariana Islands", "slug" => "mariana-islands", "iso_2" => "MP",
            ],          [
                "id" => 131, "name" => "Marshall Islands", "slug" => "marshall-islands", "iso_2" => "MH",
            ],          [
                "id" => 132, "name" => "Martinique", "slug" => "martinique", "iso_2" => "MQ",
            ],          [
                "id" => 133, "name" => "Mauritania", "slug" => "mauritania", "iso_2" => "MR",
            ],          [
                "id" => 134, "name" => "Mauritius", "slug" => "mauritius", "iso_2" => "MU",
            ],          [
                "id" => 135, "name" => "Mayotte", "slug" => "mayotte", "iso_2" => "YT",
            ],          [
                "id" => 136, "name" => "Mexico", "slug" => "mexico", "iso_2" => "MX",
            ],          [
                "id" => 137, "name" => "Micronesia", "slug" => "micronesia", "iso_2" => "FM",
            ],          [
                "id" => 138, "name" => "Moldova, Rep. Of", "slug" => "moldova-rep-of", "iso_2" => "MD",
            ],          [
                "id" => 139, "name" => "Monaco", "slug" => "monaco", "iso_2" => "MC",
            ],          [
                "id" => 140, "name" => "Mongolia", "slug" => "mongolia", "iso_2" => "MN",
            ],          [
                "id" => 141, "name" => "Montenegro, Rep Of", "slug" => "montenegro-rep-of", "iso_2" => "ME",
            ],          [
                "id" => 142, "name" => "Montserrat", "slug" => "montserrat", "iso_2" => "MS",
            ],          [
                "id" => 143, "name" => "Morocco", "slug" => "morocco", "iso_2" => "MA",
            ],          [
                "id" => 144, "name" => "Mozambique", "slug" => "mozambique", "iso_2" => "MZ",
            ],          [
                "id" => 145, "name" => "Myanmar", "slug" => "myanmar", "iso_2" => "MM",
            ],          [
                "id" => 146, "name" => "Namibia", "slug" => "namibia", "iso_2" => "NA",
            ],          [
                "id" => 147, "name" => "Nauru, Rep. Of", "slug" => "nauru-rep-of", "iso_2" => "NR",
            ],          [
                "id" => 148, "name" => "Nepal", "slug" => "nepal", "iso_2" => "NP",
            ],          [
                "id" => 149, "name" => "Netherlands, The", "slug" => "netherlands-the", "iso_2" => "NL",
            ],          [
                "id" => 150, "name" => "Nevis", "slug" => "nevis", "iso_2" => "XN",
            ],          [
                "id" => 151, "name" => "New Caledonia", "slug" => "new-caledonia", "iso_2" => "NC",
            ],          [
                "id" => 152, "name" => "New Zealand", "slug" => "new-zealand", "iso_2" => "NZ",
            ],          [
                "id" => 153, "name" => "Nicaragua", "slug" => "nicaragua", "iso_2" => "NI",
            ],          [
                "id" => 154, "name" => "Niger", "slug" => "niger", "iso_2" => "NE",
            ],          [
                "id" => 155, "name" => "Nigeria", "slug" => "nigeria", "iso_2" => "NG",
            ],          [
                "id" => 156, "name" => "Niue", "slug" => "niue", "iso_2" => "NU",
            ],          [
                "id" => 157, "name" => "North Macedonia", "slug" => "north-macedonia", "iso_2" => "MK",
            ],          [
                "id" => 158, "name" => "Norway", "slug" => "norway", "iso_2" => "NO",
            ],          [
                "id" => 159, "name" => "Oman", "slug" => "oman", "iso_2" => "OM",
            ],          [
                "id" => 160, "name" => "Pakistan", "slug" => "pakistan", "iso_2" => "PK",
            ],          [
                "id" => 161, "name" => "Palau", "slug" => "palau", "iso_2" => "PW",
            ],          [
                "id" => 162, "name" => "Panama", "slug" => "panama", "iso_2" => "PA",
            ],          [
                "id" => 163, "name" => "Papua New Guinea", "slug" => "papua-new-guinea", "iso_2" => "PG",
            ],          [
                "id" => 164, "name" => "Paraguay", "slug" => "paraguay", "iso_2" => "PY",
            ],          [
                "id" => 165, "name" => "Peru", "slug" => "peru", "iso_2" => "PE",
            ],          [
                "id" => 166, "name" => "Philippines, The", "slug" => "philippines-the", "iso_2" => "PH",
            ],          [
                "id" => 167, "name" => "Poland", "slug" => "poland", "iso_2" => "PL",
            ],          [
                "id" => 168, "name" => "Portugal", "slug" => "portugal", "iso_2" => "PT",
            ],          [
                "id" => 169, "name" => "Puerto Rico", "slug" => "puerto-rico", "iso_2" => "PR",
            ],          [
                "id" => 170, "name" => "Qatar", "slug" => "qatar", "iso_2" => "QA",
            ],          [
                "id" => 171, "name" => "Reunion, Island Of", "slug" => "reunion-island-of", "iso_2" => "RE",
            ],          [
                "id" => 172, "name" => "Romania", "slug" => "romania", "iso_2" => "RO",
            ],          [
                "id" => 173, "name" => "Russian Federation", "slug" => "russian-federation", "iso_2" => "RU",
            ],          [
                "id" => 174, "name" => "Rwanda", "slug" => "rwanda", "iso_2" => "RW",
            ],          [
                "id" => 175, "name" => "Saint Helena", "slug" => "saint-helena", "iso_2" => "SH",
            ],          [
                "id" => 176, "name" => "Samoa", "slug" => "samoa", "iso_2" => "WS",
            ],          [
                "id" => 177, "name" => "San Marino", "slug" => "san-marino", "iso_2" => "SM",
            ],          [
                "id" => 178, "name" => "Sao Tome And Principe", "slug" => "sao-tome-and-principe", "iso_2" => "ST",
            ],          [
                "id" => 179, "name" => "Saudi Arabia", "slug" => "saudi-arabia", "iso_2" => "SA",
            ],          [
                "id" => 180, "name" => "Senegal", "slug" => "senegal", "iso_2" => "SN",
            ],          [
                "id" => 181, "name" => "Serbia, Rep. Of", "slug" => "serbia-rep-of", "iso_2" => "RS",
            ],          [
                "id" => 182, "name" => "Seychelles", "slug" => "seychelles", "iso_2" => "SC",
            ],          [
                "id" => 183, "name" => "Sierra Leone", "slug" => "sierra-leone", "iso_2" => "SL",
            ],          [
                "id" => 184, "name" => "Singapore", "slug" => "singapore", "iso_2" => "SG",
            ],          [
                "id" => 185, "name" => "Slovakia", "slug" => "slovakia", "iso_2" => "SK",
            ],          [
                "id" => 186, "name" => "Slovenia", "slug" => "slovenia", "iso_2" => "SI",
            ],          [
                "id" => 187, "name" => "Solomon Islands", "slug" => "solomon-islands", "iso_2" => "SB",
            ],          [
                "id" => 188, "name" => "Somalia", "slug" => "somalia", "iso_2" => "SO",
            ],          [
                "id" => 189, "name" => "Somaliland, Rep Of", "slug" => "somaliland-rep-of", "iso_2" => "XS",
            ],          [
                "id" => 190, "name" => "South Africa", "slug" => "south-africa", "iso_2" => "ZA",
            ],          [
                "id" => 191, "name" => "South Sudan", "slug" => "south-sudan", "iso_2" => "SS",
            ],          [
                "id" => 192, "name" => "Spain", "slug" => "spain", "iso_2" => "ES",
            ],          [
                "id" => 193, "name" => "Sri Lanka", "slug" => "sri-lanka", "iso_2" => "LK",
            ],          [
                "id" => 194, "name" => "St. Barthelemy", "slug" => "st-barthelemy", "iso_2" => "XY",
            ],          [
                "id" => 195, "name" => "St. Eustatius", "slug" => "st-eustatius", "iso_2" => "XE",
            ],          [
                "id" => 196, "name" => "St. Kitts", "slug" => "st-kitts", "iso_2" => "KN",
            ],          [
                "id" => 197, "name" => "St. Lucia", "slug" => "st-lucia", "iso_2" => "LC",
            ],          [
                "id" => 198, "name" => "St. Maarten", "slug" => "st-maarten", "iso_2" => "XM",
            ],          [
                "id" => 199, "name" => "St. Vincent", "slug" => "st-vincent", "iso_2" => "VC",
            ],          [
                "id" => 200, "name" => "Sudan", "slug" => "sudan", "iso_2" => "SD",
            ],          [
                "id" => 201, "name" => "Suriname", "slug" => "suriname", "iso_2" => "SR",
            ],          [
                "id" => 202, "name" => "Swaziland", "slug" => "swaziland", "iso_2" => "SZ",
            ],          [
                "id" => 203, "name" => "Sweden", "slug" => "sweden", "iso_2" => "SE",
            ],          [
                "id" => 204, "name" => "Switzerland", "slug" => "switzerland", "iso_2" => "CH",
            ],          [
                "id" => 205, "name" => "Syria", "slug" => "syria", "iso_2" => "SY",
            ],          [
                "id" => 206, "name" => "Tahiti", "slug" => "tahiti", "iso_2" => "PF",
            ],          [
                "id" => 207, "name" => "Taiwan", "slug" => "taiwan", "iso_2" => "TW",
            ],          [
                "id" => 208, "name" => "Tajikistan", "slug" => "tajikistan", "iso_2" => "TJ",
            ],          [
                "id" => 209, "name" => "Tanzania", "slug" => "tanzania", "iso_2" => "TZ",
            ],          [
                "id" => 210, "name" => "Thailand", "slug" => "thailand", "iso_2" => "TH",
            ],          [
                "id" => 211, "name" => "Togo", "slug" => "togo", "iso_2" => "TG",
            ],          [
                "id" => 212, "name" => "Tonga", "slug" => "tonga", "iso_2" => "TO",
            ],          [
                "id" => 213, "name" => "Trinidad And Tobago", "slug" => "trinidad-and-tobago", "iso_2" => "TT",
            ],          [
                "id" => 214, "name" => "Tunisia", "slug" => "tunisia", "iso_2" => "TN",
            ],          [
                "id" => 215, "name" => "Turkey", "slug" => "turkey", "iso_2" => "TR",
            ],          [
                "id" => 216, "name" => "Turkmenistan", "slug" => "turkmenistan", "iso_2" => "TM",
            ],          [
                "id" => 217, "name" => "Turks & Caicos", "slug" => "turks-caicos", "iso_2" => "TC",
            ],          [
                "id" => 218, "name" => "Tuvalu", "slug" => "tuvalu", "iso_2" => "TV",
            ],          [
                "id" => 219, "name" => "Uganda", "slug" => "uganda", "iso_2" => "UG",
            ],          [
                "id" => 220, "name" => "Ukraine", "slug" => "ukraine", "iso_2" => "UA",
            ],          [
                "id" => 221, "name" => "United Kingdom", "slug" => "united-kingdom", "iso_2" => "GB",
            ],          [
                "id" => 222, "name" => "Uruguay", "slug" => "uruguay", "iso_2" => "UY",
            ],          [
                "id" => 223, "name" => "USA", "slug" => "usa", "iso_2" => "US",
            ],          [
                "id" => 224, "name" => "Uzbekistan", "slug" => "uzbekistan", "iso_2" => "UZ",
            ],          [
                "id" => 225, "name" => "Vanuatu", "slug" => "vanuatu", "iso_2" => "VU",
            ],          [
                "id" => 226, "name" => "Vatican City", "slug" => "vatican-city", "iso_2" => "VA",
            ],          [
                "id" => 227, "name" => "Venezuela", "slug" => "venezuela", "iso_2" => "VE",
            ],          [
                "id" => 228, "name" => "Vietnam", "slug" => "vietnam", "iso_2" => "VN",
            ],          [
                "id" => 229, "name" => "Virgin Islands-British", "slug" => "virgin-islands-british", "iso_2" => "VG",
            ],          [
                "id" => 230, "name" => "Virgin Islands-US", "slug" => "virgin-islands-us", "iso_2" => "VI",
            ],          [
                "id" => 231, "name" => "Yemen, Rep. Of", "slug" => "yemen-rep-of", "iso_2" => "YE",
            ],          [
                "id" => 232, "name" => "Zambia", "slug" => "zambia", "iso_2" => "ZM",
            ],          [
                "id" => 233, "name" => "Zimbabwe", "slug" => "zimbabwe", "iso_2" => "ZW",
            ],          [
                "id" => 234, "name" => "United Arab Emirates", "slug" => "united-arab-emirates", "iso_2" => "AE",
            ],          [
                "id" => 235, "name" => "Wallis & Futuna", "slug" => "wallis-futuna", "iso_2" => "WF",
            ],          [
                "id" => 236, "name" => "St Maarten fedex", "slug" => "st-marten", "iso_2" => "SX",
            ],          [
                "id" => 237, "name" => "St Martin", "slug" => "st-martin", "iso_2" => "MF",
            ],          [
                "id" => 238, "name" => "Palestine Autonomous", "slug" => "palestine-autonomous", "iso_2" => "PS",
            ],          [
                "id" => 239, "name" => "Curacao fedex", "slug" => "curacao-fedex", "iso_2" => "CW",
            ],          [
                "id" => 240, "name" => "Bonaire fedex", "slug" => "bonaire-fedex", "iso_2" => "BQ",
            ]
        ];
        Schema::disableForeignKeyConstraints();
        DB::table('countries')->truncate();
        DB::table('countries')->insert($countries);
        Schema::enableForeignKeyConstraints();
    }
}
