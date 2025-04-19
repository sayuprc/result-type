<?php

declare(strict_types=1);

namespace ResultType;

use LogicException;

/**
 * @template-covariant E
 *
 * @template-implements Result<never, E>
 */
class Err implements Result
{
    /**
     * @param E $error
     */
    public function __construct(private readonly mixed $error)
    {
    }

    public function isOk(): bool
    {
        return false;
    }

    public function isErr(): bool
    {
        return true;
    }

    public function unwrap(): mixed
    {
        throw new LogicException('Result is not Ok.');
    }

    /**
     * @return E
     */
    public function unwrapErr(): mixed
    {
        return $this->error;
    }
}
