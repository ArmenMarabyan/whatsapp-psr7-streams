<?php

declare(strict_types=1);

namespace Armen\WhatsappPsr7Streams\Stream;

class Decrypt
{

    public function __construct()
    {

    }

    public function decrypt()
    {
        $mediaKey = file_get_contents('samples/results/IMAGE.key');

        $hkdf = hash_hkdf('sha256', $mediaKey, 112, 'WhatsApp Image Keys');

        $iv = substr($hkdf, 0, 16);
        $cipherKey = substr($hkdf, 16, 32);
        $macKey = substr($hkdf, 48, 32);
        $refKey = substr($hkdf, 80, 32);

        $fileEnc = file_get_contents('samples/results/IMAGE.enc');
        $file = substr($fileEnc, 0, -10);
        $mac = substr($fileEnc, -10);

        $expectedMac = substr(hash_hmac('sha256', $iv . $file, $macKey, true), 0, 10);

        if (false === hash_equals($expectedMac, $mac)) {
            echo 'Not valid MAC key'; die;
        }

        $decryptedFile = openssl_decrypt($file, 'AES-256-CBC', $cipherKey, OPENSSL_RAW_DATA, $iv);

//        $strPad = ord($dec[strlen($dec)-1]);
//        $dec = substr($dec, 0, -$strPad);

        file_put_contents('samples/results/IMAGE.original', $decryptedFile);
    }
}