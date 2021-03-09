<?php

declare(strict_types=1);

namespace Finite\Test\Event\Callback;

use Finite\Event\Callback\CallbackBuilder;

/**
 * @internal
 * @coversNothing
 */
class CallbackBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testItBuildsCallback()
    {
        $stateMachine = $this
            ->getMockBuilder('Finite\StateMachine\StateMachine')
            ->disableOriginalConstructor()
            ->getMock();

        $callableMock = $this->getMockBuilder('\stdClass')->setMethods(['call'])->getMock();

        $callback = CallbackBuilder::create($stateMachine, [$callableMock, 'call'])
            ->setFrom(['s1'])
            ->addFrom('s2')
            ->setTo(['s2'])
            ->addTo('s3')
            ->setOn(['t12'])
            ->addOn('t23')
            ->getCallback();

        $this->assertInstanceOf('Finite\Event\Callback\Callback', $callback);
        $this->assertInstanceOf('Finite\Event\Callback\CallbackSpecification', $callback->getSpecification());
    }
}
