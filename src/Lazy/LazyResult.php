<?php

declare(strict_types=1);

namespace ResultType\Lazy;

use Closure;
use ResultType\Eager\EagerResult;
use ResultType\Eager\Err;
use ResultType\Eager\Ok;
use ResultType\Result;

/**
 * An implements for a lazily evaluated Result type.
 *
 * The computation of the result is deferred until the value is actually needed.
 * The constructor accepts a closure that represents the computation,
 * which is executed only once when a method such as unwrap() or map() is called.
 *
 * It is suitable for encapsulating computationally expensive operations,
 * operations with potential side effects, or operations that do not
 * necessarily need to be executed.
 *
 * @template-covariant T Type of the value on success.
 * @template-covariant E Type of the error on failure.
 *
 * @template-implements Result<T, E>
 */
class LazyResult implements Result
{
    private bool $isEvaluated = false;

    /**
     * @var Result<T, E> $result
     */
    private Result $result;

    /**
     * @param Closure(): Result<T, E> $factory
     */
    public function __construct(private readonly Closure $factory)
    {
    }

    public function isOk(): bool
    {
        return $this->evaluate()->isOk();
    }

    public function isErr(): bool
    {
        return $this->evaluate()->isErr();
    }

    public function unwrap(): mixed
    {
        return $this->evaluate()->unwrap();
    }

    public function unwrapOr(mixed $default): mixed
    {
        return $this->evaluate()->unwrapOr($default);
    }

    public function map(Closure $callback): Result
    {
        return new self(function () use ($callback) {
            $result = $this->evaluate();

            if ($result->isErr()) {
                return $result;
            }

            return new Ok($callback($result->unwrap()));
        });
    }

    public function andThen(Closure $callback): Result
    {
        return new self(function () use ($callback) {
            $result = $this->evaluate();

            if ($result->isErr()) {
                return $result;
            }

            return $callback($result->unwrap());
        });
    }

    public function unwrapErr(): mixed
    {
        return $this->evaluate()->unwrapErr();
    }

    public function unwrapErrOr(mixed $default): mixed
    {
        return $this->evaluate()->unwrapErrOr($default);
    }

    public function mapErr(Closure $callback): Result
    {
        return new self(function () use ($callback) {
            $result = $this->evaluate();

            if ($result->isOk()) {
                return $result;
            }

            return new Err($callback($result->unwrapErr()));
        });
    }

    public function orElse(Closure $callback): Result
    {
        return new self(function () use ($callback) {
            $result = $this->evaluate();

            if ($result->isOk()) {
                return $result;
            }

            return $callback($result->unwrapErr());
        });
    }

    public function match(Closure $ok, Closure $err): mixed
    {
        return $this->isOk()
            ? $ok($this->unwrap())
            : $err($this->unwrapErr());
    }

    /**
     * Converts this lazy result to eager result.
     *
     * @return EagerResult<T, E>
     */
    public function toEager(): EagerResult
    {
        return  $this->isOk()
            ? new Ok($this->unwrap())
            : new Err($this->unwrapErr());
    }

    /**
     * @return Result<T, E>
     */
    private function evaluate(): Result
    {
        if (! $this->isEvaluated) {
            $this->result = ($this->factory)();

            $this->isEvaluated = true;
        }

        return $this->result;
    }
}
