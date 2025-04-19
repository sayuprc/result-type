<?php

declare(strict_types=1);

namespace Tests;

use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ResultType\Ok;
use ResultType\Result;
use stdClass;

class OkTest extends TestCase
{
    #[Test]
    public function isImplementsResult(): void
    {
        $this->assertInstanceOf(Result::class, new Ok(null));
    }

    #[Test]
    public function isOk(): void
    {
        $this->assertTrue(new Ok(null)->isOk());
    }

    #[Test]
    public function isErr(): void
    {
        $this->assertFalse(new Ok(null)->isErr());
    }

    #[Test]
    #[DataProvider('provideUnwrap')]
    public function unwrap(mixed $value): void
    {
        $this->assertSame($value, new Ok($value)->unwrap());
    }

    public static function provideUnwrap(): array
    {
        return [
            [null],
            [1],
            ['string value'],
            [new stdClass()],
            [new OkValue(1, 'name')],
        ];
    }

    #[Test]
    public function throwWhenCalledUnwrapErr(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Result is not Err.');

        new Ok(null)->unwrapErr();
    }
}

class OkValue
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {
    }
}
