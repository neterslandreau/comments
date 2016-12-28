<?php
namespace Comments\Test\TestCase\Model\Table;

use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Comments\Model\Table\CommentTable;
use CakeDC\Users\Test\Fixture\UsersFixture;

/**
 * Comments\Model\Table\CommentTable Test Case
 */
class CommentTableTest extends TestCase
{

    /**
     * Test subject
     *
     * @var \Comments\Model\Table\CommentTable
     */
    public $Comment;

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
        $config = TableRegistry::exists('Comments') ? [] : ['className' => 'Comments\Model\Table\CommentsTable'];
        $this->Comments = TableRegistry::get('Comments', $config);
        debug($this->Comments);
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
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
//        debug($this->Comment);
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
