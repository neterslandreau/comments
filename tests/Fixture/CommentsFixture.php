<?php
namespace Comments\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * CommentFixture
 *
 */
class CommentsFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null],
        'parent_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null],
        'foreignKey' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null],
        'user_id' => ['type' => 'uuid', 'length' => null, 'null' => false, 'default' => null],
        'lft' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => 1],
        'rght' => ['type' => 'integer', 'length' => 10, 'unsigned' => false, 'null' => false, 'default' => 1],
        'model' => ['type' => 'string', 'length' => 255, 'null' => false, 'default' => null],
        'approved' => ['type' => 'boolean', 'length' => null, 'null' => false, 'default' => '1'],
        'title' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null],
        'slug' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null],
        'body' => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null],
        'author_name' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null],
        'author_url' => ['type' => 'string', 'length' => 255, 'null' => true, 'default' => null],
        'author_email' => ['type' => 'string', 'length' => 128, 'null' => true, 'default' => null],
        'language' => ['type' => 'string', 'length' => 6, 'null' => true, 'default' => null],
        'is_spam' => ['type' => 'string', 'length' => 20, 'null' => false, 'default' => 'clean'],
        'comment_type' => ['type' => 'string', 'length' => 32, 'null' => false, 'default' => 'comment'],
        'created' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null],
        'modified' => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null],
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
            'id' => '8e4f2b2c-20d9-4e0b-80f5-50bde2195e78',
            'parent_id' => '4c1342bd-1e4e-4f58-869e-48899ac32ce7',
            'foreignKey' => 'ae91bb91-1fdf-44bd-ab3f-72c55be7abee',
            'user_id' => '854ea630-0b63-4b5f-9461-6de74b7009b8',
            'lft' => 1,
            'rght' => 2,
            'model' => 'Lorem ipsum dolor sit amet',
            'approved' => 1,
            'title' => 'Lorem ipsum dolor sit amet',
            'slug' => 'Lorem ipsum dolor sit amet',
            'body' => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
            'author_name' => 'Lorem ipsum dolor sit amet',
            'author_url' => 'Lorem ipsum dolor sit amet',
            'author_email' => 'Lorem ipsum dolor sit amet',
            'language' => 'Lore',
            'is_spam' => 'Lorem ipsum dolor ',
            'comment_type' => 'Lorem ipsum dolor sit amet',
            'created' => '2016-12-21 14:32:14',
            'modified' => '2016-12-21 14:32:14'
        ],
    ];
     /* */
}
