<?php
/**
 * $_REQUEST wont work because the webhook is application/json format and not formdata format
 */
// $payload = file_get_contents('php://'); //use for receiving payload frmo RKFL SERVER


$payload = file_get_contents('./webhook_example.json'); //use for test
$payload = json_decode($payload);

$result = verify_callback($payload->data->data,$payload->signature);

if($result){
echo 'verified';
}else{
    echo 'not verified';

}

function verify_callback($body, $signature)
{
    $signature_buffer = base64_decode($signature);
    return (1 === openssl_verify($body, $signature_buffer, get_callback_public_key(), OPENSSL_ALGO_SHA256));
}
function get_callback_public_key() {
    $pub_key_path = dirname( __FILE__ ) . '/rf.pub';

    if ( ! file_exists( $pub_key_path ) ) {
        return false;
    }
    return file_get_contents($pub_key_path);
}


