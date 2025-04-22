<?php

declare(strict_types=1);

namespace Armen\WhatsappPsr7Streams\Stream;

use Armen\WhatsappPsr7Streams\MediaType;

class Encrypt
{

    public function __construct()
    {

    }

    public function encrypt(string $filePath, string $mediaKey, MediaType $mediaType) :string
    {

        $hkdf = hash_hkdf('sha256', $mediaKey, 112, $mediaType->value);

        $iv = substr($hkdf, 0, 16);
        $cipherKey = substr($hkdf, 16, 32);
        $macKey = substr($hkdf, 48, 32);
        $refKey = substr($hkdf, 80, 32);

        $image = file_get_contents($filePath);

        $encryptedImage = openssl_encrypt($image, 'AES-256-CBC', $cipherKey, OPENSSL_RAW_DATA, $iv);

        $mac = substr(hash_hmac('sha256', $iv . $encryptedImage, $macKey, true), 0, 10);

        $encryptedImageWithMac = $encryptedImage . $mac;

        return $encryptedImageWithMac;
    }
}