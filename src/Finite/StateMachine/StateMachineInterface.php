<?php

namespace Finite\StateMachine;

use Finite\Event\StateMachineDispatcher;
use Finite\Exception\TransitionException;
use Finite\State\Accessor\StateAccessorInterface;
use Finite\State\StateInterface;
use Finite\Transition\TransitionInterface;
use InvalidArgumentException;

/**
 * The Finite State Machine base Interface.
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
interface StateMachineInterface
{
    /**
     * Initialize the State Machine current state.
     */
    public function initialize();

    /**
     * Apply a transition.
     *
     * @param string $transitionName
     * @param array  $parameters
     *
     * @return mixed
     */
    public function apply(string $transitionName, array $parameters = array());

    /**
     * Returns if the transition is applicable.
     *
     * @param string|TransitionInterface $transition
     * @param array                      $parameters
     *
     * @return bool
     */
    public function can($transition, array $parameters = array()): bool;

    /**
     * @param string|StateInterface $state
     */
    public function addState($state);

    /**
     * @param string|TransitionInterface $transition
     * @param string|null                $initialState
     * @param string|null                $finalState
     *
     * @throws InvalidArgumentException
     */
    public function addTransition($transition, $initialState = null, $finalState = null);

    /**
     * Returns a transition by its name.
     *
     * @param string $name
     *
     * @return TransitionInterface
     *
     * @throws TransitionException
     */
    public function getTransition(string $name): TransitionInterface;

    /**
     * @param string $name
     *
     * @return StateInterface
     *
     * @throws TransitionException
     */
    public function getState(string $name): StateInterface;

    /**
     * Returns an array containing all the transitions names.
     */
    public function getTransitions(): array;

    /**
     * Returns an array containing all the states names.
     */
    public function getStates(): array;


    public function setObject(object $object);


    public function getObject(): object;


    public function getCurrentState(): StateInterface;


    public function getDispatcher(): StateMachineDispatcher;


    public function setStateAccessor(StateAccessorInterface $stateAccessor);


    public function hasStateAccessor(): bool;


    public function setGraph(string $graph);


    public function getGraph(): string;

    /**
     * Find a state which have a given property, with an optional given value.
     * It is useful for looking for objects having a given property in database for example.
     *
     * @param string $property
     * @param mixed  $value
     *
     * @return array
     */
    public function findStateWithProperty(string $property, $value = null): array;
}
