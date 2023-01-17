<?php

namespace RKFL\Client;

class RKFL_CLIENT
{
    public $endpoint;
    public $environment;
    private $merchantId;
    private $email;
    private $password;
    
    function __construct($options)
    {
        $this->environment = $options['environment'];
        $this->merchantId = $options['merchantId'];
        $this->email = $options['email'];
        $this->password = $options['password'];
        $this->endpoint = $this->getEndpoint();

    }

    function getEndpoint(){
        return $this->environment  === 'prod' ? 'https://app.rocketfuelblockchain.com' : 'https://app-sandbox.rocketfuelblockchain.com';
    }
    function rkflLogin()
    {
        $curl = curl_init();
        $cred = array('email' =>  $this->email, 'password' => $this->password);

        curl_setopt_array($curl, array(

            CURLOPT_URL => $this->endpoint.'/api/auth/login',
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
