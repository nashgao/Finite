<?php

namespace Finite\StateMachine;

use Finite\Exception\TransitionException;
use Finite\Transition\TransitionInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Security Aware state machine.
 * Use the Symfony Security Component and ACL.
 *
 * Need an ACL implementation available, Doctrine DBAL by default.
 *
 * @author Yohan Giarelli <yohan@frequence-web.fr>
 */
class SecurityAwareStateMachine extends StateMachine
{
    /**
     * @var AuthorizationCheckerInterface
     */
    protected AuthorizationCheckerInterface$authorizationChecker;

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     */
    public function setSecurityContext(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param TransitionInterface|string $transition
     * @param array $parameters
     * @return bool
     * @throws TransitionException
     */
    public function can($transition, array $parameters = array()): bool
    {
        $transition = $transition instanceof TransitionInterface ? $transition : $this->getTransition($transition);

        if (!$this->authorizationChecker->isGranted($transition->getName(), $this->getObject())) {
            return false;
        }

        return parent::can($transition, $parameters);
    }
}
