<?php

declare(strict_types=1);

namespace ResultType;

use Closure;

/**
 * Interface for a result type that represents either a success (Ok) or a failure (Err).
 *
 * Provides methods for inspecting, unwrapping, and transforming the result or error value.
 *
 * @template-covariant T Type of the value on success.
 * @template-covariant E Type of the error on failure.
 */
interface Result
{
    /**
     * Returns true if the result is Ok.
     */
    public function isOk(): bool;

    /**
     * Returns true if the result is Err.
     */
    public function isErr(): bool;

    /**
     * Returns the value if the result is Ok.
     * Throws an exception if the result is Err.
     *
     * @return T
     */
    public function unwrap(): mixed;

    /**
     * Returns the contained value if the result is Ok, otherwise returns the default value.
     *
     * @template TDefault
     *
     * @param TDefault $default
     *
     * @return T|TDefault
     */
    public function unwrapOr(mixed $default): mixed;

    /**
     * If the result is Ok, applies the callback to the contained value and returns a new Ok.
     * If the result is Err, the callback is not applied and the original Err is returned.
     *
     * @template TReturn
     *
     * @param Closure(T): TReturn $callback
     *
     * @return Result<TReturn, E>
     */
    public function map(Closure $callback): Result;

    /**
     * If the result is Ok, applies the callback to the contained value and returns the resulting Result.
     * If the result is Err, the callback is not applied and the original Err is returned.
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
     * Returns the error if the result is Err.
     * Throws an exception if the result is Ok.
     *
     * @return E
     */
    public function unwrapErr(): mixed;

    /**
     * Returns the contained error if the result is Err, otherwise returns the default value.
     *
     * @template EDefault
     *
     * @param EDefault $default
     *
     * @return E|EDefault
     */
    public function unwrapErrOr(mixed $default): mixed;

    /**
     * If the result is Err, applies the callback to the contained error and returns a new Err.
     * If the result is Ok, the callback is not applied and the original Ok is returned.
     *
     * @template EReturn
     *
     * @param Closure(E): EReturn $callback
     *
     * @return Result<T, EReturn>
     */
    public function mapErr(Closure $callback): Result;

    /**
     * If the result is Err, applies the callback to the contained error and returns the resulting Result.
     * If the result is Ok, the callback is not applied and the original Ok is returned.
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
     * If the result is Ok, executes the $ok callback and returns its result.
     * If the result is Err, executes the $err callback and returns its result.
     *
     * @template TReturn
     * @template EReturn
     *
     * @param Closure(T): TReturn $ok
     * @param Closure(E): EReturn $err
     *
     * @return TReturn|EReturn
     */
    public function match(Closure $ok, Closure $err): mixed;
}
