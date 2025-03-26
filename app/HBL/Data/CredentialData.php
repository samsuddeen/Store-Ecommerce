<?php
namespace App\HBL\Data;

class CredentialData
{
   //public static string $MerchantId = "******";
	public static string $MerchantId = "9101232984";
    /**
     * JWE Key Id.
     *
     * @var string
     */
    public static string $EncryptionKeyId = "19f84b5655f04e25a99b09f1ee2fac78";

    /**
     * Access Token.
     *
     * @var string
		USD 
		80b7734eb0e54b53a81ae9c3e01b51c0
		NPR
		78c41a95aaf6470c81d70d4034299551
     */
     //public static string $AccessToken = "a1419ad372c5426d855017aa857c469d";
	public static string $AccessToken = "19fdbb22502a4eba8700f920a774bc69";//NPR
	


    /**
     * Token Type - Used in JWS and JWE header.
     *
     * @var string
     */
    public static string $TokenType = "JWT";

    /**
     * JWS (JSON Web Signature) Signature Algorithm - This parameter identifies the cryptographic algorithm used to
     * secure the JWS.
     *
     * @var string
     */
    public static string $JWSAlgorithm = "PS256";

    /**
     * JWE (JSON Web Encryption) Key Encryption Algorithm - This parameter identifies the cryptographic algorithm
     * used to secure the JWE.
     *
     * @var string
     */
    public static string $JWEAlgorithm = "RSA-OAEP";

    /**
     * JWE (JSON Web Encryption) Content Encryption Algorithm - This parameter identifies the content encryption
     * algorithm used on the plaintext to produce the encrypted ciphertext.
     *
     * @var string
     */
    public static string $JWEEncrptionAlgorithm = "A128CBC-HS256";

    /**
     * Merchant Signing Private Key is used to cryptographically sign and create the request JWS.
     *
     * @var string
     */
    public static string $MerchantSigningPrivateKey = "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";
    /**
     * PACO Encryption Public Key is used to cryptographically encrypt and create the request JWE.
     *
     * @var string
     */
    public static string $PacoEncryptionPublicKey = "MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA6ZLups2K0iYEMxQqgASX8gY6tWhNVCp08YuDgjCsOVrGVgUHD0dh0TWFNJ7Lq2Jp0SOsGgi54+hrjwPOL2CCZxw8pKUlL57UksoD9oWUrK/KkSvEAwPU4cZqzxIXyhBcZb8O96iN4WQJILkRTg+DXLkML6qisO496fPGIs+vCoc87toucy5O9fRfaYSjcqjreyi8JDkvVJM/BeNtOEM2a0b/lcWa67RH+tN97H25k+Qez7QthLru6oBfWBgD6iIwhV+ICqLWHmp6fQ+DHQk/o+OO3yFiY9OAvMiy8MOTinvkBlFwYgYNznG3/w0Xh8U5vtudUXPDNUO6ddf4y99+6LlWDiKgJn/Th93YUg+gFH4LUJHyPrSY2JuC+Q8kksp2xyiZDTHGzi96kturwrqCui6TytCHcU4UB0VRMR+M7VRl3S2YPhcxv5U8Fh2PITqydZE5vv1Va06qhegjOlSZnEUl2xKPm5k/u+UHvUP/oq04fQLTlYqyA3JYDCe4z5Ea2SOgjeVl+qTatWYzmkUXyCONLZ4UaRrgbYCp0nCPHoTFgRQdChu8ezDbnYY9IW7cT/s2fEi5N7X1XrQttiEP4rbn0y0qVYYjN86+elfhtYGHidZTUSUS5RSTHqOkj59p5LIGwFF9iTXzCjfUqq8clnfOk76qSLY1+Kj+SMMe6Z8CAwEAAQ==";
    /**
     * PACO Signing Public Key is used to cryptographically verify the response JWS signature.
     *
     * @var string
     */
    public static string $PacoSigningPublicKey = "MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAr0XW6QacR8GilY4nZrJZW40wnFeYu7h9aXUSqxCP6djurCWZmLqnrsYWP7/HR8WOulYPHTVpfqJesTOdVqPgY6p10H811oRbJG9jvsG8j8kn/Bk8b2wZ9qelNqdNJMDbR5WUyaytaDWW6QdI4+clqjFfwCOw76noDSe+R4pDSzgMiyCk5R4m2ECT1fv/4Axz2bvLN+DRTg5DPPIMLWpA87lgjxeaDlGyJqZCbkJozW7JX0AJVc0X7YR9kzbiTi3LVOInSKY+VHT8yCARIdvXtKc6+IWSbVQqgpNIBB8GN0OvU8xedjPNCMGZnnMtgd7XLTf/okyadbdNLAqQLTbDs/5HnIVx8FyfgiOS/zsim5ivi3ljVAW3T3ePGjkY0q1DMzr5iJ4m/WTL2d1TArlfHyQhkSpFpQPOO+pJyVQqttHJo99vMirQogdSx4lIu//aod0yJyJLpjCeiqb2Fz3Qk0AZ4S78QKeeGsxTRchTP6Wsb6okaZd+cFi6z8qbP0z/Y3xRZO7vOLB/whkqS+pMVKBQ42YzgQPRzbXXmgCkf1nCqgrD9bnIB5ovdRGfDXW86GKY8XwGVjb4BoMvql+HsbonKHAO+eGfQulpB5YfQGQU3ZXdMdfCLAk8FuqemH4k7S7diLzVvRCuisHsEx6qJ4ewxzNCvW7OGVinTR9NSQUCAwEAAQ==";
    /**
     * Merchant Decryption Private Key used to cryptographically decrypt the response JWE.
     * @var string
     */
    public static string $MerchantDecryptionPrivateKey = "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";
}