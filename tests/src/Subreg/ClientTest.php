<?php

namespace Test\Subreg;

/**
 * Generated by PHPUnit_SkeletonGenerator on 2018-01-16 at 13:39:38.
 */
class ClientTest extends \Test\Ease\MoleculeTest
{
    /**
     * @var Client
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new \Subreg\Client(\Ease\Shared::instanced()->configuration);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        
    }

    /**
     * @covers Subreg\Client::call
     */
    public function testCall()
    {
        $this->object->call('Get_Credit');
    }

    /**
     * @covers Subreg\Client::logError
     */
    public function testLogError()
    {
        $this->object->logError(['errorcode' => ['major' => 999, 'minor' => 000],
            'errormsg' => 'some error message']);
    }

    /**
     * @covers Subreg\Client::login
     */
    public function testLogin()
    {
        $this->object->login();
    }
}
