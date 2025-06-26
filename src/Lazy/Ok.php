<?php

declare(strict_types=1);

namespace ResultType\Lazy;

use Closure;
use LogicException;
use ResultType\Result;

/**
 * @template-covariant T
 *
 * @template-implements LazyResult<T, never>
 */
class Ok implements LazyResult
{
    private bool $isEvaluated = false;

    /**
     * @var T $value
     */
    private mixed $value;

    /**
     * @param Closure(): T $factory
     */
    public function __construct(private readonly Closure $factory)
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
        return $this->evaluate();
    }

    public function unwrapOr(mixed $default): mixed
    {
        return $this->unwrap();
    }

    public function map(Closure $callback): Result
    {
        return new Ok(fn () => $callback($this->unwrap()));
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
        return new Ok(fn () => $callback($this->unwrap())->unwrap());
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

    /**
     * @return T
     */
    private function evaluate(): mixed
    {
        if (! $this->isEvaluated) {
            $this->value = ($this->factory)();

            $this->isEvaluated = true;
        }

        return $this->value;
    }
}
