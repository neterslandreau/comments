<?php
namespace Comments\Test\TestCase\Model\Behavior;

use Cake\TestSuite\TestCase;
use Comments\Model\Behavior\CommentableBehavior;
use Cake\ORM\TableRegistry;
//use Comments\Model\Entity\Comment;
//use Comments\Model\Model;

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
    public $CommentableBehavior;

    public $fixtures = [
        'plugin.Comments.Comments',
        'plugin.Comments.Users',
        'plugin.Comments.Articles'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $this->commentsTable = TableRegistry::get('Comments.Comments');
//        var_dump($this->commentsTable);
        $this->CommentableBehavior = new CommentableBehavior($this->commentsTable);
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
     * Test commentToggleApprove method
     *
     * @return void
     */
    public function testCommentToggleApprove()
    {
        $commentId = '00000000-0000-0000-0000-000000000001';
        $table = TableRegistry::get('Comments.Comments');
//        die(var_dump($table));
//        $comment = $this->commentsTable->get($commentId);
//        var_dump($comment);
        $rtn = $table->commentToggleApprove($table, $commentId);

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
//        $id = '00000000-0000-0000-0000-000000000001';
//        $comment = $this->Comments->get($id, ['contain' => ['Users']]);
//        debug($comment);

//        $rtn = $this->Comments->commentAdd($comment);

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
