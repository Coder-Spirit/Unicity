<?php
declare(strict_types=1);

namespace Unicity\Interfaces;

interface GUID
{
    public function asBinaryString(): string;
    public function asHexString(): string;
    public function asBase64String(): string;
    public function asBase64UrlString(): string;
    public function numBits(): int;
    public function equals(GUID $guid): bool;
}
