<?php

declare(strict_types=1);

namespace ResultType;

use Closure;
use LogicException;

/**
 * @template-covariant T
 *
 * @template-extends Result<T, never>
 */
class Ok extends Result
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
        return $this->unwrap();
    }

    public function map(Closure $callback): Result
    {
        return new Ok($callback($this->unwrap()));
    }

    public function andThen(Closure $callback): Result
    {
        return $callback($this->unwrap());
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
}
