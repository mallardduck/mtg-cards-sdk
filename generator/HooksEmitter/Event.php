<?php

namespace MallardDuck\MtgCardsSdk\Generator\HooksEmitter;

use ArrayAccess;
use Iterator;
use ReturnTypeWillChange;

class Event implements Iterator, ArrayAccess
{
    public array $callbacks = [];

    private array $priorityKeys = [];
    private array $currentPriority = [];
    private int $nestingLevel = 0;
    private bool $isRunning = false;

    public function current(): mixed
    {
        return current( $this->callbacks );
    }

    #[ReturnTypeWillChange]
    public function next()
    {
        return next( $this->callbacks );
    }

    public function key(): string|int|null
    {
        return key( $this->callbacks );
    }

    public function valid(): bool
    {
        return key( $this->callbacks ) !== null;
    }

    public function rewind(): void
    {
        reset( $this->callbacks );
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset( $this->callbacks[ $offset ] );
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->callbacks[$offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ( is_null( $offset ) ) {
            $this->callbacks[] = $value;
        } else {
            $this->callbacks[ $offset ] = $value;
        }
    }

    public function offsetUnset(mixed $offset): void
    {
        unset( $this->callbacks[ $offset ] );
    }
}