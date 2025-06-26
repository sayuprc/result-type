<?php

declare(strict_types=1);

namespace ResultType\Lazy;

use ResultType\Eager\EagerResult;
use ResultType\Result;

/**
 * @template-covariant T
 * @template-covariant E
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
