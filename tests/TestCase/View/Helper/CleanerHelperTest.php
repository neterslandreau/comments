<?php
namespace Comments\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase as TestCase;
use Cake\View\View;
use Comments\View\Helper\CleanerHelper;

/**
 * Comments\View\Helper\CleanerHelper Test Case
 */
class CleanerHelperTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Comments\View\Helper\CleanerHelper
     */
    public $Cleaner;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $view = new View();
        $this->Cleaner = new CleanerHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Cleaner);

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
