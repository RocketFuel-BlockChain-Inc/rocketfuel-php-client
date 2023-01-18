<?php

namespace RKFL\Client;

class RKFL_CLIENT
{
    public $endpoint;
    public $environment;
    private $merchantId;
    // private $email;
    // private $password; //deprecated
    private $clientId;
    private $secret;
    
    function __construct($options)
    {
        $this->environment = $options['environment'];
        $this->merchantId = $options['merchantId'];
  
        $this->clientId = $options['clientId'];
        $this->secret = $options['secret'];
     
        $this->endpoint = $this->getEndpoint();
    }

    function getEndpoint(){
        return $this->environment  === 'prod' ? 'https://app.rocketfuelblockchain.com' : 'https://app-sandbox.rocketfuelblockchain.com';
    }
    function encrypt($toEncrypt, $secret){
        $salt = openssl_random_pseudo_bytes(8);

        $salted = $dx = '';
        while (strlen($salted) < 48) {
            $dx = md5($dx . $secret . $salt, true);
            $salted .= $dx;
        }

        $key = substr($salted, 0, 32);
        $iv = substr($salted, 32, 16);

        // encrypt with PKCS7 padding
        return base64_encode('Salted__' . $salt . openssl_encrypt($toEncrypt . '', 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv));
    }
    function rkflLogin()
    {
        $curl = curl_init();
        $cred = array('clientId' =>  $this->clientId, 
        'encryptedPayload' => $this->encrypt(
            json_encode([
                'merchantId' => $this->merchantId,
                'totp' => '',
            ]),
            $this->secret
        ));
 
        curl_setopt_array($curl, array(

            CURLOPT_URL => $this->endpoint.'/api/auth/generate-auth-token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($cred),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    function rkflgenerateUUID($payload)
    {
        $response = $this->rkflLogin();
        $response = json_decode($response);
 
        $access =  $response->result->access;
        $curl = curl_init();
      
        curl_setopt_array($curl, array(

            CURLOPT_URL => $this->endpoint.'/api/hosted-page',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $access,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return  $response;
    }
}
