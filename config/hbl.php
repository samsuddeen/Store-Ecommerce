<?php

return [
    'api_npr_key' => env("HBL_NPR_API_KEY", "19fdbb22502a4eba8700f920a774bc69"),
    'api_usd_key' => env("HBL_USD_API_KEY", "19fdbb22502a4eba8700f920a774bc69"),
    'merchant_id' => env("HBL_MERCHANT_ID", "9101232984"),
    "hbl_success"=>env("HBL_SUCCESS_URL", env("APP_URL")),
    "hbl_success_guest"=>env("HBL_SUCCESS_URL_GUEST", env("APP_URL")),
    "hbl_success_guestdirect"=>env("HBL_SUCCESS_URL_DIRECTGUEST", env("APP_URL")),
    "hbl_failure"=>env("HBL_FAILED_URL", env("APP_URL")),
    "hbl_cancel"=>env("HBL_CANCEL_URL", env("APP_URL")),
    "hbl_backend"=>env("HBL_BACKEND_URL", env("APP_URL")),
    "3d_secure"=>env("HBL_3D_SECURE", "Y"),
    "encryption_id"=>env('HBL_ENCRYPTION_ID', "19f84b5655f04e25a99b09f1ee2fac78"),
];

