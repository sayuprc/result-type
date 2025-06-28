<?php

declare(strict_types=1);

namespace ResultType\Lazy;

use ResultType\Eager\EagerResult;
use ResultType\Result;

/**
 * An interface for a lazily evaluated Result type.
 *
 * The computation of the result is deferred until the value is actually needed.
 * The constructor accepts a closure that represents the computation,
 * which is executed only once when a method such as `unwrap()` or `map()` is called.
 *
 * It is suitable for encapsulating computationally expensive operations,
 * operations with potential side effects, or operations that do not
 * necessarily need to be executed.
 *
 * @template-covariant T Type of the value on success.
 * @template-covariant E Type of the error on failure.
 *
 * @template-extends Result<T, E>
 */
interface LazyResult extends Result
{
    /**
     * Converts this lazy result to eager result.
     *
     * @return EagerResult<T, E>
     */
    public function toEager(): EagerResult;
}
