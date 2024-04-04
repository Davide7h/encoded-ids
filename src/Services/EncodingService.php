<?php

namespace Davide7h\EncodedIds\Services;

use Sqids\Sqids;

class EncodingService
{
    private static function getDecoder()
    {
        $alphabet = config('encoded-ids.alphabet');
        $padding = config('encoded-ids.padding');
        $decoder = new Sqids($alphabet, 8);

        return $decoder;
    }

    public static function encode(int $id): string
    {
        $decoder = self::getDecoder();

        return $decoder->encode([$id]);
    }

    public static function decode(string $id): ?int
    {
        $decoder = self::getDecoder();
        $decodedData = $decoder->decode($id);

        if (! is_countable($decodedData) || ! count($decodedData)) {
            return null;
        }

        return $decodedData[0];
    }
}
