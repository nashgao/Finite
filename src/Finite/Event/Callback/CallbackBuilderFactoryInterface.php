<?php

declare(strict_types=1);

namespace Finite\Event\Callback;

use Finite\StateMachine\StateMachineInterface;

/**
 * Base interface for CallbackBuilder factories.
 */
interface CallbackBuilderFactoryInterface
{
    /**
     * @return mixed
     */
    public function createBuilder(StateMachineInterface $stateMachine);
}
