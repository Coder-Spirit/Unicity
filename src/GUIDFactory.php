<?php
declare(strict_types=1);

namespace Unicity;

use Unicity\Interfaces\GUID as GUIDInterface;
use Unicity\Interfaces\GUIDFactory as GUIDFactoryInterface;

class GUIDFactory implements GUIDFactoryInterface
{
    /** @var int */
    private $nTimeBytes;

    /** @var int */
    private $nRandomBytes;

    /** @var int */
    private $expectedLength;

    public function __construct(int $nTimeBytes = 7, int $nRandomBytes = 9)
    {
        $this->nTimeBytes = \max(4, \min(7, $nTimeBytes));
        $this->nRandomBytes = \max(2, \min(9, $nRandomBytes));
        $this->expectedLength = $this->nTimeBytes + $this->nRandomBytes;
    }

    /**
     * @return GUID|GUIDInterface
     */
    public function create(): GUIDInterface
    {
        return GUID::create($this->nTimeBytes, $this->nRandomBytes);
    }

    /**
     * @param string $binStr
     * @return GUID|GUIDInterface
     */
    public function fromBinaryString(string $binStr): GUIDInterface
    {
        return GUID::fromBinaryString($binStr, $this->expectedLength);
    }

    /**
     * @param string $hexStr
     * @return GUID|GUIDInterface
     */
    public function fromHexString(string $hexStr): GUIDInterface
    {
        return GUID::fromHexString($hexStr, $this->expectedLength);
    }

    /**
     * @param string $b64Str
     * @return GUID|GUIDInterface
     */
    public function fromBase64String(string $b64Str): GUIDInterface
    {
        return GUID::fromBase64String($b64Str, $this->expectedLength);
    }

    /**
     * @param string $b64Str
     * @return GUID|GUIDInterface
     */
    public function fromBase64UrlString(string $b64Str): GUIDInterface
    {
        return GUID::fromBase64UrlString($b64Str, $this->expectedLength);
    }
}
