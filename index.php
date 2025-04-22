<?php

require_once __DIR__ . '/vendor/autoload.php';

use Armen\WhatsappPsr7Streams\Stream\Encrypt;

$enc = new Encrypt();
$enc->encrypt();
