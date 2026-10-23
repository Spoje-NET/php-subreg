<?php

declare(strict_types=1);

/**
 * This file is part of the PHPSubreg package
 *
 * https://github.com/Spoje-NET/php-subreg
 *
 * (c) Vítězslav Dvořák <http://spojenet.cz/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Test\Subreg;

use PHPUnit\Framework\TestCase;
use Subreg\ApiException;
use Subreg\Client;

/**
 * Offline unit tests for helpers derived from production Subreg incidents.
 */
class ClientHelpersTest extends TestCase
{
    public function testFormatNsHosts(): void
    {
        $this->assertSame(
            [
                ['hostname' => 'ns.gransy.com'],
                ['hostname' => 'ns2.gransy.com'],
            ],
            Client::formatNsHosts(['ns.gransy.com.', 'ns2.gransy.com']),
        );
    }

    public function testHostsIndicateParking(): void
    {
        $this->assertTrue(Client::hostsIndicateParking([
            'ns.parktons.com',
            'ns2.parktons.com',
        ]));
        $this->assertTrue(Client::hostsIndicateParking([
            ['hostname' => 'ns.parktons.com.'],
        ]));
        $this->assertFalse(Client::hostsIndicateParking(Client::GRANSY_NS));
        $this->assertFalse(Client::hostsIndicateParking([]));
    }

    public function testIsCreditAboveThreshold(): void
    {
        $this->assertFalse(Client::isCreditAboveThreshold([
            'credit' => [
                'amount' => '260.00',
                'threshold' => '300.00',
            ],
        ]));
        $this->assertTrue(Client::isCreditAboveThreshold([
            'amount' => '300.00',
            'threshold' => '300.00',
        ]));
        $this->assertTrue(Client::isCreditAboveThreshold([
            'credit' => [
                'amount' => '500.00',
                'threshold' => '300.00',
            ],
        ]));
    }

    public function testApiExceptionPreservesCodes(): void
    {
        $ex = new ApiException([
            'errormsg' => 'Parameter curExpDate is not equal to exDate.',
            'errorcode' => [
                'major' => '500',
                'minor' => '107',
            ],
        ]);

        $this->assertSame(500, $ex->major);
        $this->assertSame(107, $ex->minor);
        $this->assertSame('Parameter curExpDate is not equal to exDate.', $ex->getMessage());
        $this->assertSame([
            'errormsg' => 'Parameter curExpDate is not equal to exDate.',
            'errorcode' => [
                'major' => 500,
                'minor' => 107,
            ],
        ], $ex->toErrorArray());
    }

    public function testGransyNsConstant(): void
    {
        $this->assertCount(5, Client::GRANSY_NS);
        $this->assertSame('ns.gransy.com', Client::GRANSY_NS[0]);
    }
}
