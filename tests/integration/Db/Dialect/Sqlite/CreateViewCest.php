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

namespace Phalcon\Test\Integration\Db\Dialect\Sqlite;

use Codeception\Example;
use IntegrationTester;
use Phalcon\Db\Dialect\Sqlite;

class CreateViewCest
{
    /**
     * Tests Phalcon\Db\Dialect\Sqlite :: createView()
     *
     * @author       Phalcon Team <team@phalconphp.com>
     * @since        2017-02-26
     *
     * @dataProvider getCreateViewFixtures
     */
    public function dbDialectSqliteCreateView(IntegrationTester $I, Example $example)
    {
        $I->wantToTest('Db\Dialect\Sqlite - createView()');

        $definition = $example[0];
        $schema     = $example[1];
        $expected   = $example[2];

        $dialect = new Sqlite();

        $actual = $dialect->createView(
            'test_view',
            $definition,
            $schema
        );

        $I->assertInternalType(
            'string',
            $actual
        );

        $I->assertEquals($expected, $actual);
    }

    protected function getCreateViewFixtures(): array
    {
        return [
            [
                [
                    'sql' => 'SELECT 1',
                ],
                null,
                'CREATE VIEW "test_view" AS SELECT 1',
            ],

            [
                [
                    'sql' => 'SELECT 1',
                ],
                'schema',
                'CREATE VIEW "schema"."test_view" AS SELECT 1',
            ],
        ];
    }
}
