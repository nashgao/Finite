<?php

namespace Finite\StateMachine;

use Finite\Event\FiniteEvents;
use Finite\Event\StateMachineDispatcher;
use Finite\Event\StateMachineEvent;
use Finite\Event\TransitionEvent;
use Finite\Exception;
use Finite\State\Accessor\PropertyPathStateAccessor;
use Finite\State\Accessor\StateAccessorInterface;
use Finite\State\State;
use Finite\State\StateInterface;
use Finite\Transition\Transition;
use Finite\Transition\TransitionInterface;

/**
 * The Finite State Machine.
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class StateMachine implements StateMachineInterface
{
    /**
     * The stateful object.
     *
     * @var object
     */
    protected object $object;

    /**
     * The available states.
     *
     * @var array
     */
    protected array $states = array();

    /**
     * The available transitions.
     *
     * @var array
     */
    protected array $transitions = array();

    /**
     * The current state.
     *
     * @var StateInterface
     */
    protected StateInterface $currentState;

    /**
     * @var StateMachineDispatcher
     */
    protected StateMachineDispatcher $dispatcher;

    /**
     * @var StateAccessorInterface
     */
    protected StateAccessorInterface $stateAccessor;

    /**
     * @var string
     */
    protected string $graph;

    /**
     * @param object                        $object
     * @param StateMachineDispatcher|null   $dispatcher
     * @param StateAccessorInterface|null   $stateAccessor
     */
    public function __construct(
        object $object,
        StateMachineDispatcher $dispatcher = null,
        StateAccessorInterface $stateAccessor = null
    ) {
        $this->object = $object;
        $this->dispatcher = $dispatcher ?: new StateMachineDispatcher();
        $this->stateAccessor = $stateAccessor ?: new PropertyPathStateAccessor();
    }

    /**
     * @throws Exception\ObjectException
     * @throws Exception\StateException
     * @throws Exception\NoSuchPropertyException
     */
    public function initialize()
    {
        try {
            $initialState = $this->stateAccessor->getState($this->object);
        } catch (Exception\NoSuchPropertyException $e) {
            throw new Exception\ObjectException(sprintf(
                'StateMachine can\'t be initialized because the defined property_path of object "%s" does not exist.',
                $this->getObject() ? get_class($this->getObject()) : null
            ), $e->getCode(), $e);
        }

        if (null === $initialState) {
            $initialState = $this->findInitialState();
            $this->stateAccessor->setState($this->object, $initialState);

            $this->dispatcher->dispatch(FiniteEvents::SET_INITIAL_STATE, new StateMachineEvent($this));
        }

        $this->currentState = $this->getState($initialState);

        $this->dispatcher->dispatch(FiniteEvents::INITIALIZE, new StateMachineEvent($this));
    }

    /**
     * {@inheritdoc}
     *
     * @throws Exception\StateException
     * @throws Exception\TransitionException
     * @throws Exception\NoSuchPropertyException
     */
    public function apply($transitionName, array $parameters = array())
    {
        $transition = $this->getTransition($transitionName);
        $event = new TransitionEvent($this->getCurrentState(), $transition, $this, $parameters);
        if (!$this->can($transition, $parameters)) {
            throw new Exception\StateException(sprintf(
                'The "%s" transition can not be applied to the "%s" state of object "%s" with graph "%s".',
                $transition->getName(),
                $this->currentState->getName(),
                $this->getObject() ? get_class($this->getObject()) : null,
                $this->getGraph()
            ));
        }

        $this->dispatchTransitionEvent($transition, $event, FiniteEvents::PRE_TRANSITION);

        $returnValue = $transition->process($this);
        $this->stateAccessor->setState($this->object, $transition->getState());
        $this->currentState = $this->getState($transition->getState());

        $this->dispatchTransitionEvent($transition, $event, FiniteEvents::POST_TRANSITION);

        return $returnValue;
    }


    public function can($transition, array $parameters = array()): bool
    {
        $transition = $transition instanceof TransitionInterface ? $transition : $this->getTransition($transition);

        if (null !== $transition->getGuard() && !call_user_func($transition->getGuard(), $this)) {
            return false;
        }

        if (!in_array($transition->getName(), $this->getCurrentState()->getTransitions())) {
            return false;
        }

        $event = new TransitionEvent($this->getCurrentState(), $transition, $this, $parameters);
        $this->dispatchTransitionEvent($transition, $event, FiniteEvents::TEST_TRANSITION);

        return !$event->isRejected();
    }


    public function addState($state)
    {
        if (!$state instanceof StateInterface) {
            $state = new State($state);
        }

        $this->states[$state->getName()] = $state;
    }

    public function addTransition($transition, $initialState = null, $finalState = null)
    {
        if ((null === $initialState || null === $finalState) && !$transition instanceof TransitionInterface) {
            throw new \InvalidArgumentException(
                'You must provide a TransitionInterface instance or the $transition, '.
                '$initialState and $finalState parameters'
            );
        }
        // If transition isn't a TransitionInterface instance, we create one from the states date
        if (!$transition instanceof TransitionInterface) {
            try {
                $transition = $this->getTransition($transition);
            } catch (Exception\TransitionException $e) {
                $transition = new Transition($transition, $initialState, $finalState);
            }
        }

        $this->transitions[$transition->getName()] = $transition;

        // We add missing states to the State Machine
        try {
            $this->getState($transition->getState());
        } catch (Exception\StateException $e) {
            $this->addState($transition->getState());
        }
        foreach ($transition->getInitialStates() as $state) {
            try {
                $this->getState($state);
            } catch (Exception\StateException $e) {
                $this->addState($state);
            }
            $state = $this->getState($state);
            if ($state instanceof State) {
                $state->addTransition($transition);
            }
        }
    }


    public function getTransition(string $name): TransitionInterface
    {
        if (!isset($this->transitions[$name])) {
            throw new Exception\TransitionException(sprintf(
                'Unable to find a transition called "%s" on object "%s" with graph "%s".',
                $name,
                $this->getObject() ? get_class($this->getObject()) : null,
                $this->getGraph()
            ));
        }

        return $this->transitions[$name];
    }

    /**
     * @param string $name
     * @return StateInterface
     * @throws Exception\StateException
     */
    public function getState(string $name): StateInterface
    {
        $name = (string) $name;

        if (!isset($this->states[$name])) {
            throw new Exception\StateException(sprintf(
                'Unable to find a state called "%s" on object "%s" with graph "%s".',
                $name,
                $this->getObject() ? get_class($this->getObject()) : null,
                $this->getGraph()
            ));
        }

        return $this->states[$name];
    }

    public function getTransitions(): array
    {
        return array_keys($this->transitions);
    }


    public function getStates(): array
    {
        return array_keys($this->states);
    }

    public function setObject($object)
    {
        $this->object = $object;
    }

    public function getObject(): object
    {
        return $this->object;
    }

    public function getCurrentState(): StateInterface
    {
        return $this->currentState;
    }

    /**
     * Find and return the Initial state if exists.
     *
     * @return string
     *
     * @throws Exception\StateException
     */
    protected function findInitialState(): string
    {
        foreach ($this->states as $state) {
            if (State::TYPE_INITIAL === $state->getType()) {
                return $state->getName();
            }
        }

        throw new Exception\StateException(sprintf(
            'No initial state found on object "%s" with graph "%s".',
            $this->getObject() ? get_class($this->getObject()) : null,
            $this->getGraph()
        ));
    }

    /**
     * @param StateMachineDispatcher $dispatcher
     */
    public function setDispatcher(StateMachineDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return StateMachineDispatcher
     */
    public function getDispatcher(): StateMachineDispatcher
    {
        return $this->dispatcher;
    }

    /**
     * @param StateAccessorInterface $stateAccessor
     */
    public function setStateAccessor(StateAccessorInterface $stateAccessor)
    {
        $this->stateAccessor = $stateAccessor;
    }


    public function hasStateAccessor(): bool
    {
        return null !== $this->stateAccessor;
    }

    public function setGraph(string $graph)
    {
        $this->graph = $graph;
    }

    public function getGraph(): string
    {
        return $this->graph;
    }

    public function findStateWithProperty($property, $value = null): array
    {
        return array_keys(
            array_map(
                function (State $state) {
                    return $state->getName();
                },
                array_filter(
                    $this->states,
                    function (State $state) use ($property, $value) {
                        if (!$state->has($property)) {
                            return false;
                        }

                        if (null !== $value && $state->get($property) !== $value) {
                            return false;
                        }

                        return true;
                    }
                )
            )
        );
    }

    /**
     * Dispatches event for the transition
     *
     * @param TransitionInterface $transition
     * @param TransitionEvent $event
     * @param string $transitionState
     */
    private function dispatchTransitionEvent(TransitionInterface $transition, TransitionEvent $event, string $transitionState)
    {
        $this->dispatcher->dispatch($transitionState, $event);
        $this->dispatcher->dispatch($transitionState.'.'.$transition->getName(), $event);
        if (null !== $this->getGraph()) {
            $this->dispatcher->dispatch($transitionState.'.'.$this->getGraph().'.'.$transition->getName(), $event);
        }
    }
}
