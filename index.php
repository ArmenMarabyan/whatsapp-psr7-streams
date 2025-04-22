<?php

require_once __DIR__ . '/vendor/autoload.php';

use Armen\WhatsappPsr7Streams\Stream\Encrypt;
use Armen\WhatsappPsr7Streams\Stream\Decrypt;

$enc = new Encrypt();
$enc->encrypt();

$dec = new Decrypt();
$dec->decrypt();
