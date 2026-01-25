<?php

declare(strict_types=1);

namespace ResultType;

use Closure;
use LogicException;
use Override;

/**
 * @template-covariant E
 *
 * @template-extends Result<never, E>
 */
class Err extends Result
{
    /**
     * @param E $error
     */
    public function __construct(private readonly mixed $error)
    {
    }

    #[Override]
    public function isOk(): bool
    {
        return false;
    }

    #[Override]
    public function isErr(): bool
    {
        return true;
    }

    #[Override]
    public function unwrap(): mixed
    {
        throw new LogicException('Result is not Ok.');
    }

    #[Override]
    public function unwrapOr(mixed $default): mixed
    {
        return $default;
    }

    /**
     * @template TReturn
     *
     * @param Closure(never): TReturn $callback
     *
     * @return Err<E>
     */
    #[Override]
    public function map(Closure $callback): Result
    {
        return $this;
    }

    #[Override]
    public function andThen(Closure $callback): Result
    {
        return $this;
    }

    #[Override]
    public function unwrapErr(): mixed
    {
        return $this->error;
    }

    #[Override]
    public function unwrapErrOr(mixed $default): mixed
    {
        return $this->unwrapErr();
    }

    /**
     * @template EReturn
     *
     * @param Closure(E): EReturn $callback
     *
     * @return Result<never, EReturn>
     */
    #[Override]
    public function mapErr(Closure $callback): Result
    {
        return new Err($callback($this->unwrapErr()));
    }

    #[Override]
    public function orElse(Closure $callback): Result
    {
        return $callback($this->unwrapErr());
    }

    /**
     * @template TReturn
     * @template EReturn
     *
     * @param Closure(never): TReturn $ok
     * @param Closure(E): EReturn     $err
     *
     * @return EReturn
     */
    #[Override]
    public function match(Closure $ok, Closure $err): mixed
    {
        return $err($this->unwrapErr());
    }
}
