<?php

declare(strict_types=1);

namespace Tests\Eager;

use Closure;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ResultType\Eager\Err;
use ResultType\Lazy\Err as LazyErr;
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
    public function mapShouldReturnErr(): void
    {
        $result = new Err(1)->map(fn (int $i) => $i * 2);

        $this->assertInstanceOf(Err::class, $result);
        $this->assertSame(1, $result->unwrapErr());
    }

    #[Test]
    #[DataProvider('mapErrProvider')]
    public function mapErr(mixed $expected, mixed $initial, Closure $callback): void
    {
        $result = new Err($initial)->mapErr($callback);

        $this->assertInstanceOf(Err::class, $result);
        $this->assertSame($expected, $result->unwrapErr());
    }

    public static function mapErrProvider(): array
    {
        return [
            [
                4,
                2,
                fn (int $i) => $i * 2,
            ],
            [
                'FizzBuzz',
                'Fizz',
                fn (string $i) => $i . 'Buzz',
            ],
        ];
    }

    #[Test]
    #[DataProvider('continuousMapErrProvider')]
    public function continuousMapErr(mixed $expected, mixed $initial, Closure $callback1, Closure $callback2): void
    {
        $result = new Err($initial)
            ->mapErr($callback1)
            ->mapErr($callback2);

        $this->assertInstanceOf(Err::class, $result);
        $this->assertSame($expected, $result->unwrapErr());
    }

    public static function continuousMapErrProvider(): array
    {
        return [
            [
                9,
                2,
                fn (int $i) => $i * 2,
                fn (int $i) => $i + 5,
            ],
            [
                'FIZZBUZZ',
                'fizz',
                fn (string $i) => $i . 'buzz',
                fn (string $i) => strtoupper($i),
            ],
        ];
    }

    #[Test]
    public function mapErrWithMap(): void
    {
        $result = new Err(1)
            ->mapErr(fn (int $i) => $i * 2)
            ->map(fn (int $i) => $i * 3)
            ->mapErr(fn (int $i) => $i * 4);

        $this->assertInstanceOf(Err::class, $result);
        $this->assertSame(8, $result->unwrapErr());
    }

    #[Test]
    public function mapWithMapErr(): void
    {
        $result = new Err(1)
            ->map(fn (int $i) => $i * 2)
            ->mapErr(fn (int $i) => $i * 3)
            ->map(fn (int $i) => $i * 4);

        $this->assertInstanceOf(Err::class, $result);
        $this->assertSame(3, $result->unwrapErr());
    }

    #[Test]
    #[DataProvider('unwrapOrProvider')]
    public function unwrapOr(mixed $expected, mixed $initial): void
    {
        $this->assertSame($expected, new Err($initial)->unwrapOr($expected));
    }

    public static function unwrapOrProvider(): array
    {
        return [
            [0, 1],
            ['default', 'value'],
        ];
    }

    #[Test]
    #[DataProvider('unwrapErrOrProvider')]
    public function unwrapErrOr(mixed $expected, mixed $default): void
    {
        $this->assertSame($expected, new Err($expected)->unwrapErrOr($default));
    }

    public static function unwrapErrOrProvider(): array
    {
        return [
            [1, 0],
            ['value', 'default'],
        ];
    }

    #[Test]
    public function andThen(): void
    {
        $result = new Err(1)->andThen(fn (int $i) => new Err($i * 2));

        $this->assertInstanceOf(Err::class, $result);
        $this->assertSame(1, $result->unwrapErr());
    }

    #[Test]
    public function andThenAfterOrElse(): void
    {
        $result = new Err(1)
            ->orElse(fn (int $i) => new Err($i * 2))
            ->andThen(fn (int $i) => new Err($i * 3));

        $this->assertInstanceOf(Err::class, $result);
        $this->assertSame(2, $result->unwrapErr());
    }

    #[Test]
    public function orElse(): void
    {
        $result = new Err(1)->orElse(fn (int $i) => new Err($i * 2));

        $this->assertInstanceOf(Err::class, $result);
        $this->assertSame(2, $result->unwrapErr());
    }

    #[Test]
    public function orElseAfterAndThen(): void
    {
        $result = new Err(1)
            ->andThen(fn (int $i) => new Err($i * 2))
            ->orElse(fn (int $i) => new Err($i * 3));

        $this->assertInstanceOf(Err::class, $result);
        $this->assertSame(3, $result->unwrapErr());
    }

    #[Test]
    public function match(): void
    {
        $this->assertSame(2, new Err(1)->match(fn () => 1, fn () => 2));
    }

    #[Test]
    public function toLazy(): void
    {
        $result = new Err(1)->toLazy();

        $this->assertInstanceOf(LazyErr::class, $result);
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
