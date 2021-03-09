<?php

declare(strict_types=1);

namespace Finite\Test\Event\Callback;

use Finite\Event\Callback\CallbackBuilderFactory;

/**
 * @internal
 * @coversNothing
 */
class CallbackBuilderFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testItConstructsCallbackBuilder()
    {
        $sm = $this->createMock('Finite\StateMachine\StateMachineInterface');

        $factory = new CallbackBuilderFactory();

        $this->assertInstanceOf('Finite\Event\Callback\CallbackBuilder', $builder = $factory->createBuilder($sm));
        $this->assertNotSame($builder, $factory->createBuilder($sm));
    }
}
