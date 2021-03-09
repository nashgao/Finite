<?php

declare(strict_types=1);

namespace Finite\Event\Callback;

use Finite\Event\TransitionEvent;

/**
 * Base interface for callbacks.
 */
interface CallbackInterface
{
    public function __invoke(TransitionEvent $event);
}
