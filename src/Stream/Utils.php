<?php

namespace Armen\WhatsappPsr7Streams\Stream;

class Utils
{
    /**
     * @param string $algorithm
     * @param string $mediaKey
     * @param int $length
     * @param string $mediaType
     * @return array
     */
    public static function getMediaKeyExpanded(
        string $algorithm,
        string $mediaKey,
        int $length,
        string $mediaType
    ): array {
        $hkdf = hash_hkdf($algorithm, $mediaKey, $length, $mediaType);

        $iv = substr($hkdf, 0, 16);
        $cipherKey = substr($hkdf, 16, 32);
        $macKey = substr($hkdf, 48, 32);
        $refKey = substr($hkdf, 80, 32);

        return [
            'iv' => $iv,
            'cipherKey' => $cipherKey,
            'macKey' => $macKey,
            'refKey' => $refKey
        ];
    }
}
