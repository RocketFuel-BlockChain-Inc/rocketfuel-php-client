<?php
 
require_once(dirname(__DIR__).'/src/RKFL_CLIENT.php');

$options = array(
    'environment'=>'sandbox', //sandbox,prod,
    'merchantId'=>'9514ec97-8672-4668-bf43-8722c9fe89c2',
    'secret'=>'fdb652e4-ee6e-478d-b332-53f9c045663b',
    'clientId'=>'45f880085d700cab1b16a506357b6bc4459b49864933ce6a91a47f2863f630c7'
);

$rkfl = new \RKFL\Client\RKFL_CLIENT($options);

$payload = array(
    "amount" => "100",
    "cart" => array(
        array(
            "name" => "Test",
            "id" => "200",
            "price" => 100,
            "quantity" => "1"
        )
    ),
    "merchant_id" => $options['merchantId'],
    "currency" => "USD",
    "order" =>  (string)time(),
    "redirectUrl" => ""
);

$response = $rkfl->rkflgenerateUUID($payload);
echo $response;
 

require_once(dirname(__DIR__).'/src/WEBHOOK_CLASS.php');

use \RKFL\Client\WEBHOOK_CLASS as rkflWebhook;
    /**
     * $_REQUEST wont work because the webhook is application/json format and not formdata format
     */
      //  use for receiving payload from RKFL SERVER
    // $payload = file_get_contents('php://input');
  
    $payload = file_get_contents(dirname(__DIR__).'/example/webhook_example.json'); //use for test
 
    $payload = json_decode($payload);

    $result = rkflWebhook::verify_callback($payload->data->data, $payload->signature);

    if ($result) {
        echo "verified \n";
    } else {
        echo 'not verified';
        return;
    }
    rkflWebhook::validate_payment($payload->data);