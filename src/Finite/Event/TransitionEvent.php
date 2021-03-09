<?php

namespace Finite\Event;

use Finite\Exception\TransitionException;
use Finite\State\StateInterface;
use Finite\StateMachine\StateMachine;
use Finite\Transition\PropertiesAwareTransitionInterface;
use Finite\Transition\TransitionInterface;

/**
 * The event object which is thrown on transitions actions.
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class TransitionEvent extends StateMachineEvent
{
    /**
     * @var TransitionInterface
     */
    protected TransitionInterface $transition;

    /**
     * @var bool
     */
    protected bool $transitionRejected = false;

    /**
     * @var StateInterface
     */
    protected StateInterface $initialState;

    /**
     * @var array
     */
    protected array $properties;

    /**
     * @param StateInterface $initialState
     * @param TransitionInterface $transition
     * @param StateMachine $stateMachine
     * @param array $properties
     * @throws TransitionException
     */
    public function __construct(
        StateInterface $initialState,
        TransitionInterface $transition,
        StateMachine $stateMachine,
        array $properties = array()
    ) {
        $this->transition = $transition;
        $this->initialState = $initialState;
        $this->properties = $properties;

        if ($transition instanceof PropertiesAwareTransitionInterface) {
            $this->properties = $transition->resolveProperties($properties);
        }

        parent::__construct($stateMachine);
    }

    /**
     * @return TransitionInterface
     */
    public function getTransition(): TransitionInterface
    {
        return $this->transition;
    }

    /**
     * @return bool
     */
    public function isRejected(): bool
    {
        return $this->transitionRejected;
    }

    public function reject()
    {
        $this->transitionRejected = true;
    }

    /**
     * @return StateInterface
     */
    public function getInitialState(): StateInterface
    {
        return $this->initialState;
    }

    /**
     * @param string $property
     *
     * @return bool
     */
    public function has(string $property): bool
    {
        return array_key_exists($property, $this->properties);
    }

    /**
     * @param string $property
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $property, $default = null)
    {
        return $this->has($property) ? $this->properties[$property] : $default;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}
