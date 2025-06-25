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
    public function isOkShouldReturnFalse(): void
    {
        $this->assertFalse(new Err(null)->isOk());
    }

    #[Test]
    public function isErrShouldReturnTrue(): void
    {
        $this->assertTrue(new Err(null)->isErr());
    }

    #[Test]
    public function throwWhenCalledUnwrap(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Result is not Ok.');

        new Err(null)->unwrap();
    }

    #[Test]
    #[DataProvider('unwrapErrProvider')]
    public function unwrapErr(mixed $value): void
    {
        $this->assertSame($value, new Err($value)->unwrapErr());
    }

    public static function unwrapErrProvider(): array
    {
        return [
            [null],
            [1],
            ['string value'],
            [new stdClass()],
            [new ErrValue(1, 'name')],
        ];
    }

    #[Test]
    public function mapShouldReturnOk(): void
    {
        $this->assertInstanceOf(Err::class, new Err(1)->mapErr(fn () => 2));
    }

    #[Test]
    public function mapErr(): void
    {
        $this->assertInstanceOf(Err::class, new Err(1)->mapErr(fn () => 2));
    }

    #[Test]
    public function continuousMapErr(): void
    {
        $this->assertSame(9, new Err(2)->mapErr(fn (int $i) => $i * 2)->mapErr(fn (int $i) => $i + 5)->unwrapErr());
    }

    #[Test]
    public function unwrapOr(): void
    {
        $this->assertSame('default', new Err(1)->unwrapOr('default'));
    }

    #[Test]
    public function unwrapErrOr(): void
    {
        $this->assertSame(1, new Err(1)->unwrapErrOr('default'));
    }

    #[Test]
    public function andThen(): void
    {
        $result = new Err(1)->andThen(fn (int $i) => new Err($i * 2));

        $this->assertInstanceOf(Err::class, $result);
        $this->assertSame(1, $result->unwrapErr());
    }

    #[Test]
    public function orElse(): void
    {
        $result = new Err(1)->orElse(fn (int $i) => new Err($i * 2));

        $this->assertInstanceOf(Err::class, $result);
        $this->assertSame(2, $result->unwrapErr());
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
