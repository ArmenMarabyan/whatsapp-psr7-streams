<?php

require_once __DIR__ . '/vendor/autoload.php';

use Armen\WhatsappPsr7Streams\Stream\Encrypt;
use Armen\WhatsappPsr7Streams\Stream\Decrypt;
use Armen\WhatsappPsr7Streams\MediaType;

$mediaKey = random_bytes(32);

$enc = new Encrypt();
$encryptedImage = $enc->encrypt('samples/IMAGE.original', $mediaKey, MediaType::IMAGE);

file_put_contents('samples/results/IMAGE.enc', $encryptedImage);
file_put_contents('samples/results/IMAGE.key', $mediaKey);

$dec = new Decrypt();
$dec->decrypt();
