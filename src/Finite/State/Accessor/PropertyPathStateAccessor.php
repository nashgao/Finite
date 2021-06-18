<?php

declare(strict_types=1);

namespace Finite\State\Accessor;

use Finite\Exception\NoSuchPropertyException;
use Symfony\Component\PropertyAccess\Exception\NoSuchPropertyException as SymfonyNoSuchPropertyException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

/**
 * Property path implementation of state accessor.
 */
class PropertyPathStateAccessor implements StateAccessorInterface
{
    private string $propertyPath;

    private PropertyAccessorInterface $propertyAccessor;

    /**
     * @param string $propertyPath
     * @param PropertyAccessorInterface|null $propertyAccessor
     */
    public function __construct(string $propertyPath = 'finiteState', PropertyAccessorInterface $propertyAccessor = null)
    {
        $this->propertyPath = $propertyPath;
        $this->propertyAccessor = $propertyAccessor ?: PropertyAccess::createPropertyAccessor();
    }

    public function getState(object $object): ?string
    {
        try {
            return $this->propertyAccessor->getValue($object, $this->propertyPath);
        } catch (SymfonyNoSuchPropertyException $e) {
            throw new NoSuchPropertyException(sprintf(
                'Property path "%s" on object "%s" does not exist.',
                $this->propertyPath,
                get_class($object)
            ), $e->getCode(), $e);
        }
    }

    public function setState(object &$object, string $value)
    {
        try {
            $this->propertyAccessor->setValue($object, $this->propertyPath, $value);
        } catch (SymfonyNoSuchPropertyException $e) {
            throw new NoSuchPropertyException(sprintf(
                'Property path "%s" on object "%s" does not exist.',
                $this->propertyPath,
                get_class($object)
            ), $e->getCode(), $e);
        }
    }
}
