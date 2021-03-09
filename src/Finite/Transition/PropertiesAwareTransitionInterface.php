<?php

declare(strict_types=1);

namespace Finite\Transition;

use Finite\Exception\TransitionException;
use Finite\PropertiesAwareInterface;

/**
 * Interface for transition with properties.
 */
interface PropertiesAwareTransitionInterface extends TransitionInterface, PropertiesAwareInterface
{
    /**
     * Returns an array with resolved properties of transition at the moment
     * it is applied. It's a merge between default properties and "at-apply" properties.
     *
     * @throws TransitionException
     */
    public function resolveProperties(array $properties): array;
}
