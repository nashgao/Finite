<?php

declare(strict_types=1);

namespace Finite\Event\Callback;

use Finite\Event\CallbackHandler;
use Finite\Event\TransitionEvent;
use Finite\StateMachine\StateMachineInterface;

/**
 * Concrete implementation of CallbackSpecification.
 */
class CallbackSpecification implements CallbackSpecificationInterface
{
    private array $specs = [];

    private StateMachineInterface $stateMachine;

    public function __construct(StateMachineInterface $sm, array $from, array $to, array $on)
    {
        $this->stateMachine = $sm;

        $isExclusion = function ($str) { return strpos($str, '-') === 0; };
        $removeDash = function ($str) { return substr($str, 1); };

        foreach (['from', 'to', 'on'] as $clause) {
            $excludedClause = 'excluded_' . $clause;

            $this->specs[$excludedClause] = array_filter(${$clause}, $isExclusion);
            $this->specs[$clause] = array_diff(${$clause}, $this->specs[$excludedClause]);
            $this->specs[$excludedClause] = array_map($removeDash, $this->specs[$excludedClause]);

            // For compatibility with old CallbackHandler.
            // To be removed in 2.0
            if (in_array(CallbackHandler::ALL, $this->specs[$clause])) {
                $this->specs[$clause] = [];
            }
        }
    }

    public function isSatisfiedBy(TransitionEvent $event): bool
    {
        return
            $event->getStateMachine() === $this->stateMachine
            && $this->supportsClause('from', $event->getInitialState()->getName())
            && $this->supportsClause('to', $event->getTransition()->getState())
            && $this->supportsClause('on', $event->getTransition()->getName());
    }

    private function supportsClause(string $clause, string $property): bool
    {
        $excludedClause = 'excluded_' . $clause;

        return
            (count($this->specs[$clause]) === 0 || in_array($property, $this->specs[$clause]))
            && (count($this->specs[$excludedClause]) === 0 || ! in_array($property, $this->specs[$excludedClause]));
    }
}
