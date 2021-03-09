<?php

declare(strict_types=1);

namespace Finite\Event;

use Finite\StateMachine\StateMachine;
use Symfony\Contracts\EventDispatcher\Event;

if (! class_exists('Symfony\Contracts\EventDispatcher\Event')) {
    class_alias('Symfony\Component\EventDispatcher\Event', 'Symfony\Contracts\EventDispatcher\Event');
}

/**
 * The event object which is thrown on state machine actions.
 */
class StateMachineEvent extends Event
{
    protected StateMachine $stateMachine;

    public function __construct(StateMachine $stateMachine)
    {
        $this->stateMachine = $stateMachine;
    }

    public function getStateMachine(): StateMachine
    {
        return $this->stateMachine;
    }
}
