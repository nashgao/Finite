<?php

namespace Finite\Factory;

use Finite\StateMachine\StateMachineInterface;
use Pimple;

/**
 * A concrete implementation of State Machine Factory using Pimple.
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class PimpleFactory extends AbstractFactory
{
    /**
     * @var Pimple
     */
    protected Pimple $container;

    /**
     * @var string
     */
    protected string $id;

    /**
     * @param Pimple $container
     * @param string $id
     */
    public function __construct(Pimple $container, string $id)
    {
        $this->container = $container;
        $this->id = $id;
    }


    protected function createStateMachine(): StateMachineInterface
    {
        return $this->container[$this->id];
    }
}
