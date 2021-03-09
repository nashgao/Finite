<?php

namespace Finite;

/**
 * Interface that all class that have properties must implements
 *
 */
interface PropertiesAwareInterface
{
    /**
     * @param string $property
     *
     * @return bool
     */
    public function has(string $property): bool;

    /**
     * @param string $property
     * @param mixed  $default
     *
     * @return mixed
     */
    public function get(string $property, $default = null);

    /**
     * Returns optional state properties.
     *
     * @return array
     */
    public function getProperties():array;
}
