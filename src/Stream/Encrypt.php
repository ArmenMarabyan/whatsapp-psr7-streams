<?php

declare(strict_types=1);

namespace Armen\WhatsappPsr7Streams\Stream;

use Psr\Http\Message\StreamInterface;

final class Encrypt implements StreamInterface
{
    /**
     * @var string
     */
    private string $cipherKey;
    /**
     * @var string
     */
    private string $macKey;
    /**
     * @var string
     */
    private string $iv;

    /**
     * @var string
     */
    private string $encryptedData;
    /**
     * @var int
     */
    private int $position = 0;

    /**
     * @param StreamInterface $stream
     * @param string $mediaKey
     * @param string $mediaType
     */
    public function __construct(private StreamInterface $stream, string $mediaKey, string $mediaType)
    {
        $mediaKeyExpanded = Utils::getMediaKeyExpanded('sha256', $mediaKey, 112, $mediaType);

        $this->iv = $mediaKeyExpanded['iv'];
        $this->cipherKey = $mediaKeyExpanded['cipherKey'];
        $this->macKey = $mediaKeyExpanded['macKey'];

        $this->encrypt();
    }

    /**
     * @return void
     */
    private function encrypt(): void
    {
        $this->stream->rewind();
        $plainData = $this->stream->getContents();

        $cipher = openssl_encrypt(
            $plainData,
            'AES-256-CBC',
            $this->cipherKey,
            OPENSSL_RAW_DATA,
            $this->iv
        );

        $mac = substr(hash_hmac('sha256', $this->iv . $cipher, $this->macKey, true), 0, 10);

        $this->encryptedData = $cipher . $mac;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $this->rewind();
        return $this->getContents();
    }

    /**
     * @return void
     */
    public function close(): void
    {
        $this->stream->close();
    }

    /**
     * @return mixed
     */
    public function detach(): mixed
    {
        return $this->stream->detach();
    }

    /**
     * @return int|null
     */
    public function getSize(): ?int
    {
        return !empty($this->encryptedData) ? strlen($this->encryptedData) : null;
    }

    /**
     * @return int
     */
    public function tell(): int
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function eof(): bool
    {
        return $this->position >= strlen($this->encryptedData);
    }

    /**
     * @return bool
     */
    public function isSeekable(): bool
    {
        return true;
    }

    /**
     * @param $offset
     * @param $whence
     * @return void
     */
    public function seek($offset, $whence = SEEK_SET): void
    {
        $length = strlen($this->encryptedData);
        match ($whence) {
            SEEK_SET => $this->position = $offset,
            SEEK_CUR => $this->position += $offset,
            SEEK_END => $this->position = $length + $offset,
            default => $this->position = $offset
        };
    }

    /**
     * @return void
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @return bool
     */
    public function isWritable(): bool
    {
        return false;
    }

    /**
     * @param string $string
     * @return int
     */
    public function write(string $string): int
    {
        throw new \RuntimeException("Encrypted data is read only");
    }

    /**
     * @return bool
     */
    public function isReadable(): bool
    {
        return true;
    }

    /**
     * @param $length
     * @return string
     */
    public function read($length): string
    {
        $chunk = substr($this->encryptedData, $this->position, $length);
        $this->position += strlen($chunk);
        return $chunk;
    }

    /**
     * @return string
     */
    public function getContents(): string
    {
        $contents = substr($this->encryptedData, $this->position);
        $this->position += strlen($contents);
        return $contents;
    }

    /**
     * @param $key
     * @return array|null
     */
    public function getMetadata($key = null): array|null
    {
        return $this->stream->getMetadata($key);
    }

    /**
     * Closes the stream when the destructed
     */
    public function __destruct()
    {
        $this->stream->close();
    }
}
