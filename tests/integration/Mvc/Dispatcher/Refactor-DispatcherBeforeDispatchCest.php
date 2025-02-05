<?php

namespace Phalcon\Test\Integration\Mvc\Dispatcher;

use Exception;
use IntegrationTester;
use Phalcon\Test\Integration\Mvc\Dispatcher\Helper\BaseDispatcher;

/**
 * \Phalcon\Test\Integration\Mvc\Dispatcher\DispatcherBeforeDispatchTest
 * Tests the \Phalcon\Dispatcher and Phalcon\Mvc\Dispatcher "beforeDispatch"
 * event.
 *
 * @link          https://docs.phalconphp.com/en/latest/reference/dispatching.html
 *
 * @copyright (c) 2011-2017 Phalcon Team
 * @link          http://www.phalconphp.com
 * @author        Andres Gutierrez <andres@phalconphp.com>
 * @author        Nikolaos Dimopoulos <nikos@phalconphp.com>
 *
 * The contents of this file are subject to the New BSD License that is
 * bundled with this package in the file docs/LICENSE.txt
 *
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email to license@phalconphp.com
 * so that we can send you a copy immediately.
 */
class DispatcherBeforeDispatchCest extends BaseDispatcher
{
    /**
     * Tests the forwarding in the beforeDispatch event
     *
     * @author Mark Johnson <https://github.com/virgofx>
     * @since  2017-10-07
     */
    public function testBeforeDispatchForwardOnce(IntegrationTester $I)
    {
        $forwarded  = false;
        $dispatcher = $this->getDispatcher();

        $dispatcher->getEventsManager()->attach(
            'dispatch:beforeDispatch',
            function ($event, $dispatcher) use (&$forwarded) {
                if ($forwarded === false) {
                    $dispatcher->forward(
                        [
                            'action' => 'index2',
                        ]
                    );

                    $forwarded = true;
                }
            }
        )
        ;

        $dispatcher->dispatch();

        $expected = [
            'beforeDispatchLoop',
            'beforeDispatch',
            'beforeDispatch',
            'beforeExecuteRoute',
            'beforeExecuteRoute-method',
            'initialize-method',
            'afterInitialize',
            'index2Action',
            'afterExecuteRoute',
            'afterExecuteRoute-method',
            'afterDispatch',
            'afterDispatchLoop',
        ];
        $actual   = $this->getDispatcherListener()->getTrace();
        $I->assertEquals($expected, $actual);
    }

    /**
     * Tests returning <tt>false</tt> inside a beforeDispatch event.
     *
     * @author Mark Johnson <https://github.com/virgofx>
     * @since  2017-10-07
     */
    public function testBeforeDispatchReturnFalse(IntegrationTester $I)
    {
        $dispatcher         = $this->getDispatcher();
        $dispatcherListener = $this->getDispatcherListener();

        $dispatcher->getEventsManager()->attach(
            'dispatch:beforeDispatch',
            function () use ($dispatcherListener) {
                $dispatcherListener->trace('beforeDispatch: custom return false');

                return false;
            }
        )
        ;
        $dispatcher->dispatch();

        $expected = [
            'beforeDispatchLoop',
            'beforeDispatch',
            'beforeDispatch: custom return false',
            'afterDispatchLoop',
        ];
        $actual   = $this->getDispatcherListener()->getTrace();
        $I->assertEquals($expected, $actual);
    }

    /**
     * Tests exception handling to ensure exceptions can be properly handled
     * when thrown from inside a beforeDispatchLoop event and then ensure the
     * exception is not bubbled when returning with <tt>false</tt>.
     *
     * @author Mark Johnson <https://github.com/virgofx>
     * @since  2017-10-07
     */
    public function testBeforeDispatchWithBeforeExceptionReturningFalse(IntegrationTester $I)
    {
        $dispatcher = $this->getDispatcher();

        $dispatcher->getEventsManager()->attach(
            'dispatch:beforeDispatch',
            function () {
                throw new Exception('beforeDispatch exception occurred');
            }
        );

        $dispatcher->getEventsManager()->attach(
            'dispatch:beforeException',
            function () {
                return false;
            }
        );

        $dispatcher->dispatch();

        $expected = [
            'beforeDispatchLoop',
            'beforeDispatch',
            'beforeException: beforeDispatch exception occurred',
            'afterDispatchLoop',
        ];
        $actual   = $this->getDispatcherListener()->getTrace();
        $I->assertEquals($expected, $actual);
    }

    /**
     * Tests exception handling to ensure exceptions can be properly handled
     * via beforeException event and then will properly bubble up the stack if
     * anything other than <tt>false</tt> is returned.
     *
     * @author Mark Johnson <https://github.com/virgofx>
     * @since  2017-10-07
     */
    public function testBeforeDispatchWithBeforeExceptionBubble(IntegrationTester $I)
    {
        $dispatcher         = $this->getDispatcher();
        $dispatcherListener = $this->getDispatcherListener();

        $dispatcher->getEventsManager()->attach(
            'dispatch:beforeDispatch',
            function () {
                throw new Exception('beforeDispatch exception occurred');
            }
        );

        $dispatcher->getEventsManager()->attach(
            'dispatch:beforeException',
            function () use ($dispatcherListener) {
                $dispatcherListener->trace(
                    'beforeException: custom before exception bubble'
                );

                return null;
            }
        );

        $I->expectThrowable(
            Exception::class,
            function () use ($dispatcher) {
                $dispatcher->dispatch();
            }
        );

        $expected = [
            'beforeDispatchLoop',
            'beforeDispatch',
            'beforeException: beforeDispatch exception occurred',
            'beforeException: custom before exception bubble',
        ];
        $actual   = $this->getDispatcherListener()->getTrace();
        $I->assertEquals($expected, $actual);
    }

    /**
     * Tests dispatch forward handling inside the beforeException when a
     * beforeDispatch exception occurs.
     *
     * @author Mark Johnson <https://github.com/virgofx>
     * @since  2017-10-07
     */
    public function testBeforeDispatchWithBeforeExceptionForwardOnce(IntegrationTester $I)
    {
        $forwarded          = false;
        $dispatcher         = $this->getDispatcher();
        $dispatcherListener = $this->getDispatcherListener();

        $dispatcher->getEventsManager()->attach(
            'dispatch:beforeDispatch',
            function () use (&$forwarded) {
                if ($forwarded === false) {
                    $forwarded = true;

                    throw new Exception('beforeDispatch exception occurred');
                }
            }
        );

        $dispatcher->getEventsManager()->attach(
            'dispatch:beforeException',
            function ($event, $dispatcher) use ($dispatcherListener) {
                $dispatcherListener->trace(
                    'beforeException: custom before exception forward'
                );

                $dispatcher->forward(
                    [
                        'action' => 'index2',
                    ]
                );
            }
        );

        $dispatcher->dispatch();

        $expected = [
            'beforeDispatchLoop',
            'beforeDispatch',
            'beforeException: beforeDispatch exception occurred',
            'beforeException: custom before exception forward',
            'beforeDispatch',
            'beforeExecuteRoute',
            'beforeExecuteRoute-method',
            'initialize-method',
            'afterInitialize',
            'index2Action',
            'afterExecuteRoute',
            'afterExecuteRoute-method',
            'afterDispatch',
            'afterDispatchLoop',
        ];
        $actual   = $this->getDispatcherListener()->getTrace();
        $I->assertEquals($expected, $actual);
    }
}
