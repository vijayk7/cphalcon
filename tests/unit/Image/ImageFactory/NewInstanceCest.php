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

namespace Phalcon\Test\Unit\Image\ImageFactory;

use Phalcon\Image\Adapter\Imagick;
use Phalcon\Image\ImageFactory;
use UnitTester;
use function dataDir;

class NewInstanceCest
{
    /**
     * Tests Phalcon\Image\ImageFactory :: newInstance()
     *
     * @author Phalcon Team <team@phalconphp.com>
     * @since  2019-05-18
     */
    public function imageImageFactoryNewInstance(UnitTester $I)
    {
        $I->wantToTest('Image\ImageFactory - newInstance()');

        $factory = new ImageFactory();
        $file    = dataDir('assets/images/phalconphp.jpg');
        $name    = 'imagick';

        $image = $factory->newInstance($name, $file);

        $I->assertInstanceOf(
            Imagick::class,
            $image
        );

        $I->assertEquals(
            $file,
            $image->getRealpath()
        );
    }
}
