<?php
namespace Comments\Model\Behavior;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
//use Comments\Model\Table\CommentsTable;
use Cake\ORM\Query;
use Cake\Utility\Inflector;

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
     * @var null
     *
    public $table = null;

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'commentsTable' => 'Comments.Comments',
        'spamField' => 'is_spam',
        'usersTableAlias' => 'UsersTable',
        'usersTableClass' => 'Users',
        'usersTable' => null,
    ];

    public function initialize(array $config)
    {
        parent::initialize($config);
//        debug($this);
    }

    /**
     * Toggle approved field in model record and increment or decrement the associated
     * models comment count appopriately.
     *
     * @param Table $Table
     * @param string $commentId
     * @param array   $options
     * @return boolean
     */
    public function commentToggleApprove(Table $Table, $commentId, $options = array())
    {
        $data = $Table->get($commentId);
        if ($data) {
            if ($data->approved == true) {
                $data->approved = 0;
                $direction = 'down';
            } elseif ($data->approved == false) {
                $data->approved = 1;
                $direction = 'up';
            }

            if ($Table->save($data)) {
                $assocTable = TableRegistry::get($data->model);
                $this->changeCommentCount($assocTable, $data->foreignKey, $direction);
                return true;
            }
        }
        return false;
    }

    /**
     * Increment or decrement the comment count cache on the associated model
     *
     * @param Table $table Associated table to change count of
     * @param mixed  $id The id to change count of
     * @param string $direction 'up' or 'down'
     * @return null
     */
    public function changeCommentCount(Table $Table, $id = null, $direction = 'up') {
        $data = $Table->get($id);
        $cnt = $data->comments;
        if ($Table->hasField('comments')) {
            if ($direction == 'up') {
                $cnt++;
            } elseif ($direction == 'down') {
                $cnt--;
            }
            $Table->id = $id;
            if (!is_null($direction) && $Table->exists([true])) {
                return $Table->updateAll(
                    [$Table->alias() . '.comments' => $cnt],
                    [$Table->alias() . '.id' => $id]
                );
            }
        }
        return false;
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
        $entity = $table->get($commentId);
        return $table->delete($entity);
    }

    /**
     * Handle adding comments
     *
     * @param Table $Table Object of the related model class
     * @param mixed $commentId parent comment id, 0 for none
     * @param array $options extra information and comment statistics
     * @throws BlackHoleException
     * @return boolean
     */
    public function commentAdd(Table $Table, $commentId = null, $options = array())
    {
        $options = array_merge(
            [
                'defaultTitle' => '',
                'modelId' => null,
                'userId' => null,
                'data' => array(),
                'permalink' => ''
            ],
            (array)$options
        );

        extract($options);
        if (isset($options['permalink'])) {
            $Table->permalink = $options['permalink'];
        }
        if (!empty($commentId)) {
            $Table->id = $commentId;
            if (!$Table->get('count', [
                'conditions' => [
                    'Comments.id' => $commentId,
                    'Comments.approved' => true,
                    'Comments.foreign_key' => $modelId
                ]
            ])) {
                throw new BlackHoleException(__d('comments', 'Unallowed comment id', true));
            }
        }
        if (!empty($data)) {
            $data['Comment']['user_id'] = $userId;
            $data['Comment']['model'] = $modelName;
            if (!isset($data['Comment']['foreign_key'])) {
                $data['Comment']['foreign_key'] = $modelId;
            }
            if (!isset($data['Comment']['parent_id'])) {
                $data['Comment']['parent_id'] = $commentId;
            }
            if (empty($data['Comment']['title'])) {
                $data['Comment']['title'] = $defaultTitle;
            }
            if (!empty($data['Other'])) {
                foreach ($data['Other'] as $spam) {
                    if (!empty($spam)) {
                        return false;
                    }
                }
            }
            $event = new Event('Behavior.Commentable.beforeCreateComment', $entity, $data);
            EventManager::instance()->dispatch($event);
            if ($event->isStopped() && !$event->result) {
                return false;
            } elseif ($event->result) {
                $data = $event->result;
            }
            $entity->create($data);
            if ($Model->Comment->Behaviors->enabled('Tree')) {
                if (isset($data['Comment']['foreign_key'])) {
                    $fk = $data['Comment']['foreign_key'];
                } elseif (isset($data['foreign_key'])) {
                    $fk = $data['foreign_key'];
                } else {
                    $fk = null;
                }
                $Model->Comment->Behaviors->load('Tree', array(
                        'scope' => array('Comment.foreign_key' => $fk))
                );
            }
            if ($entity->save()) {
                $id = $entity->id;
                $data['Comment']['id'] = $id;
                $entity->data[$entity->alias]['id'] = $id;
                if (!isset($data['Comment']['approved']) || $data['Comment']['approved'] == true) {
                    $this->changeCommentCount($entity, $modelId);
                }
                $event = new Event('Behavior.Commentable.afterCreateComment', $entity, $entity->data);
                EventManager::instance()->dispatch($event);
                if ($event->isStopped() && !$event->result) {
                    return false;
                }
                return $id;
            } else {
                return false;
            }
        }
        return null;
    }

    /**
     * Prepare models association to before fetch data
     *
     * @param Event $event
     * @param EntityInterface $entity
     * @return boolean
     */
    public function commentBeforeFind(Event $event, EntityInterface $entity)
    {
        return [];
    }
}
