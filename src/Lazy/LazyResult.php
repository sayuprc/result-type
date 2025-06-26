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
     * @return EagerResult<T, E>
     */
    public function toEager(): EagerResult;
}
