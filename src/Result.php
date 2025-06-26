<?php

declare(strict_types=1);

namespace ResultType;

use Closure;

/**
 * @template-covariant T
 * @template-covariant E
 */
interface Result
{
    /**
     * Returns true if the result is Ok (success).
     */
    public function isOk(): bool;

    /**
     * Returns false if the result is Err (failure).
     */
    public function isErr(): bool;

    /**
     * Returns the value if the result is Ok.
     * Trhws an exception if the result is Err.
     *
     * @return T
     */
    public function unwrap(): mixed;

    /**
     * Returns the value if Ok, or the given default value if Err.
     *
     * @template TDefault
     *
     * @param TDefault $default
     *
     * @return T|TDefault
     */
    public function unwrapOr(mixed $default): mixed;

    /**
     * Applies the callback to the value if Ok, and returns a new Result.
     * If Err, returns the original Result.
     *
     * @template TReturn
     *
     * @param Closure(T): TReturn $callback
     *
     * @return Result<TReturn, E>
     */
    public function map(Closure $callback): Result;

    /**
     * Returns the error if the result is Err.
     * Throws an exception if the result is Ok.
     *
     * @return E
     */
    public function unwrapErr(): mixed;

    /**
     * Returns the error if Err, or the given default value if Ok.
     *
     * @template EDefault
     *
     * @param EDefault $default
     *
     * @return E|EDefault
     */
    public function unwrapErrOr(mixed $default): mixed;

    /**
     * Applies the callback to the error if Err, and returns a new Result.
     * If Ok, returns the original Result.
     *
     * @template EReturn
     *
     * @param Closure(E): EReturn $callback
     *
     * @return Result<T, EReturn>
     */
    public function mapErr(Closure $callback): Result;

    /**
     * If Ok, applies the callback and returns the resulting Result.
     * If Err, returns the original Result.
     *
     * @template TReturn
     * @template EReturn
     *
     * @param Closure(T): Result<TReturn, EReturn> $callback
     *
     * @return Result<TReturn, EReturn>
     */
    public function andThen(Closure $callback): Result;

    /**
     * If Err, applies the callback and returns the resulting Result.
     * If Ok, returns the original Result.
     *
     * @template TReturn
     * @template EReturn
     *
     * @param Closure(E): Result<TReturn, EReturn> $callback
     *
     * @return Result<TReturn, EReturn>
     */
    public function orElse(Closure $callback): Result;

    /**
     * Pattern-matches on the result, executing the appropriate callback.
     *
     * If the result is Ok, calls the $ok callback with the value and returns its result.
     * If the result is Err, calls the $err callback with the error and returns its result.
     *
     * @template R
     *
     * @param Closure(T): R $ok
     * @param Closure(E): R $err
     *
     * @return R
     */
    public function match(Closure $ok, Closure $err): mixed;
}
