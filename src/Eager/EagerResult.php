<?php

declare(strict_types=1);

namespace ResultType\Eager;

use ResultType\Lazy\LazyResult;
use ResultType\Result;

/**
 * An interface for an eagerly evaluated Result type.
 *
 * Its state as either Ok or Err is determined at the time of instantiation.
 * It is suitable for operations with low computational cost or when the result is needed immediately.
 *
 * @template-covariant T Type of the value on success.
 * @template-covariant E Type of the error on failure.
 *
 * @template-extends Result<T, E>
 */
interface EagerResult extends Result
{
    /**
     * Converts this eager result to lazy result.
     *
     * @return LazyResult<T, E>
     */
    public function toLazy(): LazyResult;
}
