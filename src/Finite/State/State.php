<?php

declare(strict_types=1);

namespace Finite\State;

use Finite\Transition\TransitionInterface;

/**
 * The base State class.
 * Feel free to extend it to fit to your needs.
 */
class State implements StateInterface
{
    /**
     * The state type.
     */
    protected string $type;

    /**
     * The transition name.
     */
    protected array $transitions;

    /**
     * The state name.
     */
    protected string $name;

    protected array $properties;

    public function __construct(string $name, $type = self::TYPE_NORMAL, array $transitions = [], array $properties = [])
    {
        $this->name = $name;
        $this->type = $type;
        $this->transitions = $transitions;
        $this->properties = $properties;
    }

    public function __toString(): string
    {
        return $this->getName();
    }

    public function isInitial(): bool
    {
        return $this->type === self::TYPE_INITIAL;
    }

    public function isFinal(): bool
    {
        return $this->type === self::TYPE_FINAL;
    }

    public function isNormal(): bool
    {
        return $this->type === self::TYPE_NORMAL;
    }

    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string|TransitionInterface $transition
     */
    public function addTransition($transition)
    {
        if ($transition instanceof TransitionInterface) {
            $transition = $transition->getName();
        }

        $this->transitions[] = $transition;
    }

    public function setTransitions(array $transitions)
    {
        foreach ($transitions as $transition) {
            $this->addTransition($transition);
        }
    }

    public function getTransitions(): array
    {
        return $this->transitions;
    }

    public function has($property): bool
    {
        return array_key_exists($property, $this->properties);
    }

    public function get($property, $default = null)
    {
        return $this->has($property) ? $this->properties[$property] : $default;
    }

    public function getProperties(): array
    {
        return $this->properties;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setProperties(array $properties)
    {
        $this->properties = $properties;
    }
}
