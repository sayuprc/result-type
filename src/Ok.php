<?php

declare(strict_types=1);

namespace ResultType;

use LogicException;

/**
 * @template-covariant T
 *
 * @template-implements Result<T, never>
 */
class Ok implements Result
{
    /**
     * @param T $value
     */
    public function __construct(private readonly mixed $value)
    {
    }

    public function isOk(): bool
    {
        return true;
    }

    public function isErr(): bool
    {
        return false;
    }

    /**
     * @return T
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getErr(): mixed
    {
        throw new LogicException('Result is not Err.');
    }
}
