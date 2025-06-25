<?php

declare(strict_types=1);

namespace ResultType;

use Closure;
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

    public function map(Closure $callback): Result
    {
        return $this;
    }

    /**
     * @return E
     */
    public function unwrapErr(): mixed
    {
        return $this->error;
    }

    public function mapErr(Closure $callback): Result
    {
        if ($this->isOk()) {
            return $this;
        }

        return new Err($callback($this->unwrapErr()));
    }
}
