<?php

namespace Finite\Event\Callback;

use Finite\Event\TransitionEvent;

/**
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class Callback implements CallbackInterface
{
    /**
     * @var CallbackSpecificationInterface
     */
    private CallbackSpecificationInterface $specification;

    /**
     * @var callable
     */
    private $callable;

    /**
     * @param CallbackSpecificationInterface $callbackSpecification
     * @param callable                       $callable
     */
    public function __construct(CallbackSpecificationInterface $callbackSpecification, callable $callable)
    {
        $this->specification = $callbackSpecification;
        $this->callable = $callable;
    }

    /**
     * @return CallbackSpecificationInterface
     */
    public function getSpecification(): CallbackSpecificationInterface
    {
        return $this->specification;
    }


    public function __invoke(TransitionEvent $event)
    {
        if ($this->specification->isSatisfiedBy($event)) {
            $this->call($event->getStateMachine()->getObject(), $event);
        }
    }


    protected function call($object, TransitionEvent $event): bool
    {
        return call_user_func($this->callable, $object, $event);
    }
}
