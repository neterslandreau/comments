<?php
namespace Comments\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use Comments\View\Helper\TreeHelper;

/**
 * Comments\View\Helper\TreeHelper Test Case
 */
class TreeHelperTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Comments\View\Helper\TreeHelper
     */
    public $Tree;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $view = new View();
        $this->Tree = new TreeHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Tree);

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
