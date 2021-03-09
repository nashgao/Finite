<?php

declare(strict_types=1);

namespace Finite\Event;

/**
 * The class that contains event names.
 */
final class FiniteEvents
{
    const /*
         * This event is thrown when an object got its initial state
         */
        SET_INITIAL_STATE = 'finite.set_initial_state';

    const 
        /*
         * This event is thrown each time a StateMachine is initialized
         */
        INITIALIZE = 'finite.initialize';

    const 
        /*
         * This event is thrown before transitions are processed
         */
        PRE_TRANSITION = 'finite.pre_transition';

    const 
        /*
         * This event is thrown after transitions are processed
         */
        POST_TRANSITION = 'finite.post_transition';

    const 
        /*
         * Fired when a transition test is made.
         */
        TEST_TRANSITION = 'finite.test_transition';
}
