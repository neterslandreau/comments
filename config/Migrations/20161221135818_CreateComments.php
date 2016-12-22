<?php
use Migrations\AbstractMigration;

class CreateComments extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     * @return void
     */
    public function change()
    {
        $table = $this->table('comments', ['id' => false, 'primary_key' => ['id']]);
        $table->addColumn('id', 'uuid');
        $table->addColumn('parent_id', 'uuid');
        $table->addColumn('foreignKey', 'uuid');
        $table->addColumn('user_id', 'uuid');
        $table->addColumn('lft', 'integer', [
            'default' => null,
            'length' => 10
        ]);
        $table->addColumn('rght', 'integer', [
            'default' => null,
            'length' => 10
        ]);
        $table->addColumn('model', 'string', [
            'null' => false,
            'default' => null
        ]);
        $table->addColumn('approved', 'boolean', [
            'null' => false,
            'default' => true
        ]);
        $table->addColumn('title', 'string', ['null' => true, 'default' => null]);
        $table->addColumn('slug', 'string', ['null' => true, 'default' => null]);
        $table->addColumn('body', 'text', ['null' => true, 'default' => null]);
        $table->addColumn('author_name', 'string', ['null' => true, 'default' => null]);
        $table->addColumn('author_url', 'string', ['null' => true, 'default' => null]);
        $table->addColumn('author_email', 'string', [
            'null' => true,
            'default' => '',
            'length' => 128
        ]);
        $table->addColumn('language', 'string', ['null' => true, 'default' => null, 'length' => 6]);
        $table->addColumn('is_spam', 'string', ['length' => 20, 'default' => 'clean', 'null' => false]);
        $table->addColumn('comment_type', 'string', ['length' => 32, 'default' => 'comment', 'null' => false]);
        $table->addColumn('created', 'datetime', [
            'default' => null,
            'null' => false,
        ]);
        $table->addColumn('modified', 'datetime', [
            'default' => null,
            'null' => false,
        ]);

        $table->create();
    }
}
