<?php

declare(strict_types=1);

namespace ResultType;

use Closure;
use LogicException;
use Override;

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

    #[Override]
    public function isOk(): bool
    {
        return true;
    }

    #[Override]
    public function isErr(): bool
    {
        return false;
    }

    #[Override]
    public function unwrap(): mixed
    {
        return $this->value;
    }

    #[Override]
    public function unwrapOr(mixed $default): mixed
    {
        return $this->unwrap();
    }

    /**
     * @template TReturn
     *
     * @param Closure(T): TReturn $callback
     *
     * @return Result<TReturn, never>
     */
    #[Override]
    public function map(Closure $callback): Result
    {
        return new Ok($callback($this->unwrap()));
    }

    #[Override]
    public function andThen(Closure $callback): Result
    {
        return $callback($this->unwrap());
    }

    #[Override]
    public function unwrapErr(): mixed
    {
        throw new LogicException('Result is not Err.');
    }

    #[Override]
    public function unwrapErrOr(mixed $default): mixed
    {
        return $default;
    }

    /**
     * @template EReturn
     *
     * @param Closure(never): EReturn $callback
     *
     * @return Ok<T>
     */
    #[Override]
    public function mapErr(Closure $callback): Result
    {
        return $this;
    }

    #[Override]
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
    #[Override]
    public function match(Closure $ok, Closure $err): mixed
    {
        return $ok($this->unwrap());
    }
}
