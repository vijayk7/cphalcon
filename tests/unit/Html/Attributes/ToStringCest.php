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

namespace Phalcon\Test\Unit\Html\Attributes;

use Phalcon\Html\Attributes;
use Phalcon\Test\Fixtures\Traits\DiTrait;
use UnitTester;

class ToStringCest
{
    use DiTrait;

    public function _before(UnitTester $I)
    {
        $this->setNewFactoryDefault();
    }

    /**
     * Tests Phalcon\Html\Attributes :: __toString()
     *
     * @author Sid Roberts <https://github.com/SidRoberts>
     * @since  2019-06-02
     */
    public function htmlAttributesToString(UnitTester $I)
    {
        $I->wantToTest('Html\Attributes - __toString()');

        $attributes = new Attributes(
            [
                'type'  => 'text',
                'class' => 'form-control',
                'name'  => 'q',
                'value' => '',
            ]
        );

        $I->assertEquals(
            ' type="text" name="q" value="" class="form-control"',
            (string) $attributes
        );
    }
}
