<?php

declare(strict_types=1);

namespace Finite\Event\Callback;

use Finite\Event\TransitionEvent;

/**
 * Base interface for CallbackSpecification.
 */
interface CallbackSpecificationInterface
{
    /**
     * Return if this callback carried by this spec should be called on this event.
     */
    public function isSatisfiedBy(TransitionEvent $event): bool;
}
