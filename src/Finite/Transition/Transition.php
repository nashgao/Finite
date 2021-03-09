<?php

namespace Finite\Transition;

use Finite\Exception\TransitionException;
use Finite\StateMachine\StateMachineInterface;
use Finite\State\StateInterface;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;
use Symfony\Component\OptionsResolver\Exception\UndefinedOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The base Transition class.
 * Feel free to extend it to fit to your needs.
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 * @author Michal Dabrowski <dabrowski@brillante.pl>
 */
class Transition implements PropertiesAwareTransitionInterface
{
    /**
     * @var array
     */
    protected array $initialStates;

    /**
     * @var string
     */
    protected string $state;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var callable|null
     */
    protected $guard;

    /**
     * @var OptionsResolver
     */
    protected OptionsResolver $propertiesOptionsResolver;

    /**
     * @param string               $name
     * @param string|array         $initialStates
     * @param string               $state
     * @param callable|null         $guard
     * @param OptionsResolver|null $propertiesOptionsResolver
     */
    public function __construct(
        string $name,
        $initialStates,
        string $state,
        ?callable $guard = null,
        OptionsResolver $propertiesOptionsResolver = null
    ) {
        if (null !== $guard && !is_callable($guard)) {
            throw new \InvalidArgumentException('Invalid callable guard argument passed to Transition::__construct().');
        }

        $this->name = $name;
        $this->state = $state;
        $this->initialStates = (array) $initialStates;
        $this->guard = $guard;
        $this->propertiesOptionsResolver = $propertiesOptionsResolver ?: new OptionsResolver();
    }

    public function addInitialState($state)
    {
        if ($state instanceof StateInterface) {
            $state = $state->getName();
        }

        $this->initialStates[] = $state;
    }

    public function getInitialStates(): array
    {
        return $this->initialStates;
    }

    public function getState():string
    {
        return $this->state;
    }

    public function process(StateMachineInterface $stateMachine, array $parameters)
    {
    }

    public function getName():string
    {
        return $this->name;
    }

    public function getGuard(): ?callable
    {
        return $this->guard;
    }

    public function resolveProperties(array $properties): array
    {
        try {
            return $this->propertiesOptionsResolver->resolve($properties);
        } catch (MissingOptionsException $e) {
            throw new TransitionException(
                'Testing or applying this transition need a parameter. Provide it or set it optional.',
                $e->getCode(),
                $e
            );
        } catch (UndefinedOptionsException $e) {
            throw new TransitionException(
                'You provided an unknown property to test() or apply(). Remove it or declare it in your graph.',
                $e->getCode(),
                $e
            );
        }
    }

    public function has($property): bool
    {
        return array_key_exists($property, $this->getProperties());
    }

    public function get($property, $default = null)
    {
        $properties = $this->getProperties();

        return $this->has($property) ? $properties[$property] : $default;
    }

    public function getProperties(): array
    {
        $missingOptions = $this->propertiesOptionsResolver->getMissingOptions();

        if (0 === count($missingOptions)) {
            return $this->propertiesOptionsResolver->resolve(array());
        }

        $options = array_combine($missingOptions, array_fill(0, count($missingOptions), null));

        return array_diff_key(
            $this->propertiesOptionsResolver->resolve($options),
            array_combine($missingOptions, $missingOptions)
        );
    }

    public function __toString(): string
    {
        return $this->getName();
    }
}
