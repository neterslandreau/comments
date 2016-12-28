<?php
namespace Comments\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

class BlackHoleException extends \Exception {}
class NoActionException extends \Exception {}
/**
 * CommentableBehavior behavior
 */
class CommentableBehavior extends Behavior
{

    /**
     * Config array
     *
     * @var array
     */
    public $config = [];
    /**
     * Default settings
     *
     * @var array
     */
    public $defaults = array(
        'commentTable' => 'Comments.Comments',
        'spamField' => 'is_spam',
        'userModelAlias' => 'UserModel',
        'userModelClass' => 'User',
        'userModel' => null,
    );

    /**
     * @var null
     *
    public $table = null;

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function initialize(array $config)
    {
        parent::initialize($config);
        $table = TableRegistry::get('Comments.Comments');
        debug($table);
//        if (!isset($this->config[$table->alias])) {
//            $this->config[$table->alias] = $this->defaults;
//        }
//
//        if (!is_array($config)) {
//            $config = (array)$config;
//        }
//        $this->config[$table->alias] = array_merge($this->config[$table->alias], $config);
//        $this->bindCommentsModels($table);
    }

    /**
     * Binds the comment and user table and the current table to the comments table
     *
     * @param Table $table
     * @return void
     */
    public function bindCommentsModels(Table $table)
    {

    }

    /**
     * Toggle approved field in model record and increment or decrement the associated
     * models comment count appopriately.
     *
     * @param Table $table
     * @param string $commentId
     * @param array   $options
     * @return boolean
     */
    public function commentToggleApprove(Table $table, $commentId, $options = array())
    {

    }

    /**
     * Delete comment
     *
     * @param Table $table
     * @param string $commentId
     * @return boolean
     */
    public function commentDelete(Table $table, $commentId = null)
    {

    }

    /**
     * Handle adding comments
     *
     * @param Table $table     Object of the related model class
     * @param mixed $commentId parent comment id, 0 for none
     * @param array $options   extra information and comment statistics
     * @throws BlackHoleException
     * @return boolean
     */
    public function commentAdd(Table $table, $commentId = null, $options = array())
    {

    }
    /**
     * Prepare models association to before fetch data
     *
     * @param Table $table
     * @param array $options
     * @return boolean
     */
    public function commentBeforeFind(Model $model, $options)
    {

    }
}
