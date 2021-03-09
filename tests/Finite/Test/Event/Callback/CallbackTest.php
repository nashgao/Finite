<?php

declare(strict_types=1);

namespace Finite\Test\Event\Callback;

use Finite\Event\Callback\Callback;

/**
 * @internal
 * @coversNothing
 */
class CallbackTest extends \PHPUnit_Framework_TestCase
{
    public function testInvokeWithGoodSpec()
    {
        $spec = $this->getMockBuilder('Finite\Event\Callback\CallbackSpecification')->disableOriginalConstructor()->getMock();
        $callableMock = $this->getMockBuilder('\stdClass')->setMethods(['call'])->getMock();
        $event = $this->getMockBuilder('Finite\Event\TransitionEvent')->disableOriginalConstructor()->getMock();
        $stateMachine = $this->getMockBuilder('Finite\StateMachine\StateMachine')->disableOriginalConstructor()->getMock();

        $event->expects($this->once())->method('getStateMachine')->will($this->returnValue($stateMachine));
        $spec->expects($this->once())->method('isSatisfiedBy')->with($event)->will($this->returnValue(true));

        $callableMock->expects($this->once())->method('call');

        $callback = new Callback($spec, [$callableMock, 'call']);
        $callback($event);
    }

    public function testInvokeWithBadSpec()
    {
        $spec = $this->getMockBuilder('Finite\Event\Callback\CallbackSpecification')->disableOriginalConstructor()->getMock();
        $callableMock = $this->getMockBuilder('\stdClass')->setMethods(['call'])->getMock();
        $event = $this->getMockBuilder('Finite\Event\TransitionEvent')->disableOriginalConstructor()->getMock();

        $spec->expects($this->once())->method('isSatisfiedBy')->with($event)->will($this->returnValue(false));
        $callableMock->expects($this->never())->method('call');

        $callback = new Callback($spec, [$callableMock, 'call']);
        $callback($event);
    }
}
