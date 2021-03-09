<?php

declare(strict_types=1);

namespace Finite\Event\Callback;

use Finite\StateMachine\StateMachineInterface;

/**
 * Builds a Callback instance.
 */
class CallbackBuilder
{
    private StateMachineInterface $stateMachine;

    private array $from;

    private array $to;

    private array $on;

    /**
     * @var callable
     */
    private $callable;

    public function __construct(StateMachineInterface $sm, array $from = [], array $to = [], array $on = [], callable $callable = null)
    {
        $this->stateMachine = $sm;
        $this->from = $from;
        $this->to = $to;
        $this->on = $on;
        $this->callable = $callable;
    }

    public function setFrom(array $from): CallbackBuilder
    {
        $this->from = $from;

        return $this;
    }

    public function setTo(array $to): CallbackBuilder
    {
        $this->to = $to;

        return $this;
    }

    public function setOn(array $on): CallbackBuilder
    {
        $this->on = $on;

        return $this;
    }

    public function setCallable(callable $callable): CallbackBuilder
    {
        $this->callable = $callable;

        return $this;
    }

    public function addFrom(string $from): CallbackBuilder
    {
        $this->from[] = $from;

        return $this;
    }

    public function addTo(string $to): CallbackBuilder
    {
        $this->to[] = $to;

        return $this;
    }

    public function addOn(string $on): CallbackBuilder
    {
        $this->from[] = $on;

        return $this;
    }

    /**
     * @return callback
     */
    public function getCallback()
    {
        return new Callback(
            new CallbackSpecification($this->stateMachine, $this->from, $this->to, $this->on),
            $this->callable
        );
    }

    public static function create(StateMachineInterface $sm, array $from = [], array $to = [], array $on = [], callable $callable = null): CallbackBuilder
    {
        return new self($sm, $from, $to, $on, $callable);
    }
}
