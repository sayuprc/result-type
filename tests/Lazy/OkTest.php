<?php

declare(strict_types=1);

namespace Tests\Lazy;

use Closure;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ResultType\Lazy\Ok;
use ResultType\Result;
use stdClass;

class OkTest extends TestCase
{
    #[Test]
    public function isImplementsResult(): void
    {
        $this->assertInstanceOf(Result::class, new Ok(fn () => null));
    }

    #[Test]
    public function isOkShouldReturnTrue(): void
    {
        $this->assertTrue(new Ok(fn () => null)->isOk());
    }

    #[Test]
    public function isErrShouldReturnFalse(): void
    {
        $this->assertFalse(new Ok(fn () => null)->isErr());
    }

    #[Test]
    #[DataProvider('unwrapProvider')]
    public function unwrap(mixed $expected, Closure $value): void
    {
        $this->assertSame($expected, new Ok($value)->unwrap());
    }

    public static function unwrapProvider(): array
    {
        return [
            [null, fn () => null],
            [1, fn () => 1],
            ['string value', fn () => 'string value'],
            [$stdClass = new stdClass(), fn () => $stdClass],
            [$ok = new OkValue(1, 'name'), fn () => $ok],
        ];
    }

    #[Test]
    public function throwWhenCalledUnwrapErr(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Result is not Err.');

        new Ok(fn () => null)->unwrapErr();
    }

    #[Test]
    #[DataProvider('mapProvider')]
    public function map(mixed $expected, Closure $initial, Closure $callback): void
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
                fn () => 2,
                fn (int $i) => $i * 2,
            ],
            [
                'FizzBuzz',
                fn () => 'Fizz',
                fn (string $i) => $i . 'Buzz',
            ],
        ];
    }

    #[Test]
    #[DataProvider('continuousMapProvider')]
    public function continuousMap(mixed $expected, Closure $initial, Closure $callback1, Closure $callback2): void
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
                fn () => 2,
                fn (int $i) => $i * 2,
                fn (int $i) => $i + 5,
            ],
            [
                'FIZZBUZZ',
                fn () => 'fizz',
                fn (string $i) => $i . 'buzz',
                fn (string $i) => strtoupper($i),
            ],
        ];
    }

    #[Test]
    public function mapErrShouldReturnOk(): void
    {
        $result = new Ok(fn () => 1)->mapErr(fn (int $i) => $i * 2);

        $this->assertInstanceOf(Ok::class, $result);
        $this->assertSame(1, $result->unwrap());
    }

    #[Test]
    public function mapWithMapErr(): void
    {
        $result = new Ok(fn () => 1)
            ->map(fn (int $i) => $i * 2)
            ->mapErr(fn (int $i) => $i * 3)
            ->map(fn (int $i) => $i * 4);

        $this->assertInstanceOf(Ok::class, $result);
        $this->assertSame(8, $result->unwrap());
    }

    #[Test]
    public function mapErrWithMap(): void
    {
        $result = new Ok(fn () => 1)
            ->mapErr(fn (int $i) => $i * 2)
            ->map(fn (int $i) => $i * 3)
            ->mapErr(fn (int $i) => $i * 4);

        $this->assertInstanceOf(Ok::class, $result);
        $this->assertSame(3, $result->unwrap());
    }

    #[Test]
    #[DataProvider('unwrapOrProvider')]
    public function unwrapOr(mixed $expected, Closure $initial, mixed $default): void
    {
        $this->assertSame($expected, new Ok($initial)->unwrapOr($default));
    }

    public static function unwrapOrProvider(): array
    {
        return [
            [1, fn () => 1, 0],
            ['value', fn () => 'value', 'default'],
        ];
    }

    #[Test]
    #[DataProvider('unwrapErrOrProvider')]
    public function unwrapErrOr(mixed $expected, Closure $initial): void
    {
        $this->assertSame($expected, new Ok($initial)->unwrapErrOr($expected));
    }

    public static function unwrapErrOrProvider(): array
    {
        return [
            [0, fn () => 1],
            ['default', fn () => 'value'],
        ];
    }

    #[Test]
    public function andThen(): void
    {
        $result = new Ok(fn () => 1)->andThen(fn (int $i) => new Ok(fn () => $i * 2));

        $this->assertInstanceOf(Ok::class, $result);
        $this->assertSame(2, $result->unwrap());
    }

    #[Test]
    public function andThenAfterOrElse(): void
    {
        $result = new Ok(fn () => 1)
            ->orElse(fn (int $i) => new Ok(fn () => $i * 2))
            ->andThen(fn (int $i) => new Ok(fn () => $i * 3));

        $this->assertInstanceOf(Ok::class, $result);
        $this->assertSame(3, $result->unwrap());
    }

    #[Test]
    public function orElse(): void
    {
        $result = new Ok(fn () => 1)->orElse(fn (int $i) => new Ok(fn () => $i * 2));

        $this->assertInstanceOf(Ok::class, $result);
        $this->assertSame(1, $result->unwrap());
    }

    #[Test]
    public function orElseAfterAndThen(): void
    {
        $result = new Ok(fn () => 1)
            ->andThen(fn (int $i) => new Ok(fn () => $i * 2))
            ->orElse(fn (int $i) => new Ok(fn () => $i * 3));

        $this->assertInstanceOf(Ok::class, $result);
        $this->assertSame(2, $result->unwrap());
    }

    #[Test]
    public function match(): void
    {
        $this->assertSame(1, new Ok(fn () => 1)->match(fn () => 1, fn () => 2));
    }

    #[Test]
    public function isLazy(): void
    {
        $counter = 0;

        $result = new Ok(function () use (&$counter) {
            $counter++;

            return $counter;
        });

        $mapCounter = 10;

        $map = $result->map(function () use (&$mapCounter) {
            $mapCounter++;

            return $mapCounter;
        });

        $andThenCounter = 20;

        $andThen = $map->andThen(function () use (&$andThenCounter) {
            $andThenCounter++;

            return new Ok(fn () => $andThenCounter);
        });

        $this->assertSame(0, $counter);
        $this->assertSame(1, $result->unwrap());
        $this->assertSame(1, $counter);

        $this->assertSame(10, $mapCounter);
        $this->assertSame(11, $map->unwrap());
        $this->assertSame(11, $mapCounter);

        $this->assertSame(20, $andThenCounter);
        $this->assertSame(21, $andThen->unwrap());
        $this->assertSame(21, $andThenCounter);
    }

    #[Test]
    public function evaluteCache(): void
    {
        $counter = 0;

        $result = new Ok(function () use (&$counter) {
            $counter++;

            return $counter;
        });

        $mapCounter = 10;

        $map = $result->map(function () use (&$mapCounter) {
            $mapCounter++;

            return $mapCounter;
        });

        $andThenCounter = 20;

        $andThen = $map->andThen(function () use (&$andThenCounter) {
            $andThenCounter++;

            return new Ok(fn () => $andThenCounter);
        });

        $this->assertSame(0, $counter);
        $result->unwrap();
        $result->unwrap();
        $a = $result->unwrap();
        $this->assertSame(1, $a);
        $this->assertSame(1, $counter);

        $this->assertSame(10, $mapCounter);
        $map->unwrap();
        $map->unwrap();
        $b = $map->unwrap();
        $this->assertSame(11, $b);

        $this->assertSame(20, $andThenCounter);
        $andThen->unwrap();
        $andThen->unwrap();
        $c = $andThen->unwrap();
        $this->assertSame(21, $c);
        $this->assertSame(11, $mapCounter);
        $this->assertSame(21, $andThenCounter);
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
