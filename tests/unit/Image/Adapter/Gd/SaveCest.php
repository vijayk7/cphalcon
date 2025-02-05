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

namespace Phalcon\Test\Unit\Image\Adapter\Gd;

use Phalcon\Image\Adapter\Gd;
use Phalcon\Test\Fixtures\Traits\GdTrait;
use UnitTester;

class SaveCest
{
    use GdTrait;

    /**
     * Tests Phalcon\Image\Adapter\Gd :: save()
     *
     * @author Phalcon Team <team@phalconphp.com>
     * @since  2018-11-13
     */
    public function imageAdapterGdSave(UnitTester $I)
    {
        $I->wantToTest('Image\Adapter\Gd - save()');

        $outputDir   = 'tests/image/gd';
        $resultImage = 'save.';

        foreach ($this->getImages() as $type => $imagePath) {
            $image = new Gd(
                $imagePath
            );

            $output = outputDir($outputDir . '/' . $resultImage . $type);

            $actual = $image->save($output);

            $I->assertInstanceOf(Gd::class, $actual);

            $I->amInPath(
                outputDir($outputDir)
            );

            $I->seeFileFound($resultImage . $type);

            $I->safeDeleteFile($resultImage . $type);
        }
    }
}
