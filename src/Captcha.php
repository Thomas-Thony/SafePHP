<?php

namespace SafePHP;

use OpenSSLAsymmetricKey;
use SafePHP\Secret;
use SafePHP\ErrorHandler;
use SafePHP\Globals\Globals;

class Captcha {
    private OpenSSLAsymmetricKey $private_key_ressource;
    private string $public_key_ressource;
    private string $signature;

    /**
     * Initialize Captcha
     */
    public function __construct(string $aSignature) {
        $this->signature = $aSignature;
        $privateKey = $this->initKeys();
        $this->exportKeys($privateKey);
    }

    /**
     *  Create key with ECDSA
     * @return OpenSSLAsymmetricKey the key generated
     */
    public function initKeys() : OpenSSLAsymmetricKey {
        // Get env file for curve type
        $secret = new Secret(__DIR__ . "/../config/");
        $secret->getEnv();

        // Create key
        $this->private_key_ressource = openssl_pkey_new([
        "curve_name" => $_ENV["CURVE_NAME"],
        "private_key_type" => OPENSSL_KEYTYPE_EC,
        ]);
        return $this->private_key_ressource;
    }

    /**
     * Export key to PEM format
     * @return void
     */
    public function exportKeys($privateKeyRessource){
        openssl_pkey_export($privateKeyRessource, $private_key_string);
        $public_key_details = openssl_pkey_get_details($privateKeyRessource);
        $this->public_key_ressource = $public_key_details["key"];
        file_put_contents(Globals::$captchaDir . "public_key.pem", $this->public_key_ressource);
        file_put_contents(Globals::$captchaDir . "private_key.pem", $this->private_key_ressource);
    }

    /**
     * Sign data with private key
     * @param mixed $data
     * @return void
     */
    public function sign(mixed $data) : string|ErrorHandler {
        $private_key_resource = openssl_pkey_get_private($private_key_pem_string);

        $signature = '';
        // Sign the data using SHA256
        if (openssl_sign($data, $signature, $private_key_resource, OPENSSL_ALGO_SHA256)) {
            $base64_signature = base64_encode($signature);
        } else {
            return new ErrorHandler(500, "500.php", Globals::$logsDir . "signature.log");
        }
        return $base64_signature;
    }

    /**
     * Verify data signature
     * @param mixed $data
     * @param string $signature
     * @return mixed
     */
    public function verifySignature($data, $signature) {
        $received_signature_base64 = $signature;

        // Load the public key
        $public_key_resource = openssl_pkey_get_public($this->public_key_ressource);

        // Decode the signature from Base64
        $signature_binary = base64_decode($received_signature_base64);

        // Verify the signature
        $is_valid = openssl_verify($data, $signature_binary, $public_key_resource, OPENSSL_ALGO_SHA256);

        if ($is_valid === 1) {
            // Signature verified
            http_response_code(200);
        } elseif ($is_valid === 0) {
            // Unauthorised signature or wrong
            return new ErrorHandler(401, "401.php", Globals::$logsDir . "signature.log");
        } else {
            // Server error
            return new ErrorHandler(500, "500.php", Globals::$logsDir . "signature.log");
        }
    }
}