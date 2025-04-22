<?php

namespace Armen\WhatsappPsr7Streams\Stream;

class Encrypt
{

    public function __construct()
    {

    }

    public function encrypt()
    {
        $mediaKey = random_bytes(32);

        $hkdf = hash_hkdf('sha256', $mediaKey, 112, 'WhatsApp Image Keys');


        $iv = substr($hkdf, 0, 16);
        $cipherKey = substr($hkdf, 16, 32);
        $macKey = substr($hkdf, 48, 32);
        $refKey = substr($hkdf, 80, 32);

        $image = file_get_contents('samples/IMAGE.original');
//        echo filesize('samples/IMAGE.original');die;

        $enc = openssl_encrypt($image, 'AES-256-CBC', $cipherKey, OPENSSL_RAW_DATA, $iv);

        $mac = substr(hash_hmac('sha256', $iv . $enc, $macKey, true), 0, 10);

        $encImage = $enc . $mac;

        file_put_contents('samples/results/IMAGE.enc', $encImage);
        file_put_contents('samples/results/IMAGE.key', $mediaKey);
    }
}