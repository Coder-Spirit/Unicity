# Unicity Library

[![Build Status](https://travis-ci.org/Litipk/Unicity.svg?branch=master)](https://travis-ci.org/Litipk/Unicity)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Litipk/Unicity/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Litipk/Unicity/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/Litipk/Unicity/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Litipk/Unicity/?branch=master)

## Introduction

The Unicity library serves to the purpose of generating globally unique IDs ensuring some properties:

  * High (but controllable) degree of entropy/randomness:
    * Required to avoid ID guessing attacks.
  * Time-related ID (with controllable precision, and proper internal representation):
    * Time-related IDs (using a proper internal representation: Big-Endian) are a good helper to preserve the natural
      order of insertion in our DBs.
    * In addition, using time-related IDs allows us to use B-Tree indexes without having to recreate the index with
      almost every insertion due to randomness.
  * The possibility of representing the ID using multiple formats, including compact binary strings:
    * Base64
    * Base64 with an adaptation to be used in URLs
    * Hexadecimal
    * Binary string (the most compact form, ideal to ensure small DB indexes)

## Setup

Install it through composer, that's it:
```bash
composer require unicity/unicity
```

## Code examples

### The Factory

```php
<?php

use Unicity\GUIDFactory;

// A good place to start is to instantiate a factory that will help us to create
// or to unserialize GUIDs following the specified constraints.
// 

// The first parameter tells us how many timestamp-related bytes we want to use
//   (from 4 to 7 bytes, by default 7)
// The second parameter tells us how many randomness bytes we want to use
//   (from 2 to 9 bytes, by default 9)
$guidFactory = new \Unicity\GUIDFactory(5, 7);

```

An important point to take into account is that, for testing and dependency injection purposes, you should type hint
using the `Unicity\Interfaces\GUIDFactory` interface.

The same point applies to GUID instances, its better to type hint using the `Unicity\Interfaces\GUID` interface.

### Creating new GUID instances

```php
<?php

// This will create a completely new GUID following the constraints specified in the factory constructor
$newGUID = $guidFactory->create();

```

### Unserializing GUIDs

```php
<?php

// This will unserialize (and validate) a GUID from an hexadecimal string
$recoveredGUID = $guidFactory->fromHexString('1234567890ab1234567890ab');

// This will unserialize (and validate) a GUID from a base64 string
$recoveredGUID = $guidFactory->fromBase64String('EjRWeJCrEjRWeJCr');

// This will unserialize (and validate) a GUID from a base64 (modified for URLs) string
$recoveredGUID = $guidFactory->fromBase64UrlString('EjRWeJCrEjRWeJCr');

// This will unserialize (and validate) a GUID from a binary bytes stream
$recoveredGUID = $guidFactory->fromBinaryString('1234567890ab');

```

### Serializing GUIDs

```php
<?php

$hexString = $newGUID->asHexString();
$base64String = $newGUID->asBase64String();
$base64urlString = $newGUID->asBase64UrlString();
$bytesStream = $newGUID->asBinaryString();

```

### Comparing GUIDs

```php
<?php

if (!$requestGUID->equals($dbGUID)) {
    // block request
}

```

