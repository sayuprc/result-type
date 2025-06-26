<?php

declare(strict_types=1);

namespace ResultType\Eager;

use ResultType\Result;

/**
 * @template-covariant T
 * @template-covariant E
 *
 * @template-extends Result<T, E>
 */
interface EagerResult extends Result
{
}
