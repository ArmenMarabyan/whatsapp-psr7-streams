# 📦 WhatsApp-Compatible PSR-7 Stream Encryption/Decryption

Это библиотека-декоратор для PSR-7 потоков, обеспечивающая шифрование и дешифрование медиафайлов по алгоритму, совместимому с WhatsApp.

## 🛠 Install

```bash
composer require armen/whatsapp-psr7-streams
```

## Usage

```php

use Armen\WhatsappPsr7Streams\Stream\Encrypt;
use Armen\WhatsappPsr7Streams\Stream\Decrypt;
use Armen\WhatsappPsr7Streams\MediaType;
use GuzzleHttp\Psr7\Utils;
use Nyholm\Psr7\Factory\Psr17Factory;

$mediaKey = random_bytes(32);

// $originalStream = Utils::streamFor(file_get_contents('samples/IMAGE.original'));
//or
$psr17Factory = new Psr17Factory();
$originalStream = $psr17Factory->createStream(file_get_contents('samples/IMAGE.original'));

//Шифрование
$enc = new Encrypt($input, $mediaKey, MediaType::IMAGE->value);

//Дешифрование
$dec = new Decrypt($enc, $mediaKey, MediaType::IMAGE->value);

file_put_contents('samples/results/IMAGE.enc', $enc->getContents());
file_put_contents('samples/results/IMAGE.original', $dec->getContents());
```

## TODO:

[ ] Покрытие тестами