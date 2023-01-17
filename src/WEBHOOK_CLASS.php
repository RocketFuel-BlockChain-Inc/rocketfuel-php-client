<?php
namespace RKFL\Client;


class WEBHOOK_CLASS
{


    public static function validate_payment($body)
    {
        $ddt = json_decode($body->data);

        $status = (int)$ddt->paymentStatus;

        switch ($status) {
            case 1:
                echo 'Payment successful';
                break;

            case 101:
                echo 'Partial Payment successful';
                break;
            case -1:
                echo 'Partial failed';
                break;
            default:
                echo 'Partial pending';

                break;
        }
    }
    public static function verify_callback($body, $signature)
    {
        $signature_buffer = base64_decode($signature);
        return (1 === openssl_verify($body, $signature_buffer, self::get_callback_public_key(), OPENSSL_ALGO_SHA256));
    }
    public static function get_callback_public_key()
    {
        $pub_key_path = dirname(__FILE__) . '/rf.pub';

        if (!file_exists($pub_key_path)) {
            return false;
        }
        return file_get_contents($pub_key_path);
    }
}

