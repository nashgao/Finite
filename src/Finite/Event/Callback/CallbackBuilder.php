<?php

namespace Finite\Event\Callback;

use Finite\StateMachine\StateMachineInterface;

/**
 * Builds a Callback instance.
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class CallbackBuilder
{
    /**
     * @var StateMachineInterface
     */
    private StateMachineInterface $stateMachine;

    /**
     * @var array
     */
    private array $from;

    /**
     * @var array
     */
    private array $to;

    /**
     * @var array
     */
    private array $on;

    /**
     * @var callable
     */
    private $callable;

    /**
     * @param StateMachineInterface $sm
     * @param array                 $from
     * @param array                 $to
     * @param array                 $on
     * @param callable|null         $callable
     */
    public function __construct(StateMachineInterface $sm, array $from = array(), array $to = array(), array $on = array(), callable $callable = null)
    {
        $this->stateMachine = $sm;
        $this->from = $from;
        $this->to = $to;
        $this->on = $on;
        $this->callable = $callable;
    }

    /**
     * @param array $from
     *
     * @return CallbackBuilder
     */
    public function setFrom(array $from): CallbackBuilder
    {
        $this->from = $from;

        return $this;
    }

    /**
     * @param array $to
     *
     * @return CallbackBuilder
     */
    public function setTo(array $to): CallbackBuilder
    {
        $this->to = $to;

        return $this;
    }

    /**
     * @param array $on
     *
     * @return CallbackBuilder
     */
    public function setOn(array $on): CallbackBuilder
    {
        $this->on = $on;

        return $this;
    }

    /**
     * @param callable $callable
     *
     * @return CallbackBuilder
     */
    public function setCallable(callable $callable): CallbackBuilder
    {
        $this->callable = $callable;

        return $this;
    }

    /**
     * @param string $from
     *
     * @return CallbackBuilder
     */
    public function addFrom(string $from): CallbackBuilder
    {
        $this->from[] = $from;

        return $this;
    }

    /**
     * @param string $to
     *
     * @return CallbackBuilder
     */
    public function addTo(string $to): CallbackBuilder
    {
        $this->to[] = $to;

        return $this;
    }

    /**
     * @param string $on
     *
     * @return CallbackBuilder
     */
    public function addOn(string $on): CallbackBuilder
    {
        $this->from[] = $on;

        return $this;
    }

    /**
     * @return Callback
     */
    public function getCallback()
    {
        return new Callback(
            new CallbackSpecification($this->stateMachine, $this->from, $this->to, $this->on),
            $this->callable
        );
    }

    /**
     * @param StateMachineInterface $sm
     * @param array                 $from
     * @param array                 $to
     * @param array                 $on
     * @param callable|null         $callable
     *
     * @return CallbackBuilder
     */
    public static function create(StateMachineInterface $sm, array $from = array(), array $to = array(), array $on = array(), callable $callable = null): CallbackBuilder
    {
        return new self($sm, $from, $to, $on, $callable);
    }
}
