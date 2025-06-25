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
    public function isOk(): bool;

    public function isErr(): bool;

    /**
     * @return T
     */
    public function unwrap(): mixed;

    /**
     * @template TReturn
     *
     * @param Closure(T): TReturn $callback
     *
     * @return Result<TReturn, E>
     */
    public function map(Closure $callback): self;

    /**
     * @return E
     */
    public function unwrapErr(): mixed;

    /**
     * @template EReturn
     *
     * @param Closure(E): EReturn $callback
     *
     * @return Result<T, EReturn>
     */
    public function mapErr(Closure $callback): self;
}
