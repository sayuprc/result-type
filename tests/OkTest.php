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
    public function isOkShouldReturnTrue(): void
    {
        $this->assertTrue(new Ok(null)->isOk());
    }

    #[Test]
    public function isErrShouldReturnFalse(): void
    {
        $this->assertFalse(new Ok(null)->isErr());
    }

    #[Test]
    #[DataProvider('unwrapProvider')]
    public function unwrap(mixed $value): void
    {
        $this->assertSame($value, new Ok($value)->unwrap());
    }

    public static function unwrapProvider(): array
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

    #[Test]
    public function map(): void
    {
        $this->assertSame(4, new Ok(2)->map(fn (int $i) => $i * 2)->unwrap());
    }

    #[Test]
    public function continuousMap(): void
    {
        $this->assertSame(9, new Ok(2)->map(fn (int $i) => $i * 2)->map(fn (int $i) => $i + 5)->unwrap());
    }

    #[Test]
    public function mapErrShouldReturnOk(): void
    {
        $this->assertInstanceOf(Ok::class, new Ok(1)->mapErr(fn () => 2));
    }

    #[Test]
    public function unwrapOr(): void
    {
        $this->assertSame(1, new Ok(1)->unwrapOr('default'));
    }

    #[Test]
    public function unwrapErrOr(): void
    {
        $this->assertSame('default', new Ok(1)->unwrapErrOr('default'));
    }

    #[Test]
    public function andThen(): void
    {
        $result = new Ok(1)->andThen(fn (int $i) => new Ok($i * 2));

        $this->assertInstanceOf(Ok::class, $result);
        $this->assertSame(2, $result->unwrap());
    }

    #[Test]
    public function orElse(): void
    {
        $result = new Ok(1)->orElse(fn (int $i) => new Ok($i * 2));

        $this->assertInstanceOf(Ok::class, $result);
        $this->assertSame(1, $result->unwrap());
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
