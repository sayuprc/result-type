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
    #[DataProvider('provideGetValue')]
    public function getValue(mixed $value): void
    {
        $this->assertSame($value, new Ok($value)->getValue());
    }

    public static function provideGetValue(): array
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
    public function throwWhenCalledGetErr(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Result is not Err.');

        new Ok(null)->getErr();
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
