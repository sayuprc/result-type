<?php

declare(strict_types=1);

namespace Tests\Eager;

use Closure;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ResultType\Eager\Ok;
use ResultType\Lazy\Ok as LazyOk;
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
    #[DataProvider('mapProvider')]
    public function map(mixed $expected, mixed $initial, Closure $callback): void
    {
        $result = new Ok($initial)->map($callback);

        $this->assertInstanceOf(Ok::class, $result);
        $this->assertSame($expected, $result->unwrap());
    }

    public static function mapProvider(): array
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
    #[DataProvider('continuousMapProvider')]
    public function continuousMap(mixed $expected, mixed $initial, Closure $callback1, Closure $callback2): void
    {
        $result = new Ok($initial)
            ->map($callback1)
            ->map($callback2);

        $this->assertInstanceOf(Ok::class, $result);
        $this->assertSame($expected, $result->unwrap());
    }

    public static function continuousMapProvider(): array
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
    public function mapErrShouldReturnOk(): void
    {
        $result = new Ok(1)->mapErr(fn (int $i) => $i * 2);

        $this->assertInstanceOf(Ok::class, $result);
        $this->assertSame(1, $result->unwrap());
    }

    #[Test]
    public function mapWithMapErr(): void
    {
        $result = new Ok(1)
            ->map(fn (int $i) => $i * 2)
            ->mapErr(fn (int $i) => $i * 3)
            ->map(fn (int $i) => $i * 4);

        $this->assertInstanceOf(Ok::class, $result);
        $this->assertSame(8, $result->unwrap());
    }

    #[Test]
    public function mapErrWithMap(): void
    {
        $result = new Ok(1)
            ->mapErr(fn (int $i) => $i * 2)
            ->map(fn (int $i) => $i * 3)
            ->mapErr(fn (int $i) => $i * 4);

        $this->assertInstanceOf(Ok::class, $result);
        $this->assertSame(3, $result->unwrap());
    }

    #[Test]
    #[DataProvider('unwrapOrProvider')]
    public function unwrapOr(mixed $expected, mixed $default): void
    {
        $this->assertSame($expected, new Ok($expected)->unwrapOr($default));
    }

    public static function unwrapOrProvider(): array
    {
        return [
            [1, 0],
            ['value', 'default'],
        ];
    }

    #[Test]
    #[DataProvider('unwrapErrOrProvider')]
    public function unwrapErrOr(mixed $expected, mixed $initial): void
    {
        $this->assertSame($expected, new Ok($initial)->unwrapErrOr($expected));
    }

    public static function unwrapErrOrProvider(): array
    {
        return [
            [0, 1],
            ['default', 'value'],
        ];
    }

    #[Test]
    public function andThen(): void
    {
        $result = new Ok(1)->andThen(fn (int $i) => new Ok($i * 2));

        $this->assertInstanceOf(Ok::class, $result);
        $this->assertSame(2, $result->unwrap());
    }

    #[Test]
    public function andThenAfterOrElse(): void
    {
        $result = new Ok(1)
            ->orElse(fn (int $i) => new Ok($i * 2))
            ->andThen(fn (int $i) => new Ok($i * 3));

        $this->assertInstanceOf(Ok::class, $result);
        $this->assertSame(3, $result->unwrap());
    }

    #[Test]
    public function orElse(): void
    {
        $result = new Ok(1)->orElse(fn (int $i) => new Ok($i * 2));

        $this->assertInstanceOf(Ok::class, $result);
        $this->assertSame(1, $result->unwrap());
    }

    #[Test]
    public function orElseAfterAndThen(): void
    {
        $result = new Ok(1)
            ->andThen(fn (int $i) => new Ok($i * 2))
            ->orElse(fn (int $i) => new Ok($i * 3));

        $this->assertInstanceOf(Ok::class, $result);
        $this->assertSame(2, $result->unwrap());
    }

    #[Test]
    public function match(): void
    {
        $this->assertSame(1, new Ok(1)->match(fn () => 1, fn () => 2));
    }

    #[Test]
    public function toLazy(): void
    {
        $reesult = new Ok(1)->toLazy();

        $this->assertInstanceOf(LazyOk::class, $reesult);
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
