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

namespace Phalcon\Test\Unit\Crypt;

use Phalcon\Crypt;
use UnitTester;

class GetAuthDataCest
{
    /**
     * Unit Tests Phalcon\Crypt :: getAuthData()
     *
     * @author Phalcon Team <team@phalconphp.com>
     * @since  2019-05-25
     */
    public function cryptGetAuthData(UnitTester $I)
    {
        $I->wantToTest('Crypt - getAuthData()');

        $I->skipTest('Need implementation');
    }
}
