<?php

declare(strict_types=1);

namespace Finite\Event;

use Finite\Exception\TransitionException;
use Finite\State\StateInterface;
use Finite\StateMachine\StateMachine;
use Finite\Transition\PropertiesAwareTransitionInterface;
use Finite\Transition\TransitionInterface;

/**
 * The event object which is thrown on transitions actions.
 */
class TransitionEvent extends StateMachineEvent
{
    protected TransitionInterface $transition;

    protected bool $transitionRejected = false;

    protected StateInterface $initialState;

    protected array $properties;

    /**
     * @throws TransitionException
     */
    public function __construct(
        StateInterface $initialState,
        TransitionInterface $transition,
        StateMachine $stateMachine,
        array $properties = []
    ) {
        $this->transition = $transition;
        $this->initialState = $initialState;
        $this->properties = $properties;

        if ($transition instanceof PropertiesAwareTransitionInterface) {
            $this->properties = $transition->resolveProperties($properties);
        }

        parent::__construct($stateMachine);
    }

    public function getTransition(): TransitionInterface
    {
        return $this->transition;
    }

    public function isRejected(): bool
    {
        return $this->transitionRejected;
    }

    public function reject()
    {
        $this->transitionRejected = true;
    }

    public function getInitialState(): StateInterface
    {
        return $this->initialState;
    }

    public function has(string $property): bool
    {
        return array_key_exists($property, $this->properties);
    }

    /**
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $property, $default = null)
    {
        return $this->has($property) ? $this->properties[$property] : $default;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }
}
