<?php

require_once __DIR__ . '/vendor/autoload.php';

use Armen\WhatsappPsr7Streams\Stream\Encrypt;
use Armen\WhatsappPsr7Streams\Stream\Decrypt;
use Armen\WhatsappPsr7Streams\MediaType;
use GuzzleHttp\Psr7\Utils;
use Nyholm\Psr7\Factory\Psr17Factory;

$mediaKey = random_bytes(32);

//$input = Utils::streamFor(file_get_contents('samples/VIDEO.original'));

$psr17Factory = new Psr17Factory();
$input = $psr17Factory->createStream(file_get_contents('samples/VIDEO.original'));

$enc = new Encrypt($input, $mediaKey, MediaType::VIDEO->value);

$dec = new Decrypt($enc, $mediaKey, MediaType::VIDEO->value);

file_put_contents('samples/results/IMAGE.enc', $enc->getContents());
file_put_contents('samples/results/IMAGE.key', $mediaKey);
file_put_contents('samples/results/IMAGE.original', $dec->getContents());
