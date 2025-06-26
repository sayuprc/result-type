<?php

declare(strict_types=1);

namespace Tests\Lazy;

use Closure;
use LogicException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ResultType\Eager\Err as EagerErr;
use ResultType\Lazy\Err;
use ResultType\Result;
use stdClass;

class ErrTest extends TestCase
{
    #[Test]
    public function isImplementsResult(): void
    {
        $this->assertInstanceOf(Result::class, new Err(fn () => null));
    }

    #[Test]
    public function isOkShouldReturnFalse(): void
    {
        $this->assertFalse(new Err(fn () => null)->isOk());
    }

    #[Test]
    public function isErrShouldReturnTrue(): void
    {
        $this->assertTrue(new Err(fn () => null)->isErr());
    }

    #[Test]
    public function throwWhenCalledUnwrap(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('Result is not Ok.');

        new Err(fn () => null)->unwrap();
    }

    #[Test]
    #[DataProvider('unwrapErrProvider')]
    public function unwrapErr(mixed $expected, Closure $value): void
    {
        $this->assertSame($expected, new Err($value)->unwrapErr());
    }

    public static function unwrapErrProvider(): array
    {
        return [
            [null, fn () => null],
            [1, fn () => 1],
            ['string value', fn () => 'string value'],
            [$stdClass = new stdClass(), fn () => $stdClass],
            [$err = new ErrValue(1, 'name'), fn () => $err],
        ];
    }

    #[Test]
    public function mapShouldReturnErr(): void
    {
        $result = new Err(fn () => 1)->map(fn (int $i) => $i * 2);

        $this->assertInstanceOf(Err::class, $result);
        $this->assertSame(1, $result->unwrapErr());
    }

    #[Test]
    #[DataProvider('mapErrProvider')]
    public function mapErr(mixed $expected, Closure $initial, Closure $callback): void
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
    #[DataProvider('continuousMapErrProvider')]
    public function continuousMapErr(mixed $expected, Closure $initial, Closure $callback1, Closure $callback2): void
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
    public function mapErrWithMap(): void
    {
        $result = new Err(fn () => 1)
            ->mapErr(fn (int $i) => $i * 2)
            ->map(fn (int $i) => $i * 3)
            ->mapErr(fn (int $i) => $i * 4);

        $this->assertInstanceOf(Err::class, $result);
        $this->assertSame(8, $result->unwrapErr());
    }

    #[Test]
    public function mapWithMapErr(): void
    {
        $result = new Err(fn () => 1)
            ->map(fn (int $i) => $i * 2)
            ->mapErr(fn (int $i) => $i * 3)
            ->map(fn (int $i) => $i * 4);

        $this->assertInstanceOf(Err::class, $result);
        $this->assertSame(3, $result->unwrapErr());
    }

    #[Test]
    #[DataProvider('unwrapOrProvider')]
    public function unwrapOr(mixed $expected, Closure $initial): void
    {
        $this->assertSame($expected, new Err($initial)->unwrapOr($expected));
    }

    public static function unwrapOrProvider(): array
    {
        return [
            [0, fn () => 1],
            ['default', fn () => 'value'],
        ];
    }

    #[Test]
    #[DataProvider('unwrapErrOrProvider')]
    public function unwrapErrOr(mixed $expected, Closure $initial, mixed $default): void
    {
        $this->assertSame($expected, new Err($initial)->unwrapErrOr($default));
    }

    public static function unwrapErrOrProvider(): array
    {
        return [
            [1, fn () => 1, 0],
            ['value', fn () => 'value', 'default'],
        ];
    }

    #[Test]
    public function andThen(): void
    {
        $result = new Err(fn () => 1)->andThen(fn (int $i) => new Err(fn () => $i * 2));

        $this->assertInstanceOf(Err::class, $result);
        $this->assertSame(1, $result->unwrapErr());
    }

    #[Test]
    public function andThenAfterOrElse(): void
    {
        $result = new Err(fn () => 1)
            ->orElse(fn (int $i) => new Err(fn () => $i * 2))
            ->andThen(fn (int $i) => new Err(fn () => $i * 3));

        $this->assertInstanceOf(Err::class, $result);
        $this->assertSame(2, $result->unwrapErr());
    }

    #[Test]
    public function orElse(): void
    {
        $result = new Err(fn () => 1)->orElse(fn (int $i) => new Err(fn () => $i * 2));

        $this->assertInstanceOf(Err::class, $result);
        $this->assertSame(2, $result->unwrapErr());
    }

    #[Test]
    public function orElseAfterAndThen(): void
    {
        $result = new Err(fn () => 1)
            ->andThen(fn (int $i) => new Err(fn () => $i * 2))
            ->orElse(fn (int $i) => new Err(fn () => $i * 3));

        $this->assertInstanceOf(Err::class, $result);
        $this->assertSame(3, $result->unwrapErr());
    }

    #[Test]
    public function match(): void
    {
        $this->assertSame(2, new Err(fn () => 1)->match(fn () => 1, fn () => 2));
    }

    #[Test]
    public function isLazy(): void
    {
        $counter = 0;

        $result = new Err(function () use (&$counter) {
            $counter++;

            return $counter;
        });

        $mapErrCounter = 10;

        $mapErr = $result->mapErr(function () use (&$mapErrCounter) {
            $mapErrCounter++;

            return $mapErrCounter;
        });

        $orElseCounter = 20;

        $orElse = $mapErr->orElse(function () use (&$orElseCounter) {
            $orElseCounter++;

            return new Err(fn () => $orElseCounter);
        });

        $this->assertSame(0, $counter);
        $this->assertSame(1, $result->unwrapErr());
        $this->assertSame(1, $counter);

        $this->assertSame(10, $mapErrCounter);
        $this->assertSame(11, $mapErr->unwrapErr());
        $this->assertSame(11, $mapErrCounter);

        $this->assertSame(20, $orElseCounter);
        $this->assertSame(21, $orElse->unwrapErr());
        $this->assertSame(21, $orElseCounter);
    }

    #[Test]
    public function evaluateCache(): void
    {
        $counter = 0;

        $result = new Err(function () use (&$counter) {
            $counter++;

            return $counter;
        });

        $mapErrCounter = 10;

        $mapErr = $result->mapErr(function () use (&$mapErrCounter) {
            $mapErrCounter++;

            return $mapErrCounter;
        });

        $orElseCounter = 20;

        $orElse = $mapErr->orElse(function () use (&$orElseCounter) {
            $orElseCounter++;

            return new Err(fn () => $orElseCounter);
        });

        $this->assertSame(0, $counter);
        $result->unwrapErr();
        $result->unwrapErr();
        $a = $result->unwrapErr();
        $this->assertSame(1, $a);
        $this->assertSame(1, $counter);

        $this->assertSame(10, $mapErrCounter);
        $mapErr->unwrapErr();
        $mapErr->unwrapErr();
        $b = $mapErr->unwrapErr();
        $this->assertSame(11, $b);

        $this->assertSame(20, $orElseCounter);
        $orElse->unwrapErr();
        $orElse->unwrapErr();
        $c = $orElse->unwrapErr();
        $this->assertSame(21, $c);
        $this->assertSame(11, $mapErrCounter);
        $this->assertSame(21, $orElseCounter);
    }

    #[Test]
    public function toEager(): void
    {
        $result = new Err(fn () => 1)->toEager();

        $this->assertInstanceOf(EagerErr::class, $result);
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
