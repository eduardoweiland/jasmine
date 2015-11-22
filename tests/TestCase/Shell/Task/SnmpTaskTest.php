<?php
namespace App\Test\TestCase\Shell;

use App\Shell\Task\SnmpTask;
use Cake\TestSuite\TestCase;

/**
 * App\Shell\Task/SnmpTaskShell Test Case
 */
class SnmpTaskTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->io = $this->getMock('Cake\Console\ConsoleIo');
        $this->Snmp = new SnmpTask($this->io);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Snmp);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
