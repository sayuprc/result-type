<?php

declare(strict_types=1);

namespace ResultType\Eager;

use Closure;
use LogicException;
use ResultType\Lazy\LazyResult;
use ResultType\Lazy\Ok as LazyOk;
use ResultType\Result;

/**
 * @template-covariant T
 *
 * @template-implements EagerResult<T, never>
 */
class Ok implements EagerResult
{
    /**
     * @param T $value
     */
    public function __construct(private readonly mixed $value)
    {
    }

    public function isOk(): bool
    {
        return true;
    }

    public function isErr(): bool
    {
        return false;
    }

    public function unwrap(): mixed
    {
        return $this->value;
    }

    public function unwrapOr(mixed $default): mixed
    {
        return $this->value;
    }

    public function map(Closure $callback): Result
    {
        return new Ok($callback($this->unwrap()));
    }

    public function unwrapErr(): mixed
    {
        throw new LogicException('Result is not Err.');
    }

    public function unwrapErrOr(mixed $default): mixed
    {
        return $default;
    }

    public function mapErr(Closure $callback): Result
    {
        return $this;
    }

    public function andThen(Closure $callback): Result
    {
        return $callback($this->value);
    }

    public function orElse(Closure $callback): Result
    {
        return $this;
    }

    /**
     * @template TReturn
     * @template EReturn
     *
     * @param Closure(T): TReturn     $ok
     * @param Closure(never): EReturn $err
     *
     * @return TReturn
     */
    public function match(Closure $ok, Closure $err): mixed
    {
        return $ok($this->unwrap());
    }

    public function toLazy(): LazyResult
    {
        return new LazyOk(fn () => $this->unwrap());
    }
}
