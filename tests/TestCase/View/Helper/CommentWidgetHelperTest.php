<?php
namespace Comments\Test\TestCase\View\Helper;

use Cake\TestSuite\TestCase;
use Cake\View\View;
use Comments\View\Helper\CommentWidgetHelper;

/**
 * Comments\View\Helper\CommentWidgetHelper Test Case
 */
class CommentWidgetHelperTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Comments\View\Helper\CommentWidgetHelper
     */
    public $CommentWidget;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $view = new View();
        $this->CommentWidget = new CommentWidgetHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CommentWidget);

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
