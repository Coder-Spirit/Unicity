<?php
declare(strict_types=1);

namespace Unicity\Interfaces;

interface GUIDFactory
{
    public function create(): GUID;
    public function fromBinaryString(string $binStr): GUID;
    public function fromHexString(string $hexStr): GUID;
    public function fromBase64String(string $b64Str): GUID;
    public function fromBase64UrlString(string $b64Str): GUID;
}
