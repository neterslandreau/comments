<?php
namespace Comments\Test\TestCase\Model\Behavior;

use Cake\TestSuite\TestCase;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Comments\Model\Behavior\CommentableBehavior;

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
    public $Commentable;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'plugin.comments.comments',
        'plugin.comments.users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $table = TableRegistry::get('Comments.Comments');
//        $table->addBehavior
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->Commentable);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
//        $this->markTestIncomplete('Not implemented yet.');
//        debug($this->Commentable);
    }

    /**
     * Test bindCommentsModels method
     *
     * @return void
     */
    public function testBindCommentsModels()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test commentToggleApprove method
     *
     * @return void
     */
    public function testCommentToggleApprove()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test commentDelete method
     *
     * @return void
     */
    public function testCommentDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test commentAdd method
     *
     * @return void
     */
    public function testCommentAdd()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test commentBeforeFind method
     *
     * @return void
     */
    public function testCommentBeforeFind()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
