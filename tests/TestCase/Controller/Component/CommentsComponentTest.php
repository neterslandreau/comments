<?php
namespace Comments\Test\TestCase\Controller\Component;

use Cake\Controller\ComponentRegistry;
use Cake\TestSuite\TestCase;
use Comments\Controller\Component\CommentsComponent;

/**
 * Comments\Controller\Component\CommentsComponent Test Case
 */
class CommentsComponentTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Comments\Controller\Component\CommentsComponent
     */
    public $Comments;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $registry = new ComponentRegistry();
        $this->Comments = new CommentsComponent($registry);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Comments);

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
