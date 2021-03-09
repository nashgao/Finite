<?php

declare(strict_types=1);

namespace Finite\Event\Callback;

use Finite\StateMachine\StateMachineInterface;

/**
 * Concrete implementation of CallbackBuilder factory.
 */
class CallbackBuilderFactory implements CallbackBuilderFactoryInterface
{
    public function createBuilder(StateMachineInterface $stateMachine): CallbackBuilder
    {
        return CallbackBuilder::create($stateMachine);
    }
}
