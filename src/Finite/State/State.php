<?php

namespace Finite\State;

use Finite\Transition\TransitionInterface;

/**
 * The base State class.
 * Feel free to extend it to fit to your needs.
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 * @author Michal Dabrowski <dabrowski@brillante.pl>
 */
class State implements StateInterface
{
    /**
     * The state type.
     *
     * @var string
     */
    protected string $type;

    /**
     * The transition name.
     *
     * @var array
     */
    protected array $transitions;

    /**
     * The state name.
     *
     * @var string
     */
    protected string $name;

    /**
     * @var array
     */
    protected array $properties;

    public function __construct($name, $type = self::TYPE_NORMAL, array $transitions = array(), array $properties = array())
    {
        $this->name = $name;
        $this->type = $type;
        $this->transitions = $transitions;
        $this->properties = $properties;
    }


    public function isInitial(): bool
    {
        return self::TYPE_INITIAL === $this->type;
    }


    public function isFinal(): bool
    {
        return self::TYPE_FINAL === $this->type;
    }


    public function isNormal(): bool
    {
        return self::TYPE_NORMAL === $this->type;
    }


    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param TransitionInterface|string $transition
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

    /**
     * {@inheritdoc}
     *
     * @deprecated Deprecated since version 1.0.0-BETA2. Use {@link StateMachine::can($transition)} instead.
     */
    public function can($transition): bool
    {
        if ($transition instanceof TransitionInterface) {
            $transition = $transition->getName();
        }

        return in_array($transition, $this->transitions);
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

    public function __toString(): string
    {
        return $this->getName();
    }
}
