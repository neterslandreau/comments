<?php
namespace Comments\Test\TestCase\Model\Behavior;

use Cake\TestSuite\TestCase;
use Comments\Model\Behavior\CommentableBehavior;
use Comments\Model\Table\CommentTable;

/**
 * Comments\Model\Behavior\CommentableBehavior Test Case
 */
class CommentableBehaviorTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Comments\Model\Behavior\CommentableBehavior
     */
    public $CommentableBehavior = null;
    public $CommentTable;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->CommentTable = new CommentTable();
        $this->config = [];
        $this->CommentableBehavior = new CommentableBehavior($this->CommentTable, $this->config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CommentableBehavior);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        debug($this->CommentableBehavior);
        $this->markTestIncomplete('Not implemented yet.');
    }
    /* */
}
