<?php
declare(strict_types=1);

namespace Unicity;

interface GloballyUniqueIdentifier
{
    function asBinaryString(): string;
    function asHexString(): string;
    function asBase64String(): string;
    function asBase64UrlString(): string;
    function numBits(): int;
    function equals(GloballyUniqueIdentifier $guid): bool;
}
