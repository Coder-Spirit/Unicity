<?php
declare(strict_types=1);

namespace Unicity;

class GUID implements GloballyUniqueIdentifier
{
    /** @var string */
    private $bytes;

    private function __construct(string $bytes)
    {
        $this->bytes = $bytes;
    }

    public static function create(int $nTimeBytes = 7, int $nRandomBytes = 9): GUID
    {
        return self::fromBinaryString(
            self::getTimeBytes($nTimeBytes) . \random_bytes(\max(2, \min(9, $nRandomBytes)))
        );
    }

    public static function fromBinaryString(string $binStr): GUID
    {
        $strLen = \strlen($binStr);
        if (0 !== $strLen % 2) {
            throw new GUIDInvariantsViolationError('IDs must have an even number of bytes when stored as a binary string');
        }
        if (8 > $strLen) {
            throw new GUIDInvariantsViolationError('IDs must have at least 8 bytes of entropy');
        }

        return new GUID($binStr);
    }

    public static function fromHexString(string $hexStr): GUID
    {
        if (0 === \preg_match('/^(([0-9A-F]{2})+|([0-9a-f]{2})+)$/', $hexStr)) {
            throw new UnserializationError('Invalid hexadecimal string');
        }

        return self::fromBinaryString(\hex2bin($hexStr));
    }

    public static function fromBase64String(string $b64Str): GUID
    {
        if (0 === \preg_match('^(?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=)?$', $b64Str)) {
            throw new UnserializationError('Invalid base64 string');
        }

        return self::fromBinaryString(\base64_decode($b64Str, true));
    }

    public static function fromBase64UrlString(string $b64Str): GUID
    {
        return self::fromBase64String(\strtr($b64Str, '-_.', '+/='));
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

    public function equals(GloballyUniqueIdentifier $guid): bool
    {
        return $guid->asBinaryString() === $this->bytes;
    }

    private static function getTimeBytes(int $nTimeBytes): string
    {
        $ts_parts = explode(' ', microtime());

        $nTimeBytes = \max(4, \min(7, $nTimeBytes));
        $micros = ((int)\round($ts_parts[0] * 1000000) + $ts_parts[1] * 1000000) % (2 ** ($nTimeBytes << 3));

        $timeBytes = \str_pad('', $nTimeBytes, \chr(0));

        for ($i = $nTimeBytes - 1; $i >= 0; $i--) {
            $timeByte = \chr($micros & 0xff);
            $timeBytes[$i] = $timeByte;
            $micros = ($micros - $timeByte) >> 8;
        }

        return $timeBytes;
    }
}
