<?php

declare(strict_types=1);

namespace Tests;

use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ResultType\Err;
use ResultType\Result;
use stdClass;

class ErrTest extends TestCase
{
    #[Test]
    public function isImplementsResult(): void
    {
        $this->assertInstanceOf(Result::class, new Err(null));
    }

    #[Test]
    public function isOk(): void
    {
        $this->assertFalse(new Err(null)->isOk());
    }

    #[Test]
    public function throwWhenCalledGetValue(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Result is not Ok.');

        new Err(null)->getValue();
    }

    #[Test]
    #[DataProvider('provideGetErr')]
    public function getErr(mixed $value): void
    {
        $this->assertSame($value, new Err($value)->getErr());
    }

    public static function provideGetErr(): array
    {
        return [
            [null],
            [1],
            ['string value'],
            [new stdClass()],
            [new ErrValue(1, 'name')],
        ];
    }
}

class ErrValue
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
    ) {
    }
}
