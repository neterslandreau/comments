<?php
namespace Comments\Test\TestCase\Model\Behavior;

use Cake\TestSuite\TestCase;
use Comments\Model\Behavior\CommentableBehavior;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;
use Comments\Model\Entity\Comment;
use Comments\Model\Table\CommentsTable;

if (!class_exists('Articles')) {
    class ArticlesTable extends Table
    {
        /**
         * Callback data
         *
         * @var array
         */
        public $callbackData = [];

        /**
         * @param array $options
         */
        public function initialize(array $options)
        {
            $this->addBehavior('Comments.Commentable', [
                'commentsModel' => 'Comments.Comments',
                'userModelAlias' => 'UsersTable',
                'userModel' => 'Users'
            ]);

            $this->table('articles');
        }
    }
}

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
//        $this->Comments = TableRegistry::get('Comments.Comments');
        $this->CommentableBehavior = new CommentableBehavior(TableRegistry::get('Comments.Comments'));
//        debug($this->CommentableBehavior->config('commentsTable'));
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->CommentableBehavior);
        unset($this->Comments);
        parent::tearDown();
    }

    /**
     * Test commentToggleApprove method
     *
     * @return void
     */
    public function testCommentToggleApprove()
    {
        debug($this->CommentableBehavior->config());
//        $commentId = '00000000-0000-0000-0000-000000000001';
//        $foreignKey = $this->Comments->get($commentId)->foreignKey;
//        $assocTable = TableRegistry::get($this->Comments->get($commentId)->model);
//        $assocData = $assocTable->get($foreignKey);
//        $origCnt = $assocData->comments;
//        $rtn = $this->Comments->commentToggleApprove($this->Comments, $commentId);
//        $this->assertTrue($rtn);
//
//        $assocTable = TableRegistry::get($this->Comments->get($commentId)->model);
//        $assocData = $assocTable->get($foreignKey);
//        $newCnt = $assocData->comments;
//        $this->assertEquals($newCnt, $origCnt + 1);
//
//        $rtn = $this->Comments->commentToggleApprove($this->Comments, $commentId);
//        $assocTable = TableRegistry::get($this->Comments->get($commentId)->model);
//        $assocData = $assocTable->get($foreignKey);
//        $thrdCnt = $assocData->comments;
//        $this->assertTrue($rtn);
//        $this->assertEquals($thrdCnt, $newCnt - 1);

    }

    /**
     * Test commentDelete method
     *
     * @return void
     *
    public function testCommentDelete()
    {
        $commentId = '00000000-0000-0000-0000-000000000001';

        $cnt = count($this->Comments->find()->toArray());
        $rtn = $this->Comments->commentDelete($this->Comments, $commentId);
        $this->assertTrue($rtn);
        $ncnt = count($this->Comments->find()->toArray());
        $this->assertEquals($ncnt, $cnt - 1);

    }

    /**
     * Test commentAdd method
     *
     * @return void
     *
    public function testCommentAdd()
    {
        $id = '00000000-0000-0000-0000-000000000001';
        $comment = $this->Comments->get($id, ['contain' => ['Users']]);
        debug($comment->foreignKey);
        $options = [
            'permalink' => $comment->permalink,
            'modelId' => $comment->foreignKey

        ];

//        $rtn = $this->Comments->commentAdd($comment, $comment->id, $options);

        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test commentBeforeFind method
     *
     * @return void
     *
    public function testCommentBeforeFind()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
    /* */
}
