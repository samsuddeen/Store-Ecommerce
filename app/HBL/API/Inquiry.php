<?php
namespace App\HBL\API;

use App\HBL\Action\PaymentAction;
use App\HBL\Data\CredentialData;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;

class Inquiry extends PaymentAction
{
    /**
     * @throws GuzzleException
     */
    public function Execute(): string
    {
        $now = Carbon::now();

        $officeId = "DEMOOFFICE";
        $orderNo = "1635476979216";

        $request = [
            "apiRequest" => [
                "requestMessageID" => $this->Guid(),
                "requestDateTime" => $now->utc()->format('Y-m-d\TH:i:s.v\Z'),
                "language" => "en-US",
            ],
            "advSearchParams" => [
                "controllerInternalID" => null,
                "officeId" => [
                    $officeId
                ],
                "orderNo" => [
                    "$orderNo"
                ],
                "invoiceNo2C2P" => null,
                "fromDate" => "0001-01-01T00:00:00",
                "toDate" => "0001-01-01T00:00:00",
                "amountFrom" => null,
                "amountTo" => null
            ],
        ];

        $stringRequest = json_encode($request);

        //third-party http client https://github.com/guzzle/guzzle
        $response = $this->client->post('api/1.0/Inquiry/transactionList', [
            'headers' => [
                'Accept' => 'application/json',
                'apiKey' => CredentialData::$AccessToken,
                'Content-Type' => 'application/json; charset=utf-8'
            ],
            'body' => $stringRequest
        ]);

        return $response->getBody()->getContents();
    }

    /**
     * @throws GuzzleException
     * @throws Exception
     */
    public function ExecuteJose(): string
    {
        $now = Carbon::now();

        $officeId = "DEMOOFFICE";
        $orderNo = "1635476979216";

        $request = [
            "apiRequest" => [
                "requestMessageID" => $this->Guid(),
                "requestDateTime" => $now->utc()->format('Y-m-d\TH:i:s.v\Z'),
                "language" => "en-US",
            ],
            "advSearchParams" => [
                "controllerInternalID" => null,
                "officeId" => [
                    $officeId
                ],
                "orderNo" => [
                    $orderNo
                ],
                "invoiceNo2C2P" => null,
                "fromDate" => "0001-01-01T00:00:00",
                "toDate" => "0001-01-01T00:00:00",
                "amountFrom" => null,
                "amountTo" => null
            ],
        ];

        $payload = [
            "request" => $request,
            "iss" => CredentialData::$AccessToken,
            "aud" => "PacoAudience",
            "CompanyApiKey" => CredentialData::$AccessToken,
            "iat" => $now->unix(),
            "nbf" => $now->unix(),
            "exp" => $now->addHour()->unix(),
        ];

        $stringPayload = json_encode($payload);
        $signingKey = $this->GetPrivateKey(CredentialData::$MerchantSigningPrivateKey);
        $encryptingKey = $this->GetPublicKey(CredentialData::$PacoEncryptionPublicKey);

        $body = $this->EncryptPayload($stringPayload, $signingKey, $encryptingKey);

        //third-party http client https://github.com/guzzle/guzzle
        $response = $this->client->post('api/1.0/Inquiry/transactionList', [
            'headers' => [
                'Accept' => 'application/jose',
                'CompanyApiKey' => CredentialData::$AccessToken,
                'Content-Type' => 'application/jose; charset=utf-8'
            ],
            'body' => $body
        ]);

        $token = $response->getBody()->getContents();
        $decryptingKey = $this->GetPrivateKey(CredentialData::$MerchantDecryptionPrivateKey);
        $signatureVerificationKey = $this->GetPublicKey(CredentialData::$PacoSigningPublicKey);

        return $this->DecryptToken($token, $decryptingKey, $signatureVerificationKey);
    }

}