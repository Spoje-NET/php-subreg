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

/**
 * Generated by PHPUnit_SkeletonGenerator on 2018-01-16 at 13:39:38.
 */
class ClientTest extends \Test\Ease\MoleculeTest
{
    protected Client $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        $this->object = new \Subreg\Client(\Ease\Shared::instanced()->configuration);
        $this->object->login();
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }

    /**
     * Test Constructor.
     *
     * @depends testLogBanner
     *
     * @covers \Subreg\Client::__construct
     */
    public function testConstructor(): void
    {
        $classname = \get_class($this->object);
        // Get mock, without the constructor being called
        $mock = $this->getMockBuilder($classname)
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();
        $mock->__construct(\Ease\Shared::instanced()->configuration);
        $this->assertArrayHasKey('login', $this->object->config);
        $this->assertInstanceOf('\SoapClient', $this->object->soaper);
    }

    /**
     * @covers \Subreg\Client::logBanner
     */
    public function testLogBanner(): void
    {
        $this->assertTrue($this->object->logBanner(addslashes(\get_class($this))));
    }

    /**
     * @covers \Subreg\Client::call
     */
    public function testCall(): void
    {
        $fail = $this->object->call('NonExist');
        $this->assertEquals(['error' => [
            'errormsg' => 'Invalid method called',
            'errorcode' => [
                'major' => '500',
                'minor' => '107',
            ],
        ],
        ], $fail);
        $success = $this->object->call('Get_Credit');
        $this->arrayHasKey(\array_key_exists('credit', $success));
    }

    /**
     * @covers \Subreg\Client::logError
     */
    public function testLogError(): void
    {
        $error = ['errorcode' => ['major' => 999, 'minor' => 000],
            'errormsg' => 'some error message'];
        $this->object->logError($error);
        $this->assertEquals($error, $this->object->lastError);
    }

    /**
     * @covers \Subreg\Client::login
     */
    public function testLogin(): void
    {
        $this->assertTrue($this->object->login());
        $this->assertNotEmpty($this->object->token);
    }

    /**
     * @covers \Subreg\Client::checkDomain
     */
    public function testCheckDomain(): void
    {
        $this->assertIsArray($this->object->checkDomain('php-subreg.cz'));
    }

    /**
     * @covers \Subreg\Client::domainsList
     */
    public function testDomainsList(): void
    {
        $domainlist = $this->object->domainsList();
        $this->assertTrue(\array_key_exists('domains', $domainlist) && \array_key_exists(
            'count',
            $domainlist,
        ));
    }

    /**
     * @covers \Subreg\Client::pricelist
     */
    public function testPricelist(): void
    {
        $pricelist = $this->object->pricelist();
        $this->assertArrayHasKey('cz', $pricelist);
    }

    /**
     * @covers \Subreg\Client::getPricelist
     */
    public function testGetPricelist(): void
    {
        $pricelist = $this->object->getPricelist('MYPRICELIST');
        $this->assertArrayHasKey('error', $pricelist);
    }

    /**
     * @covers \Subreg\Client::registerDomain
     */
    public function testRegisterDomain(): void
    {
        $unexistentDomain = strtolower(\Ease\Functions::randomString()).'.cz';

        $nsHosts = ['ns.spoje.net', 'ns2.spoje.net'];

        $result = $this->object->registerDomain(
            $unexistentDomain,
            'G-000001',
            'G-000001',
            'G-000001',
            'ukulele',
            $nsHosts,
        );

        $this->assertArrayHasKey('orderid', $result);
    }

    /**
     * @covers \Subreg\Client::renewDomain
     */
    public function testRenewDomain(): void
    {
        $this->assertIsArray($this->object->renewDomain('php-subreg.cz', 1));
    }

    /**
     * @covers \Subreg\Client::creditCorrection
     */
    public function testcreditCorrection(): void
    {
        $this->assertIsArray($this->object->creditCorrection('php-subreg', '+1', 'PHPUnit Test'));
    }

    /**
     * @covers \Subreg\Client::infoUser
     */
    public function testinfoUser(): void
    {
        $userInfo = $this->object->infoUser(1); // Not Exists
        $this->assertArrayHasKey('error', $userInfo);
    }
}
