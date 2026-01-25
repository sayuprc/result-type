<?php

declare(strict_types=1);

namespace ResultType;

use Closure;

/**
 * Abstract class for a result type that represents either a success (Ok) or a failure (Err).
 *
 * Provides methods for inspecting, unwrapping, and transforming the result or error value.
 *
 * @template-covariant T Type of the value on success.
 * @template-covariant E Type of the error on failure.
 */
abstract class Result
{
    /**
     * Returns true if the result is Ok.
     */
    abstract public function isOk(): bool;

    /**
     * Returns true if the result is Err.
     */
    abstract public function isErr(): bool;

    /**
     * Returns the value if the result is Ok.
     * Throws an exception if the result is Err.
     *
     * @return T
     */
    abstract public function unwrap(): mixed;

    /**
     * Returns the contained value if the result is Ok, otherwise returns the default value.
     *
     * @template TDefault
     *
     * @param TDefault $default
     *
     * @return T|TDefault
     */
    abstract public function unwrapOr(mixed $default): mixed;

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
    abstract public function map(Closure $callback): Result;

    /**
     * If the result is Ok, applies the callback to the contained value and returns the resulting Result.
     * If the result is Err, the callback is not applied and the original Err is returned.
     *
     * @template TReturn
     * @template EReturn
     *
     * @param Closure(T): Result<TReturn, EReturn> $callback
     *
     * @return Result<TReturn, E|EReturn>
     */
    abstract public function andThen(Closure $callback): Result;

    /**
     * Returns the error if the result is Err.
     * Throws an exception if the result is Ok.
     *
     * @return E
     */
    abstract public function unwrapErr(): mixed;

    /**
     * Returns the contained error if the result is Err, otherwise returns the default value.
     *
     * @template EDefault
     *
     * @param EDefault $default
     *
     * @return E|EDefault
     */
    abstract public function unwrapErrOr(mixed $default): mixed;

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
    abstract public function mapErr(Closure $callback): Result;

    /**
     * If the result is Err, applies the callback to the contained error and returns the resulting Result.
     * If the result is Ok, the callback is not applied and the original Ok is returned.
     *
     * @template TReturn
     * @template EReturn
     *
     * @param Closure(E): Result<TReturn, EReturn> $callback
     *
     * @return Result<T|TReturn, EReturn>
     */
    abstract public function orElse(Closure $callback): Result;

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
    abstract public function match(Closure $ok, Closure $err): mixed;

    /**
     * Combines two Results into one.
     *
     * If any Result is Err, returns the first Err encountered.
     * If all Results are Ok, returns Ok with an array of all values.
     *
     * @template NewT1
     * @template NewT2
     * @template NewE
     *
     * @param Result<NewT1, NewE> $result1
     * @param Result<NewT2, NewE> $result2
     *
     * @return Result<array{NewT1, NewT2}|NewT1|NewT2, NewE>
     */
    public static function combine(Result $result1, Result $result2): Result
    {
        if ($result1->isErr()) {
            return $result1;
        }

        if ($result2->isErr()) {
            return $result2;
        }

        return new Ok([
            $result1->unwrap(),
            $result2->unwrap(),
        ]);
    }

    /**
     * Combines three Results into one.
     *
     * If any Result is Err, returns the first Err encountered.
     * If all Results are Ok, returns Ok with an array of all values.
     *
     * @template NewT1
     * @template NewT2
     * @template NewT3
     * @template NewE
     *
     * @param Result<NewT1, NewE> $result1
     * @param Result<NewT2, NewE> $result2
     * @param Result<NewT3, NewE> $result3
     *
     * @return Result<array{NewT1, NewT2, NewT3}|NewT1|NewT2|NewT3, NewE>
     */
    public static function combine3(Result $result1, Result $result2, Result $result3): Result
    {
        if ($result1->isErr()) {
            return $result1;
        }

        if ($result2->isErr()) {
            return $result2;
        }

        if ($result3->isErr()) {
            return $result3;
        }

        return new Ok([
            $result1->unwrap(),
            $result2->unwrap(),
            $result3->unwrap(),
        ]);
    }

    /**
     * Combines four Results into one.
     *
     * If any Result is Err, returns the first Err encountered.
     * If all Results are Ok, returns Ok with an array of all values.
     *
     * @template NewT1
     * @template NewT2
     * @template NewT3
     * @template NewT4
     * @template NewE
     *
     * @param Result<NewT1, NewE> $result1
     * @param Result<NewT2, NewE> $result2
     * @param Result<NewT3, NewE> $result3
     * @param Result<NewT4, NewE> $result4
     *
     * @return Result<array{NewT1, NewT2, NewT3, NewT4}|NewT1|NewT2|NewT3|NewT4, NewE>
     */
    public static function combine4(Result $result1, Result $result2, Result $result3, Result $result4): Result
    {
        if ($result1->isErr()) {
            return $result1;
        }

        if ($result2->isErr()) {
            return $result2;
        }

        if ($result3->isErr()) {
            return $result3;
        }

        if ($result4->isErr()) {
            return $result4;
        }

        return new Ok([
            $result1->unwrap(),
            $result2->unwrap(),
            $result3->unwrap(),
            $result4->unwrap(),
        ]);
    }

    /**
     * Combines five Results into one.
     *
     * If any Result is Err, returns the first Err encountered.
     * If all Results are Ok, returns Ok with an array of all values.
     *
     * @template NewT1
     * @template NewT2
     * @template NewT3
     * @template NewT4
     * @template NewT5
     * @template NewE
     *
     * @param Result<NewT1, NewE> $result1
     * @param Result<NewT2, NewE> $result2
     * @param Result<NewT3, NewE> $result3
     * @param Result<NewT4, NewE> $result4
     * @param Result<NewT5, NewE> $result5
     *
     * @return Result<array{NewT1, NewT2, NewT3, NewT4, NewT5}|NewT1|NewT2|NewT3|NewT4|NewT5, NewE>
     */
    public static function combine5(Result $result1, Result $result2, Result $result3, Result $result4, Result $result5): Result
    {
        if ($result1->isErr()) {
            return $result1;
        }

        if ($result2->isErr()) {
            return $result2;
        }

        if ($result3->isErr()) {
            return $result3;
        }

        if ($result4->isErr()) {
            return $result4;
        }

        if ($result5->isErr()) {
            return $result5;
        }

        return new Ok([
            $result1->unwrap(),
            $result2->unwrap(),
            $result3->unwrap(),
            $result4->unwrap(),
            $result5->unwrap(),
        ]);
    }

    /**
     * Combines six Results into one.
     *
     * If any Result is Err, returns the first Err encountered.
     * If all Results are Ok, returns Ok with an array of all values.
     *
     * @template NewT1
     * @template NewT2
     * @template NewT3
     * @template NewT4
     * @template NewT5
     * @template NewT6
     * @template NewE
     *
     * @param Result<NewT1, NewE> $result1
     * @param Result<NewT2, NewE> $result2
     * @param Result<NewT3, NewE> $result3
     * @param Result<NewT4, NewE> $result4
     * @param Result<NewT5, NewE> $result5
     * @param Result<NewT6, NewE> $result6
     *
     * @return Result<array{NewT1, NewT2, NewT3, NewT4, NewT5, NewT6}|NewT1|NewT2|NewT3|NewT4|NewT5|NewT6, NewE>
     */
    public static function combine6(
        Result $result1,
        Result $result2,
        Result $result3,
        Result $result4,
        Result $result5,
        Result $result6,
    ): Result {
        if ($result1->isErr()) {
            return $result1;
        }

        if ($result2->isErr()) {
            return $result2;
        }

        if ($result3->isErr()) {
            return $result3;
        }

        if ($result4->isErr()) {
            return $result4;
        }

        if ($result5->isErr()) {
            return $result5;
        }

        if ($result6->isErr()) {
            return $result6;
        }

        return new Ok([
            $result1->unwrap(),
            $result2->unwrap(),
            $result3->unwrap(),
            $result4->unwrap(),
            $result5->unwrap(),
            $result6->unwrap(),
        ]);
    }

    /**
     * Combines seven Results into one.
     *
     * If any Result is Err, returns the first Err encountered.
     * If all Results are Ok, returns Ok with an array of all values.
     *
     * @template NewT1
     * @template NewT2
     * @template NewT3
     * @template NewT4
     * @template NewT5
     * @template NewT6
     * @template NewT7
     * @template NewE
     *
     * @param Result<NewT1, NewE> $result1
     * @param Result<NewT2, NewE> $result2
     * @param Result<NewT3, NewE> $result3
     * @param Result<NewT4, NewE> $result4
     * @param Result<NewT5, NewE> $result5
     * @param Result<NewT6, NewE> $result6
     * @param Result<NewT7, NewE> $result7
     *
     * @return Result<array{NewT1, NewT2, NewT3, NewT4, NewT5, NewT6, NewT7}|NewT1|NewT2|NewT3|NewT4|NewT5|NewT6|NewT7, NewE>
     */
    public static function combine7(
        Result $result1,
        Result $result2,
        Result $result3,
        Result $result4,
        Result $result5,
        Result $result6,
        Result $result7,
    ): Result {
        if ($result1->isErr()) {
            return $result1;
        }

        if ($result2->isErr()) {
            return $result2;
        }

        if ($result3->isErr()) {
            return $result3;
        }

        if ($result4->isErr()) {
            return $result4;
        }

        if ($result5->isErr()) {
            return $result5;
        }

        if ($result6->isErr()) {
            return $result6;
        }

        if ($result7->isErr()) {
            return $result7;
        }

        return new Ok([
            $result1->unwrap(),
            $result2->unwrap(),
            $result3->unwrap(),
            $result4->unwrap(),
            $result5->unwrap(),
            $result6->unwrap(),
            $result7->unwrap(),
        ]);
    }

    /**
     * Combines eight Results into one.
     *
     * If any Result is Err, returns the first Err encountered.
     * If all Results are Ok, returns Ok with an array of all values.
     *
     * @template NewT1
     * @template NewT2
     * @template NewT3
     * @template NewT4
     * @template NewT5
     * @template NewT6
     * @template NewT7
     * @template NewT8
     * @template NewE
     *
     * @param Result<NewT1, NewE> $result1
     * @param Result<NewT2, NewE> $result2
     * @param Result<NewT3, NewE> $result3
     * @param Result<NewT4, NewE> $result4
     * @param Result<NewT5, NewE> $result5
     * @param Result<NewT6, NewE> $result6
     * @param Result<NewT7, NewE> $result7
     * @param Result<NewT8, NewE> $result8
     *
     * @return Result<array{NewT1, NewT2, NewT3, NewT4, NewT5, NewT6, NewT7, NewT8}|NewT1|NewT2|NewT3|NewT4|NewT5|NewT6|NewT7|NewT8, NewE>
     */
    public static function combine8(
        Result $result1,
        Result $result2,
        Result $result3,
        Result $result4,
        Result $result5,
        Result $result6,
        Result $result7,
        Result $result8,
    ): Result {
        if ($result1->isErr()) {
            return $result1;
        }

        if ($result2->isErr()) {
            return $result2;
        }

        if ($result3->isErr()) {
            return $result3;
        }

        if ($result4->isErr()) {
            return $result4;
        }

        if ($result5->isErr()) {
            return $result5;
        }

        if ($result6->isErr()) {
            return $result6;
        }

        if ($result7->isErr()) {
            return $result7;
        }

        if ($result8->isErr()) {
            return $result8;
        }

        return new Ok([
            $result1->unwrap(),
            $result2->unwrap(),
            $result3->unwrap(),
            $result4->unwrap(),
            $result5->unwrap(),
            $result6->unwrap(),
            $result7->unwrap(),
            $result8->unwrap(),
        ]);
    }

    /**
     * Combines nine Results into one.
     *
     * If any Result is Err, returns the first Err encountered.
     * If all Results are Ok, returns Ok with an array of all values.
     *
     * @template NewT1
     * @template NewT2
     * @template NewT3
     * @template NewT4
     * @template NewT5
     * @template NewT6
     * @template NewT7
     * @template NewT8
     * @template NewT9
     * @template NewE
     *
     * @param Result<NewT1, NewE> $result1
     * @param Result<NewT2, NewE> $result2
     * @param Result<NewT3, NewE> $result3
     * @param Result<NewT4, NewE> $result4
     * @param Result<NewT5, NewE> $result5
     * @param Result<NewT6, NewE> $result6
     * @param Result<NewT7, NewE> $result7
     * @param Result<NewT8, NewE> $result8
     * @param Result<NewT9, NewE> $result9
     *
     * @return Result<array{NewT1, NewT2, NewT3, NewT4, NewT5, NewT6, NewT7, NewT8, NewT9}|NewT1|NewT2|NewT3|NewT4|NewT5|NewT6|NewT7|NewT8|NewT9, NewE>
     */
    public static function combine9(
        Result $result1,
        Result $result2,
        Result $result3,
        Result $result4,
        Result $result5,
        Result $result6,
        Result $result7,
        Result $result8,
        Result $result9,
    ): Result {
        if ($result1->isErr()) {
            return $result1;
        }

        if ($result2->isErr()) {
            return $result2;
        }

        if ($result3->isErr()) {
            return $result3;
        }

        if ($result4->isErr()) {
            return $result4;
        }

        if ($result5->isErr()) {
            return $result5;
        }

        if ($result6->isErr()) {
            return $result6;
        }

        if ($result7->isErr()) {
            return $result7;
        }

        if ($result8->isErr()) {
            return $result8;
        }

        if ($result9->isErr()) {
            return $result9;
        }

        return new Ok([
            $result1->unwrap(),
            $result2->unwrap(),
            $result3->unwrap(),
            $result4->unwrap(),
            $result5->unwrap(),
            $result6->unwrap(),
            $result7->unwrap(),
            $result8->unwrap(),
            $result9->unwrap(),
        ]);
    }

    /**
     * Combines ten Results into one.
     *
     * If any Result is Err, returns the first Err encountered.
     * If all Results are Ok, returns Ok with an array of all values.
     *
     * @template NewT1
     * @template NewT2
     * @template NewT3
     * @template NewT4
     * @template NewT5
     * @template NewT6
     * @template NewT7
     * @template NewT8
     * @template NewT9
     * @template NewT10
     * @template NewE
     *
     * @param Result<NewT1, NewE>  $result1
     * @param Result<NewT2, NewE>  $result2
     * @param Result<NewT3, NewE>  $result3
     * @param Result<NewT4, NewE>  $result4
     * @param Result<NewT5, NewE>  $result5
     * @param Result<NewT6, NewE>  $result6
     * @param Result<NewT7, NewE>  $result7
     * @param Result<NewT8, NewE>  $result8
     * @param Result<NewT9, NewE>  $result9
     * @param Result<NewT10, NewE> $result10
     *
     * @return Result<array{NewT1, NewT2, NewT3, NewT4, NewT5, NewT6, NewT7, NewT8, NewT9, NewT10}|NewT1|NewT2|NewT3|NewT4|NewT5|NewT6|NewT7|NewT8|NewT9|NewT10, NewE>
     */
    public static function combine10(
        Result $result1,
        Result $result2,
        Result $result3,
        Result $result4,
        Result $result5,
        Result $result6,
        Result $result7,
        Result $result8,
        Result $result9,
        Result $result10,
    ): Result {
        if ($result1->isErr()) {
            return $result1;
        }

        if ($result2->isErr()) {
            return $result2;
        }

        if ($result3->isErr()) {
            return $result3;
        }

        if ($result4->isErr()) {
            return $result4;
        }

        if ($result5->isErr()) {
            return $result5;
        }

        if ($result6->isErr()) {
            return $result6;
        }

        if ($result7->isErr()) {
            return $result7;
        }

        if ($result8->isErr()) {
            return $result8;
        }

        if ($result9->isErr()) {
            return $result9;
        }

        if ($result10->isErr()) {
            return $result10;
        }

        return new Ok([
            $result1->unwrap(),
            $result2->unwrap(),
            $result3->unwrap(),
            $result4->unwrap(),
            $result5->unwrap(),
            $result6->unwrap(),
            $result7->unwrap(),
            $result8->unwrap(),
            $result9->unwrap(),
            $result10->unwrap(),
        ]);
    }

    /**
     * Collects two Results into one, accumulating all errors.
     *
     * If all Results are Ok, returns Ok with an array of all values.
     * If any Result is Err, returns Err with an array of all errors (null for Ok positions).
     *
     * @template NewT1
     * @template NewE1
     * @template NewT2
     * @template NewE2
     *
     * @param Result<NewT1, NewE1> $result1
     * @param Result<NewT2, NewE2> $result2
     *
     * @return Result<array{NewT1, NewT2}, array{NewE1|null, NewE2|null}>
     */
    public static function collect(Result $result1, Result $result2): Result
    {
        if (self::allOk($result1, $result2)) {
            return new Ok([
                $result1->unwrap(),
                $result2->unwrap(),
            ]);
        }

        return new Err([
            $result1->unwrapErrOr(null),
            $result2->unwrapErrOr(null),
        ]);
    }

    /**
     * Collects three Results into one, accumulating all errors.
     *
     * If all Results are Ok, returns Ok with an array of all values.
     * If any Result is Err, returns Err with an array of all errors (null for Ok positions).
     *
     * @template NewT1
     * @template NewE1
     * @template NewT2
     * @template NewE2
     * @template NewT3
     * @template NewE3
     *
     * @param Result<NewT1, NewE1> $result1
     * @param Result<NewT2, NewE2> $result2
     * @param Result<NewT3, NewE3> $result3
     *
     * @return Result<array{NewT1, NewT2, NewT3}, array{NewE1|null, NewE2|null, NewE3|null}>
     */
    public static function collect3(Result $result1, Result $result2, Result $result3): Result
    {
        if (self::allOk($result1, $result2, $result3)) {
            return new Ok([
                $result1->unwrap(),
                $result2->unwrap(),
                $result3->unwrap(),
            ]);
        }

        return new Err([
            $result1->unwrapErrOr(null),
            $result2->unwrapErrOr(null),
            $result3->unwrapErrOr(null),
        ]);
    }

    /**
     * Collects four Results into one, accumulating all errors.
     *
     * If all Results are Ok, returns Ok with an array of all values.
     * If any Result is Err, returns Err with an array of all errors (null for Ok positions).
     *
     * @template NewT1
     * @template NewE1
     * @template NewT2
     * @template NewE2
     * @template NewT3
     * @template NewE3
     * @template NewT4
     * @template NewE4
     *
     * @param Result<NewT1, NewE1> $result1
     * @param Result<NewT2, NewE2> $result2
     * @param Result<NewT3, NewE3> $result3
     * @param Result<NewT4, NewE4> $result4
     *
     * @return Result<array{NewT1, NewT2, NewT3, NewT4}, array{NewE1|null, NewE2|null, NewE3|null, NewE4|null}>
     */
    public static function collect4(
        Result $result1,
        Result $result2,
        Result $result3,
        Result $result4,
    ): Result {
        if (self::allOk($result1, $result2, $result3, $result4)) {
            return new Ok([
                $result1->unwrap(),
                $result2->unwrap(),
                $result3->unwrap(),
                $result4->unwrap(),
            ]);
        }

        return new Err([
            $result1->unwrapErrOr(null),
            $result2->unwrapErrOr(null),
            $result3->unwrapErrOr(null),
            $result4->unwrapErrOr(null),
        ]);
    }

    /**
     * Collects five Results into one, accumulating all errors.
     *
     * If all Results are Ok, returns Ok with an array of all values.
     * If any Result is Err, returns Err with an array of all errors (null for Ok positions).
     *
     * @template NewT1
     * @template NewE1
     * @template NewT2
     * @template NewE2
     * @template NewT3
     * @template NewE3
     * @template NewT4
     * @template NewE4
     * @template NewT5
     * @template NewE5
     *
     * @param Result<NewT1, NewE1> $result1
     * @param Result<NewT2, NewE2> $result2
     * @param Result<NewT3, NewE3> $result3
     * @param Result<NewT4, NewE4> $result4
     * @param Result<NewT5, NewE5> $result5
     *
     * @return Result<array{NewT1, NewT2, NewT3, NewT4, NewT5}, array{NewE1|null, NewE2|null, NewE3|null, NewE4|null, NewE5|null}>
     */
    public static function collect5(
        Result $result1,
        Result $result2,
        Result $result3,
        Result $result4,
        Result $result5,
    ): Result {
        if (self::allOk($result1, $result2, $result3, $result4, $result5)) {
            return new Ok([
                $result1->unwrap(),
                $result2->unwrap(),
                $result3->unwrap(),
                $result4->unwrap(),
                $result5->unwrap(),
            ]);
        }

        return new Err([
            $result1->unwrapErrOr(null),
            $result2->unwrapErrOr(null),
            $result3->unwrapErrOr(null),
            $result4->unwrapErrOr(null),
            $result5->unwrapErrOr(null),
        ]);
    }

    /**
     * Collects six Results into one, accumulating all errors.
     *
     * If all Results are Ok, returns Ok with an array of all values.
     * If any Result is Err, returns Err with an array of all errors (null for Ok positions).
     *
     * @template NewT1
     * @template NewE1
     * @template NewT2
     * @template NewE2
     * @template NewT3
     * @template NewE3
     * @template NewT4
     * @template NewE4
     * @template NewT5
     * @template NewE5
     * @template NewT6
     * @template NewE6
     *
     * @param Result<NewT1, NewE1> $result1
     * @param Result<NewT2, NewE2> $result2
     * @param Result<NewT3, NewE3> $result3
     * @param Result<NewT4, NewE4> $result4
     * @param Result<NewT5, NewE5> $result5
     * @param Result<NewT6, NewE6> $result6
     *
     * @return Result<array{NewT1, NewT2, NewT3, NewT4, NewT5, NewT6}, array{NewE1|null, NewE2|null, NewE3|null, NewE4|null, NewE5|null, NewE6|null}>
     */
    public static function collect6(
        Result $result1,
        Result $result2,
        Result $result3,
        Result $result4,
        Result $result5,
        Result $result6,
    ): Result {
        if (self::allOk($result1, $result2, $result3, $result4, $result5, $result6)) {
            return new Ok([
                $result1->unwrap(),
                $result2->unwrap(),
                $result3->unwrap(),
                $result4->unwrap(),
                $result5->unwrap(),
                $result6->unwrap(),
            ]);
        }

        return new Err([
            $result1->unwrapErrOr(null),
            $result2->unwrapErrOr(null),
            $result3->unwrapErrOr(null),
            $result4->unwrapErrOr(null),
            $result5->unwrapErrOr(null),
            $result6->unwrapErrOr(null),
        ]);
    }

    /**
     * Collects seven Results into one, accumulating all errors.
     *
     * If all Results are Ok, returns Ok with an array of all values.
     * If any Result is Err, returns Err with an array of all errors (null for Ok positions).
     *
     * @template NewT1
     * @template NewE1
     * @template NewT2
     * @template NewE2
     * @template NewT3
     * @template NewE3
     * @template NewT4
     * @template NewE4
     * @template NewT5
     * @template NewE5
     * @template NewT6
     * @template NewE6
     * @template NewT7
     * @template NewE7
     *
     * @param Result<NewT1, NewE1> $result1
     * @param Result<NewT2, NewE2> $result2
     * @param Result<NewT3, NewE3> $result3
     * @param Result<NewT4, NewE4> $result4
     * @param Result<NewT5, NewE5> $result5
     * @param Result<NewT6, NewE6> $result6
     * @param Result<NewT7, NewE7> $result7
     *
     * @return Result<array{NewT1, NewT2, NewT3, NewT4, NewT5, NewT6, NewT7}, array{NewE1|null, NewE2|null, NewE3|null, NewE4|null, NewE5|null, NewE6|null, NewE7|null}>
     */
    public static function collect7(
        Result $result1,
        Result $result2,
        Result $result3,
        Result $result4,
        Result $result5,
        Result $result6,
        Result $result7,
    ): Result {
        if (self::allOk($result1, $result2, $result3, $result4, $result5, $result6, $result7)) {
            return new Ok([
                $result1->unwrap(),
                $result2->unwrap(),
                $result3->unwrap(),
                $result4->unwrap(),
                $result5->unwrap(),
                $result6->unwrap(),
                $result7->unwrap(),
            ]);
        }

        return new Err([
            $result1->unwrapErrOr(null),
            $result2->unwrapErrOr(null),
            $result3->unwrapErrOr(null),
            $result4->unwrapErrOr(null),
            $result5->unwrapErrOr(null),
            $result6->unwrapErrOr(null),
            $result7->unwrapErrOr(null),
        ]);
    }

    /**
     * Collects eight Results into one, accumulating all errors.
     *
     * If all Results are Ok, returns Ok with an array of all values.
     * If any Result is Err, returns Err with an array of all errors (null for Ok positions).
     *
     * @template NewT1
     * @template NewE1
     * @template NewT2
     * @template NewE2
     * @template NewT3
     * @template NewE3
     * @template NewT4
     * @template NewE4
     * @template NewT5
     * @template NewE5
     * @template NewT6
     * @template NewE6
     * @template NewT7
     * @template NewE7
     * @template NewT8
     * @template NewE8
     *
     * @param Result<NewT1, NewE1> $result1
     * @param Result<NewT2, NewE2> $result2
     * @param Result<NewT3, NewE3> $result3
     * @param Result<NewT4, NewE4> $result4
     * @param Result<NewT5, NewE5> $result5
     * @param Result<NewT6, NewE6> $result6
     * @param Result<NewT7, NewE7> $result7
     * @param Result<NewT8, NewE8> $result8
     *
     * @return Result<array{NewT1, NewT2, NewT3, NewT4, NewT5, NewT6, NewT7, NewT8}, array{NewE1|null, NewE2|null, NewE3|null, NewE4|null, NewE5|null, NewE6|null, NewE7|null, NewE8|null}>
     */
    public static function collect8(
        Result $result1,
        Result $result2,
        Result $result3,
        Result $result4,
        Result $result5,
        Result $result6,
        Result $result7,
        Result $result8,
    ): Result {
        if (self::allOk($result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8)) {
            return new Ok([
                $result1->unwrap(),
                $result2->unwrap(),
                $result3->unwrap(),
                $result4->unwrap(),
                $result5->unwrap(),
                $result6->unwrap(),
                $result7->unwrap(),
                $result8->unwrap(),
            ]);
        }

        return new Err([
            $result1->unwrapErrOr(null),
            $result2->unwrapErrOr(null),
            $result3->unwrapErrOr(null),
            $result4->unwrapErrOr(null),
            $result5->unwrapErrOr(null),
            $result6->unwrapErrOr(null),
            $result7->unwrapErrOr(null),
            $result8->unwrapErrOr(null),
        ]);
    }

    /**
     * Collects nine Results into one, accumulating all errors.
     *
     * If all Results are Ok, returns Ok with an array of all values.
     * If any Result is Err, returns Err with an array of all errors (null for Ok positions).
     *
     * @template NewT1
     * @template NewE1
     * @template NewT2
     * @template NewE2
     * @template NewT3
     * @template NewE3
     * @template NewT4
     * @template NewE4
     * @template NewT5
     * @template NewE5
     * @template NewT6
     * @template NewE6
     * @template NewT7
     * @template NewE7
     * @template NewT8
     * @template NewE8
     * @template NewT9
     * @template NewE9
     *
     * @param Result<NewT1, NewE1> $result1
     * @param Result<NewT2, NewE2> $result2
     * @param Result<NewT3, NewE3> $result3
     * @param Result<NewT4, NewE4> $result4
     * @param Result<NewT5, NewE5> $result5
     * @param Result<NewT6, NewE6> $result6
     * @param Result<NewT7, NewE7> $result7
     * @param Result<NewT8, NewE8> $result8
     * @param Result<NewT9, NewE9> $result9
     *
     * @return Result<array{NewT1, NewT2, NewT3, NewT4, NewT5, NewT6, NewT7, NewT8, NewT9}, array{NewE1|null, NewE2|null, NewE3|null, NewE4|null, NewE5|null, NewE6|null, NewE7|null, NewE8|null, NewE9|null}>
     */
    public static function collect9(
        Result $result1,
        Result $result2,
        Result $result3,
        Result $result4,
        Result $result5,
        Result $result6,
        Result $result7,
        Result $result8,
        Result $result9,
    ): Result {
        if (self::allOk($result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9)) {
            return new Ok([
                $result1->unwrap(),
                $result2->unwrap(),
                $result3->unwrap(),
                $result4->unwrap(),
                $result5->unwrap(),
                $result6->unwrap(),
                $result7->unwrap(),
                $result8->unwrap(),
                $result9->unwrap(),
            ]);
        }

        return new Err([
            $result1->unwrapErrOr(null),
            $result2->unwrapErrOr(null),
            $result3->unwrapErrOr(null),
            $result4->unwrapErrOr(null),
            $result5->unwrapErrOr(null),
            $result6->unwrapErrOr(null),
            $result7->unwrapErrOr(null),
            $result8->unwrapErrOr(null),
            $result9->unwrapErrOr(null),
        ]);
    }

    /**
     * Collects ten Results into one, accumulating all errors.
     *
     * If all Results are Ok, returns Ok with an array of all values.
     * If any Result is Err, returns Err with an array of all errors (null for Ok positions).
     *
     * @template NewT1
     * @template NewE1
     * @template NewT2
     * @template NewE2
     * @template NewT3
     * @template NewE3
     * @template NewT4
     * @template NewE4
     * @template NewT5
     * @template NewE5
     * @template NewT6
     * @template NewE6
     * @template NewT7
     * @template NewE7
     * @template NewT8
     * @template NewE8
     * @template NewT9
     * @template NewE9
     * @template NewT10
     * @template NewE10
     *
     * @param Result<NewT1, NewE1>   $result1
     * @param Result<NewT2, NewE2>   $result2
     * @param Result<NewT3, NewE3>   $result3
     * @param Result<NewT4, NewE4>   $result4
     * @param Result<NewT5, NewE5>   $result5
     * @param Result<NewT6, NewE6>   $result6
     * @param Result<NewT7, NewE7>   $result7
     * @param Result<NewT8, NewE8>   $result8
     * @param Result<NewT9, NewE9>   $result9
     * @param Result<NewT10, NewE10> $result10
     *
     * @return Result<array{NewT1, NewT2, NewT3, NewT4, NewT5, NewT6, NewT7, NewT8, NewT9, NewT10}, array{NewE1|null, NewE2|null, NewE3|null, NewE4|null, NewE5|null, NewE6|null, NewE7|null, NewE8|null, NewE9|null, NewE10|null}>
     */
    public static function collect10(
        Result $result1,
        Result $result2,
        Result $result3,
        Result $result4,
        Result $result5,
        Result $result6,
        Result $result7,
        Result $result8,
        Result $result9,
        Result $result10,
    ): Result {
        if (self::allOk($result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10)) {
            return new Ok([
                $result1->unwrap(),
                $result2->unwrap(),
                $result3->unwrap(),
                $result4->unwrap(),
                $result5->unwrap(),
                $result6->unwrap(),
                $result7->unwrap(),
                $result8->unwrap(),
                $result9->unwrap(),
                $result10->unwrap(),
            ]);
        }

        return new Err([
            $result1->unwrapErrOr(null),
            $result2->unwrapErrOr(null),
            $result3->unwrapErrOr(null),
            $result4->unwrapErrOr(null),
            $result5->unwrapErrOr(null),
            $result6->unwrapErrOr(null),
            $result7->unwrapErrOr(null),
            $result8->unwrapErrOr(null),
            $result9->unwrapErrOr(null),
            $result10->unwrapErrOr(null),
        ]);
    }

    /**
     * Returns true if all Results are Ok.
     *
     * @param Result<mixed, mixed> ...$results
     */
    private static function allOk(Result ...$results): bool
    {
        foreach ($results as $result) {
            if ($result->isErr()) {
                return false;
            }
        }

        return true;
    }
}
