<?php
use Migrations\AbstractSeed;

/**
 * Comments seed.
 */
class CommentsSeed extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => '1bcbf30e-cfc9-44e8-b955-05db4d734e43',
                'parent_id' => '5e4c1127-3c5a-4e6e-9956-95e765201adc',
                'foreignKey' => '6e68cecc-b9a9-463c-bcf5-83fae4b73847',
                'user_id' => '6e68cecc-b9a9-463c-bcf5-83fae4b',
                'lft' => 1,
                'rght' => 1,
                'model' => 'Article',
                'approved' => 1,
                'title' => 'Comment 1',
                'slug' => 'comment_1',
                'body' => 'This is a comment.',
                'author_name' => 'Neters Landreau',
                'author_url' => 'http://think-knot.com',
                'author_email' => 'neterslandreau@gmail.com',
                'language' => '',
                'is_spam' => 'clean',
                'comment_type' => 'comment',
                'created' => '2016-12-22 12:00:00',
                'modified' => '2016-12-22 12:00:00',

            ],
            [
                'id' => '27629a1d-a8a9-42be-b391-afddc4b35510',
                'parent_id' => '5e4c1127-3c5a-4e6e-9956-95e765201adc',
                'foreignKey' => '6e68cecc-b9a9-463c-bcf5-83fae4b73847',
                'user_id' => '6e68cecc-b9a9-463c-bcf5-83fae4b',
                'lft' => 1,
                'rght' => 1,
                'model' => 'Article',
                'approved' => 1,
                'title' => 'Comment 1',
                'slug' => 'comment_1',
                'body' => 'This is a comment.',
                'author_name' => 'Neters Landreau',
                'author_url' => 'http://think-knot.com',
                'author_email' => 'neterslandreau@gmail.com',
                'language' => '',
                'is_spam' => 'clean',
                'comment_type' => 'comment',
                'created' => '2016-12-22 12:00:00',
                'modified' => '2016-12-22 12:00:00',
            ],
            [
                'id' => '946f7ed1-e8f8-41d1-8bfd-777635d779a0',
                'parent_id' => '5e4c1127-3c5a-4e6e-9956-95e765201adc',
                'foreignKey' => '6e68cecc-b9a9-463c-bcf5-83fae4b73847',
                'user_id' => '6e68cecc-b9a9-463c-bcf5-83fae4b',
                'lft' => 1,
                'rght' => 1,
                'model' => 'Article',
                'approved' => 1,
                'title' => 'Comment 1',
                'slug' => 'comment_1',
                'body' => 'This is a comment.',
                'author_name' => 'Neters Landreau',
                'author_url' => 'http://think-knot.com',
                'author_email' => 'neterslandreau@gmail.com',
                'language' => '',
                'is_spam' => 'clean',
                'comment_type' => 'comment',
                'created' => '2016-12-22 12:00:00',
                'modified' => '2016-12-22 12:00:00',
            ],
            [
                'id' => '4b603c34-ee8c-409e-a913-62ef9b9bae31',
                'parent_id' => '5e4c1127-3c5a-4e6e-9956-95e765201adc',
                'foreignKey' => '6e68cecc-b9a9-463c-bcf5-83fae4b73847',
                'user_id' => '6e68cecc-b9a9-463c-bcf5-83fae4b',
                'lft' => 1,
                'rght' => 1,
                'model' => 'Article',
                'approved' => 1,
                'title' => 'Comment 1',
                'slug' => 'comment_1',
                'body' => 'This is a comment.',
                'author_name' => 'Neters Landreau',
                'author_url' => 'http://think-knot.com',
                'author_email' => 'neterslandreau@gmail.com',
                'language' => '',
                'is_spam' => 'clean',
                'comment_type' => 'comment',
                'created' => '2016-12-22 12:00:00',
                'modified' => '2016-12-22 12:00:00',
            ],
            [
                'id' => 'f57f3d6c-62ab-4ac9-8801-879706a61e85',
                'parent_id' => '5e4c1127-3c5a-4e6e-9956-95e765201adc',
                'foreignKey' => '6e68cecc-b9a9-463c-bcf5-83fae4b73847',
                'user_id' => '6e68cecc-b9a9-463c-bcf5-83fae4b',
                'lft' => 1,
                'rght' => 1,
                'model' => 'Article',
                'approved' => 1,
                'title' => 'Comment 1',
                'slug' => 'comment_1',
                'body' => 'This is a comment.',
                'author_name' => 'Neters Landreau',
                'author_url' => 'http://think-knot.com',
                'author_email' => 'neterslandreau@gmail.com',
                'language' => '',
                'is_spam' => 'clean',
                'comment_type' => 'comment',
                'created' => '2016-12-22 12:00:00',
                'modified' => '2016-12-22 12:00:00',
            ],
        ];

        $table = $this->table('comments');
        $table->insert($data)->save();
    }
}
