<?php

declare(strict_types=1);

namespace Finite;

/**
 * Implementing this interface make an object Stateful and
 * able to be handled by the state machine.
 */
interface StatefulInterface
{
    /**
     * Gets the object state.
     */
    public function getFiniteState(): ?string;

    /**
     * Sets the object state.
     */
    public function setFiniteState(string $state);
}
