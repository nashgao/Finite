<?php

namespace Finite;

use Finite\Factory\FactoryInterface;
use Finite\StateMachine\StateMachine;
use Finite\StateMachine\StateMachineInterface;

/**
 * The Finite context.
 * It provides easy ways to deal with Stateful objects, and factory.
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class Context
{
    /**
     * @var FactoryInterface
     */
    protected FactoryInterface $factory;

    /**
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param object $object
     * @param string $graph
     *
     * @return string
     */
    public function getState(object $object, $graph = 'default'): string
    {
        return $this->getStateMachine($object, $graph)->getCurrentState()->getName();
    }

    /**
     * @param object $object
     * @param string $graph
     * @param bool $asObject
     *
     * @return array<string>
     * @throws Exception\TransitionException
     */
    public function getTransitions(object $object, $graph = 'default', $asObject = false): array
    {
        if (!$asObject) {
            return $this->getStateMachine($object, $graph)->getCurrentState()->getTransitions();
        }

        $stateMachine = $this->getStateMachine($object, $graph);

        return array_map(
            function ($transition) use ($stateMachine) {
                return $stateMachine->getTransition($transition);
            },
            $stateMachine->getCurrentState()->getTransitions()
        );
    }

    public function getProperties(object $object, $graph = 'default'): array
    {
        return $this->getStateMachine($object, $graph)->getCurrentState()->getProperties();
    }

    /**
     * @param object $object
     * @param string $property
     * @param string $graph
     *
     * @return bool
     */
    public function hasProperty(object $object, string $property, string $graph = 'default'): bool
    {
        return $this->getStateMachine($object, $graph)->getCurrentState()->has($property);
    }

    /**
     * @param object $object
     * @param string $graph
     *
     * @return StateMachineInterface
     */
    public function getStateMachine(object $object, $graph = 'default'): StateMachineInterface
    {
        return $this->getFactory()->get($object, $graph);
    }

    /**
     * @return FactoryInterface
     */
    public function getFactory(): FactoryInterface
    {
        return $this->factory;
    }
}
