# sayuprc/result-type

A library for handling result types in PHP.

## Requirements

|name|version|
|---|---|
|PHP|^8.4|

## Installation

```bash
composer require sayuprc/result-type
```

## Usage

```php
<?php

use ResultType\Err;
use ResultType\Ok;
use ResultType\Result;

class Success
{
    // Some data
}

class Error
{
    // Some error
}

class Handler
{
    /**
      * @return Result<Success, Error>
      */
    function handle(): Result
    {
        if (/* some error */) {
            return new Err(new Error());
        }
        
        return new Ok(new Success());
    }
}

$handler = new Handler();

$result = $handler->handle();

if ($result->isOk()) {
    $result->unwrap(); // Access to Success
} else {
    $result->unwrapErr(); // Access to Error
}
```

## Wiki

For more information, please click [here](https://deepwiki.com/sayuprc/result-type).
