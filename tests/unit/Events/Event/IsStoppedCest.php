<?php
declare(strict_types=1);

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalconphp.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Phalcon\Test\Unit\Events\Event;

use Phalcon\Events\Event;
use UnitTester;

class IsStoppedCest
{
    /**
     * Tests Phalcon\Events\Event :: isStopped()
     *
     * @author Sid Roberts <https://github.com/SidRoberts>
     * @since  2019-05-20
     */
    public function eventsEventIsStopped(UnitTester $I)
    {
        $I->wantToTest('Events\Event - isStopped()');

        $event = new Event(
            'some-type:beforeSome',
            $this
        );

        $I->assertFalse(
            $event->isStopped()
        );

        $event->stop();

        $I->assertTrue(
            $event->isStopped()
        );
    }
}
