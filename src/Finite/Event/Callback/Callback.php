<?php

declare(strict_types=1);

namespace Finite\Event\Callback;

use Finite\Event\TransitionEvent;

class Callback implements CallbackInterface
{
    private CallbackSpecificationInterface $specification;

    /**
     * @var callable
     */
    private $callable;

    public function __construct(CallbackSpecificationInterface $callbackSpecification, callable $callable)
    {
        $this->specification = $callbackSpecification;
        $this->callable = $callable;
    }

    public function __invoke(TransitionEvent $event)
    {
        if ($this->specification->isSatisfiedBy($event)) {
            $this->call($event->getStateMachine()->getObject(), $event);
        }
    }

    public function getSpecification(): CallbackSpecificationInterface
    {
        return $this->specification;
    }

    /**
     * @return mixed
     */
    protected function call(object $object, TransitionEvent $event)
    {
        return call_user_func($this->callable, $object, $event);
    }
}
