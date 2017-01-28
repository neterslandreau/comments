<?php
namespace Comments\Test\TestCase\Model\Behavior;

use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;
use Comments\Model\Entity\Comment;


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
     *
     */
    public function testProcessDeleteCommentOnly()
    {
        $action = 'deleteCommentOnly';
        $items = [
            '00000000-0000-0000-0000-000000000016' => '1',
            '00000000-0000-0000-0000-000000000018' => '0',
            '00000000-0000-0000-0000-000000000019' => '1',
        ];
        $commentsStart = $this->Comments->find()->count();
        $results = $this->Comments->process($action, $items);
        $commentsEnd = $this->Comments->find()->count();

        $this->assertEquals('success', $results['type']);
        $count = 0;
        foreach ($items as $item => $act) {
            if (!$act) {
                $comment = $this->Comments->get($item);
                $this->assertInstanceOf('Comments\Model\Entity\Comment', $comment);
            } else {
                $count++;
            }
        }
        $this->assertEquals($commentsStart - $commentsEnd, $count);

    }

    /* */
}
