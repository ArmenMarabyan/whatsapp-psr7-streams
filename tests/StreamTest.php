<?php

declare(strict_types=1);

use Armen\WhatsappPsr7Streams\Stream\Encrypt;
use Armen\WhatsappPsr7Streams\Stream\Decrypt;
use PHPUnit\Framework\TestCase;
use Nyholm\Psr7\Stream;

class StreamTest extends TestCase
{
    private string $mediaKey;
    private string $mediaType;
    private string $originalContent;

    protected function setUp(): void
    {
        $this->mediaKey = random_bytes(32);
        $this->mediaType = 'WhatsApp Video Keys';
        $this->originalContent = file_get_contents(__DIR__.'/Samples/VIDEO.original');
    }

    public function testEncryption(): void
    {
        $originalStream = Stream::create($this->originalContent);
        $encryptedStream = new Encrypt($originalStream, $this->mediaKey, $this->mediaType);

        $contents = $encryptedStream->getContents();

        $this->assertNotSame($this->originalContent, $contents);
        $this->assertSame(strlen($contents), $encryptedStream->getSize());
        $this->assertEquals(substr($contents, -10), substr($contents, -10), 'MAC appended at the end');
    }

    public function testDecryption(): void
    {
        $originalStream = Stream::create($this->originalContent);
        $encryptedStream = new Encrypt($originalStream, $this->mediaKey, $this->mediaType);

        $encryptedData = $encryptedStream->getContents();
        $encryptedDataStream = Stream::create($encryptedData);

        $decryptedStream = new Decrypt($encryptedDataStream, $this->mediaKey, $this->mediaType);
        $decryptedContent = $decryptedStream->getContents();

        $this->assertSame($this->originalContent, $decryptedContent);
    }
}
