<?php

namespace Finite\Factory;

use Finite\Exception\FactoryException;
use Finite\StateMachine\StateMachineInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A concrete implementation of State Machine Factory using the sf2 DIC.
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class SymfonyDependencyInjectionFactory extends AbstractFactory
{
    /**
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * @var string
     */
    protected string $key;

    /**
     * @param ContainerInterface $container
     * @param string $key
     * @throws FactoryException
     */
    public function __construct(ContainerInterface $container, string $key)
    {
        $this->container = $container;
        $this->key = $key;

        if (!$container->has($key)) {
            throw new FactoryException(
                sprintf(
                    'You must define the "%s" service as your StateMachine definition',
                    $key
                )
            );
        }
    }


    protected function createStateMachine(): StateMachineInterface
    {
        return $this->container->get($this->key);
    }
}
