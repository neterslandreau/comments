<?php
namespace Comments\Test\TestCase\Model\Behavior;

use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;


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
        $this->Comments = TableRegistry::get('Comments.Comments');
        $this->Comments->addBehavior('Comments.Commentable', [
            'userModelAlias' => 'Users',
            'userModelClass' => 'Users.Users',
            'modelName' => 'Comments.Comments'
        ]);

    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    /**
     *
     */
    public function testProcessHam()
    {
        $action = 'ham';
        $items = [
            '00000000-0000-0000-0000-000000000001' => '1',
            '00000000-0000-0000-0000-000000000002' => '1',
            '00000000-0000-0000-0000-000000000003' => '1',
        ];
        $results = $this->Comments->process($action, $items);
        $this->assertEquals('success', $results['type']);
        $count = 0;
        foreach ($items as $item => $act) {
            $comment = $this->Comments->get($item);
            if ($act) {
                $count++;
                $this->assertEquals($comment->is_spam, $action);
            }
        }
        $this->assertEquals($results['count'], $count);
    }

    /**
     *
     */
    public function testProcessSpam()
    {
        $action = 'spam';
        $items = [
            '00000000-0000-0000-0000-000000000001' => '1',
            '00000000-0000-0000-0000-000000000002' => '0',
            '00000000-0000-0000-0000-000000000003' => '1',
        ];
        $results = $this->Comments->process($action, $items);
        $this->assertEquals('success', $results['type']);
        $count = 0;
        foreach ($items as $item => $act) {
            $comment = $this->Comments->get($item);
            if ($act) {
                $count++;
                $this->assertEquals($comment->is_spam, $action);
            }
        }
        $this->assertEquals($results['count'], $count);
    }

    /**
     *
     */
    public function testProcessApprove()
    {
        $action = 'approve';
        $items = [
            '00000000-0000-0000-0000-000000000001' => '1',
            '00000000-0000-0000-0000-000000000002' => '0',
            '00000000-0000-0000-0000-000000000003' => '1',
        ];
        $results = $this->Comments->process($action, $items);

        $this->assertEquals('success', $results['type']);
        $count = 0;
        foreach ($items as $item => $act) {
            $comment = $this->Comments->get($item);
            if ($act == 1) {
                $count++;
                $this->assertEquals($comment->approved, true);
            }
        }
        $this->assertEquals($results['count'], $count);
    }

    /**
     *
     */
    public function testProcessDisapprove()
    {
        $action = 'disapprove';
        $items = [
            '00000000-0000-0000-0000-000000000004' => '1',
            '00000000-0000-0000-0000-000000000005' => '0',
            '00000000-0000-0000-0000-000000000006' => '1',
        ];
        $results = $this->Comments->process($action, $items);

        $this->assertEquals('success', $results['type']);
        $count = 0;
        foreach ($items as $item => $act) {
            $comment = $this->Comments->get($item);
            if ($act == 1) {
                $count++;
                $this->assertEquals($comment->approved, false);
            }
        }
        $this->assertEquals($results['count'], $count);
    }
    /**
     * Test commentToggleApprove method
     *
     * @return void
     *
    public function testCommentToggleApprove()
    {
//        debug($this->CommentableBehavior->config());
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
