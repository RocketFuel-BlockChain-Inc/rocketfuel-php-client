PHP library for the Rocketfuel API.
This is a PHP client library for accessing the Rocketfuel API.

Where possible, the services available on the client groups the API into logical chunks and correspond to the structure of the Rocketfuel API documentation at https:docs.rocketfuelblockchain.com/developer-guides/api-reference
 
================================

==INSTRUCTION==

Generate the uuid on the server side
 
 ```
    require_once(PATH_TO_RKFL.'../src/RKFL_CLIENT.php');
    //configure Options
    $options = array(
        'environment'=>'sandbox', //sandbox -- prod,
        'merchantId'=>'MERCHANT_ID',
        'password'=>'PASSWORD',
        'email'=>'EMAIL',
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
        "merchant_id" => MERCHANT_ID,
        "currency" => "USD",
        "order" => "20",
        "redirectUrl" => ""
    );

    $rkfl->rkflgenerateUUID($payload);
 ```
 
 

==WEBHOOK SYSTEM==
For more information, follow https:docs.rocketfuelblockchain.com/webhooks

```
   require_once(PATH_TO_RKFL.'../src/WEBHOOK_CLASS.php');
    use RKFL\Client\WEBHOOK_CLASS as rkflWebhook
 
    /**
     * $_REQUEST wont work because the webhook is application/json format and not formdata format
     */
    $payload = file_get_contents('php://input'); //use for receiving payload from RKFL SERVER
 
    $payload = json_decode($payload);

    $result = rkflWebhook::verify_callback($payload->data->data, $payload->signature);

    if ($result) {
        echo "verified \n";
    } else {
        echo 'not verified';
        return;
    }
    rkflWebhook::validate_payment($payload->data);
 
```