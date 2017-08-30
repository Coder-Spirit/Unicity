<?php
declare(strict_types=1);

namespace Unicity;

use Unicity\Errors\GUIDInvariantsViolationError;
use Unicity\Errors\UnserializationError;
use Unicity\Interfaces\GUID as GUIDInterface;

class GUID implements GUIDInterface
{
    const DEFAULT_GUID_SIZE = 16;

    /** @var string */
    private $bytes;

    private function __construct(string $bytes)
    {
        $this->bytes = $bytes;
    }

    public static function create(int $nTimeBytes = 7, int $nRandomBytes = 9): GUID
    {
        $nTimeBytes = \max(4, \min(7, $nTimeBytes));
        $nRandomBytes = \max(2, \min(9, $nRandomBytes));

        return self::fromBinaryString(
            self::getTimeBytes($nTimeBytes) . \random_bytes($nRandomBytes),
            $nTimeBytes + $nRandomBytes
        );
    }

    public static function fromBinaryString(string $binStr, int $expectedLength = self::DEFAULT_GUID_SIZE): GUID
    {
        $strLen = \strlen($binStr);
        if (6 > $expectedLength) {
            throw new GUIDInvariantsViolationError('IDs must have at least 6 bytes of entropy');
        }
        if ($expectedLength !== $strLen) {
            throw new UnserializationError(
                'The passed string has an unexpected length ' .
                \json_encode(['expected' => $expectedLength, 'given' => $strLen])
            );
        }

        return new GUID($binStr);
    }

    public static function fromHexString(string $hexStr, int $expectedLength = self::DEFAULT_GUID_SIZE): GUID
    {
        if (0 === \preg_match('/^(([0-9A-F]{2})+|([0-9a-f]{2})+)$/', $hexStr)) {
            throw new UnserializationError('Invalid hexadecimal string');
        }

        return self::fromBinaryString(
            \hex2bin($hexStr),
            $expectedLength
        );
    }

    public static function fromBase64String(string $b64Str, int $expectedLength = self::DEFAULT_GUID_SIZE): GUID
    {
        if (0 === \preg_match('#^(?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=)?$#', $b64Str)) {
            throw new UnserializationError('Invalid base64 string');
        }

        return self::fromBinaryString(
            \base64_decode($b64Str, true),
            $expectedLength
        );
    }

    public static function fromBase64UrlString(string $b64Str, int $expectedLength = self::DEFAULT_GUID_SIZE): GUID
    {
        if (0 === \preg_match('#^(?:[A-Za-z0-9\-_]{4})*(?:[A-Za-z0-9\-_]{2}\.\.|[A-Za-z0-9\-_]{3}\.)?$#', $b64Str)) {
            throw new UnserializationError('Invalid base64 string');
        }

        return self::fromBase64String(
            \strtr($b64Str, '-_.', '+/='),
            $expectedLength
        );
    }

    public function asBinaryString(): string
    {
        return $this->bytes;
    }

    public function asHexString(): string
    {
        return \bin2hex($this->bytes);
    }

    public function asBase64String(): string
    {
        return \base64_encode($this->bytes);
    }

    public function asBase64UrlString(): string
    {
        return \strtr(\base64_encode($this->bytes), '+/=', '-_.');
    }

    public function numBits(): int
    {
        return (\strlen($this->bytes) << 3);
    }

    public function equals(GUIDInterface $guid): bool
    {
        return $guid->asBinaryString() === $this->bytes;
    }

    public function __toString(): string
    {
        return $this->asBase64UrlString();
    }

    private static function getTimeBytes(int $nTimeBytes): string
    {
        $bytesPool = self::getAdjustedTimeBytes($nTimeBytes);

        $timeBytes = \str_pad('', $nTimeBytes, \chr(0));

        for ($i = $nTimeBytes - 1; $i >= 0; $i--) {
            $timeByte = $bytesPool & 0xff;
            $timeBytes[$i] = \chr($timeByte);
            $bytesPool = ($bytesPool - $timeByte) >> 8;
        }

        return $timeBytes;
    }

    private static function getAdjustedTimeBytes(int $nTimeBytes): int
    {
        $ts_parts = \explode(' ', microtime());
        $micros = (int)\round($ts_parts[0] * 1000000) + ((int)$ts_parts[1]) * 1000000;

        switch ($nTimeBytes) {
            case 7: return $micros;
            case 6: return (int)($micros / 1000);
            default: return (int)($micros / 1000000);
        }
    }

    private function __clone()
    {
        throw new \BadMethodCallException('GUID objects must not be cloned');
    }
}
