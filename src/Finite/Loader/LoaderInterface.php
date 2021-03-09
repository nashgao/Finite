<?php

declare(strict_types=1);

namespace Finite\Loader;

use Finite\StateMachine\StateMachineInterface;

/**
 * State & Transitions Loader interface.
 */
interface LoaderInterface
{
    /**
     * Loads a state machine.
     */
    public function load(StateMachineInterface $stateMachine);

    /**
     * Returns if this loader supports $object for $graph.
     */
    public function supports(object $object, string $graph = 'default'): bool;
}
