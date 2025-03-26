<?php
namespace App\HBL\API;

use App\HBL\Action\PaymentAction;
use App\HBL\Data\CredentialData;
use Carbon\Carbon;
use GuzzleHttp\Exception\GuzzleException;

class VoidRequest extends PaymentAction
{
    /**
     * @throws GuzzleException
     */
    public function Execute(): string
    {
        $officeId = "DEMOOFFICE";
        $orderNo = "1643362945102"; //OrderNo can be Refund/Void one time only
        $productDescription = "Sample request for 1643362945102";

        $request = [
            "officeId" => $officeId,
            "orderNo" => $orderNo,
            "productDescription" => $productDescription,
            "issuerApprovalCode" => "140331", // approvalCode of order place (Payment api) response
            "actionBy" => "System",
            "voidAmount" => [
                "amountText" => "000000100000",
                "currencyCode" => "THB",
                "decimalPlaces" => 2,
                "amount" => 1000.00
            ],
        ];

        $stringRequest = json_encode($request);

        //third-party http client https://github.com/guzzle/guzzle
        $response = $this->client->post('api/1.0/Void', [
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
        $orderNo = "1643362945102"; //OrderNo can be Refund/Void one time only
        $productDescription = "Sample request for 1643362945102";

        $request = [
            "officeId" => $officeId,
            "orderNo" => $orderNo,
            "productDescription" => $productDescription,
            "issuerApprovalCode" => "140331", // approvalCode of order place (Payment api) response
            "actionBy" => "System",
            "voidAmount" => [
                "amountText" => "000000100000",
                "currencyCode" => "THB",
                "decimalPlaces" => 2,
                "amount" => 1000.00
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
        $response = $this->client->post('api/1.0/Void', [
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