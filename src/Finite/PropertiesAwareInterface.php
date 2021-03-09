<?php

declare(strict_types=1);

namespace Finite;

/**
 * Interface that all class that have properties must implements.
 */
interface PropertiesAwareInterface
{
    public function has(string $property): bool;

    /**
     * @param mixed $default
     *
     * @return mixed
     */
    public function get(string $property, $default = null);

    /**
     * Returns optional state properties.
     */
    public function getProperties(): array;
}
