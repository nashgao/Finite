<?php

declare(strict_types=1);

namespace Finite\State;

use Finite\PropertiesAwareInterface;
use Finite\Transition\TransitionInterface;

/**
 * The base State Interface.
 */
interface StateInterface extends PropertiesAwareInterface
{
    const TYPE_INITIAL = 'initial';

    const TYPE_NORMAL = 'normal';

    const TYPE_FINAL = 'final';

    /**
     * Returns the state name.
     */
    public function getName(): string;

    /**
     * Returns if this state is the initial state.
     */
    public function isInitial(): bool;

    /**
     * Returns if this state is the final state.
     *
     * @return mixed
     */
    public function isFinal();

    /**
     * Returns if this state is a normal state (!($this->isInitial() || $this->isFinal()).
     *
     * @return mixed
     */
    public function isNormal();

    /**
     * Returns the state type.
     */
    public function getType(): string;

    /**
     * Returns the available transitions.
     */
    public function getTransitions(): array;

    /**
     * Returns if this state can run $transition.
     *
     * @param string|TransitionInterface $transition
     *
     * @deprecated Deprecated since version 1.0.0-BETA2. Use {@link StateMachine::can($transition)} instead.
     */
    public function can($transition): bool;
}
