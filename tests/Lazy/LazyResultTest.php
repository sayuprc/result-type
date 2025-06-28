<?php

declare(strict_types=1);

namespace Tests\Lazy;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use ResultType\Eager\Err;
use ResultType\Eager\Ok;
use ResultType\Lazy\LazyResult;

class LazyResultTest extends TestCase
{
    #[Test]
    public function isOkShouldReturnTrueWhenOk(): void
    {
        $this->assertTrue(new LazyResult(fn () => new Ok(1))->isOk());
    }

    #[Test]
    public function isOkShouldReturnFalseWhenErr(): void
    {
        $this->assertFalse(new LazyResult(fn () => new Err(1))->isOk());
    }

    #[Test]
    public function isErrShouldReturnTrueWhenErr(): void
    {
        $this->assertTrue(new Err(fn () => new Err(1))->isErr());
    }

    #[Test]
    public function isErrShouldReturnFalseWhenOk(): void
    {
        $this->assertTrue(new Err(fn () => new Ok(1))->isErr());
    }

    #[Test]
    public function unwrap(): void
    {
        $this->assertSame(1, new LazyResult(fn () => new Ok(1))->unwrap());
    }

    #[Test]
    public function unwrapOr(): void
    {
        $this->assertSame(1, new LazyResult(fn () => new Ok(1))->unwrapOr('default'));
    }

    #[Test]
    public function unwrapOrShouldReturnDefault(): void
    {
        $this->assertSame(1, new LazyResult(fn () => new Err('error'))->unwrapOr(1));
    }

    #[Test]
    public function map(): void
    {
        $result = new LazyResult(fn () => new Ok(1))->map(fn ($i) => $i * 2);

        $this->assertSame(2, $result->unwrap());
    }

    #[Test]
    public function shouldNotExecuteMapWithErr(): void
    {
        $result = new LazyResult(fn () => new Err(1))->map(fn ($i) => $i * 2);

        $this->assertTrue($result->isErr());
        $this->assertSame(1, $result->unwrapErr());
    }

    #[Test]
    public function andThen(): void
    {
        $result = new LazyResult(fn () => new Ok(1))
            ->andThen(fn (int $i) => new Ok($i * 2));

        $this->assertTrue($result->isOk());
        $this->assertSame(2, $result->unwrap());
    }

    #[Test]
    public function shouldNotExecuteAndThenWhenErr(): void
    {
        $result = new LazyResult(fn () => new Err(1))
            ->andThen(fn (int $i) => new Ok($i * 2));

        $this->assertTrue($result->isErr());
        $this->assertSame(1, $result->unwrapErr());
    }

    #[Test]
    public function unwrapErr(): void
    {
        $this->assertSame(1, new LazyResult(fn () => new Err(1))->unwrapErr());
    }

    #[Test]
    public function unwrapErrOr(): void
    {
        $this->assertSame(1, new LazyResult(fn () => new Err(1))->unwrapErrOr('default'));
    }

    #[Test]
    public function unwrapErrOrShouldReturnDefault(): void
    {
        $this->assertSame(1, new LazyResult(fn () => new Ok('ok'))->unwrapErrOr(1));
    }

    #[Test]
    public function mapErr(): void
    {
        $result = new LazyResult(fn () => new Err(1))->mapErr(fn ($i) => $i * 2);

        $this->assertSame(2, $result->unwrapErr());
    }

    #[Test]
    public function shouldNotExecuteMapErrWithOk(): void
    {
        $result = new LazyResult(fn () => new Ok(1))->mapErr(fn ($i) => $i * 2);

        $this->assertTrue($result->isOk());
        $this->assertSame(1, $result->unwrap());
    }

    #[Test]
    public function orElse(): void
    {
        $result = new LazyResult(fn () => new Err(1))
            ->orElse(fn (int $i) => new Err($i * 2));

        $this->assertTrue($result->isErr());
        $this->assertSame(2, $result->unwrapErr());
    }

    #[Test]
    public function shouldNotExecuteOrElseWhenOk(): void
    {
        $result = new LazyResult(fn () => new Ok(1))
            ->orElse(fn (int $i) => new Err($i * 2));

        $this->assertTrue($result->isOk());
        $this->assertSame(1, $result->unwrap());
    }

    #[Test]
    public function matchWithOk(): void
    {
        $result = new LazyResult(fn () => new Ok(1));

        $this->assertSame(2, $result->match(fn ($i) => $i * 2, fn ($i) => $i * 3));
    }

    #[Test]
    public function matchWithErr(): void
    {
        $result = new LazyResult(fn () => new Err(1));

        $this->assertSame(3, $result->match(fn ($i) => $i * 2, fn ($i) => $i * 3));
    }

    #[Test]
    public function toEagerWithOk(): void
    {
        $result = new LazyResult(fn () => new Ok(1))->toEager();

        $this->assertInstanceOf(Ok::class, $result);
    }

    #[Test]
    public function toEagerWithErr(): void
    {
        $result = new LazyResult(fn () => new Err(1))->toEager();

        $this->assertInstanceOf(Err::class, $result);
    }

    #[Test]
    public function isLazyWithOk(): void
    {
        $counter = 0;

        $result = new LazyResult(function () use (&$counter) {
            $counter++;

            return new Ok($counter);
        });

        $mapCounter = 10;

        $map = $result->map(function () use (&$mapCounter) {
            $mapCounter++;

            return $mapCounter;
        });

        $andThenCounter = 20;

        $andThen = $map->andThen(function () use (&$andThenCounter) {
            $andThenCounter++;

            return new Ok($andThenCounter);
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
    public function evaluateCacheWithOk(): void
    {
        $counter = 0;

        $result = new LazyResult(
            function () use (&$counter) {
                $counter++;

                return new Ok($counter);
            }
        );

        $mapCounter = 10;

        $map = $result->map(function () use (&$mapCounter) {
            $mapCounter++;

            return $mapCounter;
        });

        $andThenCounter = 20;

        $andThen = $map->andThen(function () use (&$andThenCounter) {
            $andThenCounter++;

            return new Ok($andThenCounter);
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

    #[Test]
    public function evaluateCacheWithErr(): void
    {
        $counter = 0;

        $result = new LazyResult(
            function () use (&$counter) {
                $counter++;

                return new Err($counter);
            }
        );

        $mapErrCounter = 10;

        $mapErr = $result->mapErr(function () use (&$mapErrCounter) {
            $mapErrCounter++;

            return $mapErrCounter;
        });

        $orElseCounter = 20;

        $orElse = $mapErr->orElse(function () use (&$orElseCounter) {
            $orElseCounter++;

            return new Err($orElseCounter);
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
    public function isLazyWithErr(): void
    {
        $counter = 0;

        $result = new LazyResult(function () use (&$counter) {
            $counter++;

            return new Err($counter);
        });

        $mapCounter = 10;

        $mapErr = $result->mapErr(function () use (&$mapCounter) {
            $mapCounter++;

            return $mapCounter;
        });

        $orElseCounter = 20;

        $andThen = $mapErr->orElse(function () use (&$orElseCounter) {
            $orElseCounter++;

            return new Err($orElseCounter);
        });

        $this->assertSame(0, $counter);
        $this->assertSame(1, $result->unwrapErr());
        $this->assertSame(1, $counter);

        $this->assertSame(10, $mapCounter);
        $this->assertSame(11, $mapErr->unwrapErr());
        $this->assertSame(11, $mapCounter);

        $this->assertSame(20, $orElseCounter);
        $this->assertSame(21, $andThen->unwrapErr());
        $this->assertSame(21, $orElseCounter);
    }

    #[Test]
    public function chain(): void
    {
        $result = new LazyResult(fn () => new Ok(10))
            ->andThen(fn (int $i) => $i < 50 ? new Ok((int)($i * 1.5)) : new Err('error'))
            ->map(fn (int $i) => $i + 30)
            ->mapErr(fn (string $i) => $i)
            ->match(fn (int $i) => "result is {$i}", fn (string $i) => $i);

        $this->assertSame('result is 45', $result);
    }
}
