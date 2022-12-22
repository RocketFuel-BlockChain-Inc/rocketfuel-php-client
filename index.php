<?php

$curl = curl_init();

$cred = array('email'=>'EMAIL', 'password'=>'PASSWORD');
$merchantId = 'MERCHANT_ID';
curl_setopt_array($curl, array(

  CURLOPT_URL => 'https://app.rocketfuelblockchain.com/api/auth/login',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>json_encode($cred),
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
$response = json_decode($response);
 
$curl = curl_init();
$payload = array(
    "amount"=> "100",
    "cart" => array(
            array(
                "name"=>"Test",
                "id"=>"200",
                "price"=>100,
                "quantity"=>"1"
            )
        ),
    "merchant_id" =>$merchantId,
    "currency" => "USD",
    "order" => "20",
    "redirectUrl" => ""
);
curl_setopt_array($curl, array(
 
  CURLOPT_URL => 'https://app.rocketfuelblockchain.com/api/hosted-page',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => json_encode($payload),
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer '.$response->result->access,
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

curl_close($curl);
echo $response;

//sample response
//{"ok":true,"result":{"url":"https://qa-payment.rocketdemo.net/hostedPage/083f3af4-8d06-4b8e-99a8-2694a407a10e","uuid":"083f3af4-8d06-4b8e-99a8-2694a407a10e","returnListingFormat":{"invoiceId":"083f3af4-8d06-4b8e-99a8-2694a407a10e","customerName":null,"createdAt":"2022-12-22T20:03:56.325Z","invoiceAmount":"100","currency":"USD","symbol":"USD","email":null,"status":111,"details":{"customerDetails":null,"invoiceDetails":{"invoiceId":"20","totalAmount":"100","itemCarts":[{"name":"Test","id":"200","price":100,"quantity":1}],"currency":"USD"},"paymentLink":"https://qa-payment.rocketdemo.net/hostedPage/083f3af4-8d06-4b8e-99a8-2694a407a10e"}}}}



// ================================
// INSTRUCTION```
// Two parts 
// 1. Server side -> Generate the uuid on the server side




// 2. Client side -> use rkfl.js to trigger the iframe
// use https://bitbucket.org/rocketfuelblockchain/rocketfuel-readme/src/master/javascript-sdk.md

// <script src="rkfl.js"></script>

// STEP 1
// rkfl = new  RocketFuel({
//     uuid,
//     callback:  callBackFunc,
//     environment:  "prod"  // prod, preprod,sandbox
// });

//function callBackFunc(result){
    /**
     *  if(result.status !== 0 ){
     *  //update order status
     * }
     * 
     */
// }
// STEP 2
//  rkfl.initPayment();



//STATUSES
// 0 = Pending || completed should be regarded as pending
// 1 = success
// -1 = failed 


// WEBHOOK SYSTEM
// https://docs.rocketfuelblockchain.com/webhooks