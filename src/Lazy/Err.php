<?php

declare(strict_types=1);

namespace ResultType\Lazy;

use Closure;
use LogicException;
use ResultType\Result;

/**
 * @template-covariant E
 *
 * @template-implements LazyResult<never, E>
 */
class Err implements LazyResult
{
    private bool $isEvaluated = false;

    /**
     * @var E $error
     */
    private mixed $error;

    /**
     * @param Closure(): E $factory
     */
    public function __construct(private readonly Closure $factory)
    {
    }

    public function isOk(): bool
    {
        return false;
    }

    public function isErr(): bool
    {
        return true;
    }

    public function unwrap(): mixed
    {
        throw new LogicException('Result is not Ok.');
    }

    public function unwrapOr(mixed $default): mixed
    {
        return $default;
    }

    public function map(Closure $callback): Result
    {
        return $this;
    }

    public function unwrapErr(): mixed
    {
        return $this->evaluate();
    }

    public function unwrapErrOr(mixed $default): mixed
    {
        return $this->unwrapErr();
    }

    public function mapErr(Closure $callback): Result
    {
        return new Err(fn () => $callback($this->unwrapErr()));
    }

    public function andThen(Closure $callback): Result
    {
        return $this;
    }

    public function orElse(Closure $callback): Result
    {
        return new Err(fn () => $callback($this->unwrapErr())->unwrapErr());
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
    public function match(Closure $ok, Closure $err): mixed
    {
        return $err($this->unwrapErr());
    }

    /**
     * @return E
     */
    private function evaluate(): mixed
    {
        if (! $this->isEvaluated) {
            $this->error = ($this->factory)();

            $this->isEvaluated = true;
        }

        return $this->error;
    }
}
