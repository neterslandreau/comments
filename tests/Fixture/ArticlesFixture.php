<?php
namespace Comments\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ArticlesFixture
 *
 */
class ArticlesFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => 0, '0' => false, 'default' => 0, 'comment' => '', 'precision' => 0],
        'user_id' => ['type' => 'uuid', 'length' => 0, '0' => false, 'default' => 0, 'comment' => '', 'precision' => 0],
        'category_id' => ['type' => 'uuid', 'length' => 0, '0' => false, 'default' => 0, 'comment' => '', 'precision' => 0],
        'title' => ['type' => 'string', 'length' => 255, '0' => false, 'default' => 0, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => 0, 'fixed' => 0],
        'body' => ['type' => 'text', 'length' => null, 'null' => false, 'default' => null, 'collate' => 'utf8_general_ci', 'comment' => '', 'precision' => null],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'comments' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, '0' => true, 'default' => 0, 'comment' => '', 'precision' => 0, 'autoIncrement' => 0],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => '00000000-0000-0000-0000-000000000001',
            'user_id' => '00000000-0000-0000-0000-000000000001',
            'category_id' => '51d38a39-662a-4506-ab27-df330d7c2ad0',
            'title' => 'Always needed',
            'body' => 'Information is always needed.',
            'created' => '2017-01-24 22:08:31',
            'modified' => '2017-01-24 22:08:31',
            'comments' => 0
        ],
        [
            'id' => '00000000-0000-0000-0000-000000000002',
            'user_id' => '00000000-0000-0000-0000-000000000001',
            'category_id' => '51d38a39-662a-4506-ab27-df330d7c2ad0',
            'title' => 'Not Bees',
            'body' => 'This is not about bees!',
            'created' => '2017-01-18 22:24:53',
            'modified' => '2017-01-18 22:24:53',
            'comments' => 0
        ],
        [
            'id' => '00000000-0000-0000-0000-000000000003',
            'user_id' => '00000000-0000-0000-0000-000000000001',
            'category_id' => 'c554e002-c403-46b1-8066-9097af1780cc',
            'title' => 'Bees again',
            'body' => 'Bees are worthless.',
            'created' => '2017-01-25 01:16:53',
            'modified' => '2017-01-25 01:16:53',
            'comments' => 0
        ],
        [
            'id' => '00000000-0000-0000-0000-000000000004',
            'user_id' => '00000000-0000-0000-0000-000000000001',
            'category_id' => '51d38a39-662a-4506-ab27-df330d7c2ad0',
            'title' => 'RBAC woes',
            'body' => 'RBAC can be bothersome unless you created the methods.',
            'created' => '2017-01-24 22:21:54',
            'modified' => '2017-01-24 22:21:54',
            'comments' => 0
        ],
        [
            'id' => '00000000-0000-0000-0000-000000000005',
            'user_id' => '00000000-0000-0000-0000-000000000001',
            'category_id' => '27f1e929-08d9-41db-8f22-e67fc516003e',
            'title' => 'more birds',
            'body' => 'this ia all about birds and more birds',
            'created' => '2017-01-25 01:01:22',
            'modified' => '2017-01-25 01:01:22',
            'comments' => 0
        ],
        [
            'id' => '00000000-0000-0000-0000-000000000006',
            'user_id' => '00000000-0000-0000-0000-000000000001',
            'category_id' => '27f1e929-08d9-41db-8f22-e67fc516003e',
            'title' => 'birds are for the birds',
            'body' => 'like i said..',
            'created' => '2017-01-24 17:15:31',
            'modified' => '2017-01-24 17:15:31',
            'comments' => 0
        ],
        [
            'id' => '00000000-0000-0000-0000-000000000007',
            'user_id' => '00000000-0000-0000-0000-000000000001',
            'category_id' => 'c554e002-c403-46b1-8066-9097af1780cc',
            'title' => 'Necessary for life',
            'body' => 'Bees are required to sustain life.',
            'created' => '2017-01-24 22:07:41',
            'modified' => '2017-01-24 22:07:41',
            'comments' => 0
        ],
        [
            'id' => '00000000-0000-0000-0000-000000000008',
            'user_id' => '00000000-0000-0000-0000-000000000001',
            'category_id' => '27f1e929-08d9-41db-8f22-e67fc516003e',
            'title' => 'new article',
            'body' => 'this is also owned by admin!',
            'created' => '2017-01-24 17:28:31',
            'modified' => '2017-01-24 17:28:31',
            'comments' => 0
        ],
        [
            'id' => '00000000-0000-0000-0000-000000000009',
            'user_id' => '00000000-0000-0000-0000-000000000001',
            'category_id' => '51d38a39-662a-4506-ab27-df330d7c2ad0',
            'title' => 'General nonsense',
            'body' => 'this is to check the counter cache just added to categories.',
            'created' => '2017-01-25 01:16:14',
            'modified' => '2017-01-25 01:16:14',
            'comments' => 0
        ],
    ];
}
