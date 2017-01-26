<?php
namespace Comments\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Comments\Model\Entity\Comment;
/**
 * CommentableBehavior behavior
 */
class CommentableBehavior extends Behavior
{
    /**
     * Settings array
     *
     * @var array
     */
    public $settings = array();
    /**
     * Default settings
     *
     * @var array
     */
    public $defaults = array(
        'commentModel' => 'Comments.Comments',
        'spamField' => 'is_spam',
        'userModelAlias' => 'Users',
        'userModelClass' => 'Users',
        'userModel' => null,
    );

    /**
     * @var array
     */
    public $definedActions = [
        'clean',
        'ham',
        'spam',
        'delete',
        'approve',
        'disapprove',
    ];

    public $model = null;

    /**
     * Initialize behavior
     *
     * @param array $config
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $model = TableRegistry::get($config['modelName']);

        if (!isset($this->settings[$model->alias()])) {
            $this->settings[$model->alias()] = $this->defaults;
        }
        $this->settings[$model->alias()] = array_merge($this->settings[$model->alias()], $config);

        $this->model = $this->bindCommentModels($model);
    }

    /**
     * Binds the user model and the current model to the comments model
     *
     * @param Table $model
     * @return Table $model
     */
    public function bindCommentModels(Table $model)
    {
        $config = $this->settings[$model->alias()];
        if (!empty($config['commentModel']) && is_array($config['commentModel'])) {
            $model->hasMany('Comments', $config['commentModel']);
        } else {
            $model->hasMany('Comments', [
                'className' => $config['commentModel'],
                'foreignKey' => 'foreign_key',
                'unique' => true,
                'conditions' => true,
                'fields' => '',
                'dependent' => true,
                'order' => '',
                'limit' => '',
                'offset' => '',
                'exclusive' => '',
                'finderQuery' => '',
                'counterQuery' => ''

            ]);
        }
        $comments = TableRegistry::get($config['commentModel']);
        $comments->belongsTo($model->alias(), [
            'className' => $model->alias(),
            'foreignKey' => 'foreign_key',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'counterCache' => true,
            'dependent' => false
        ]);

        if (!empty($config['userModel']) && is_array($config['userModel'])) {
            $comments->belongsTo($config['userModelAlias'], $config['userModel']);
        } else {
            $comments->belongsTo($config['userModelAlias'], [
                'className' => $config['userModelClass'],
                'foreignKey' => 'user_id',
                'conditions' => '',
                'fields' => '',
                'counterCache' => true,
                'order' => '',
            ]);
        }
        return $model;
    }

    /**
     * Provide for bulk or individual comment processing.
     *
     * @param $action
     * @param array $items
     * @return array
     */
    public function process($action, array $items)
    {
        $treeBehavior = false;
        $message = ['type' => 'error', 'body' => '', 'count' => 0];
        if (!(in_array($action, $this->definedActions))) {
            $message = [
                'body' => 'This action is not defined.'
            ];
            return $message;
        }
        foreach ($items as $item => $act) {
            $act = (bool)($act);
            if ($act) {
                if ($this->model->hasBehavior('Tree')) {
                    $treeBehavior = true;
                    $this->model->removeBehavior('Tree');
                }
                if ($action != 'delete') {
                    $comment = $this->model->get($item);
                    if ($action === 'ham' || $action === 'spam' || $action === 'clean') {
                        $comment->is_spam = $action;
                        $r = $this->model->save($comment);
                        if ($r instanceof Comment) {
                            $message['count']++;
                            $message['type'] = 'success';
                        }
                    } elseif ($action === 'approve') {
                        $comment->approved = 1;
                        $r = $this->model->save($comment);
                        if ($r instanceof Comment) {
                            $message['count']++;
                            $message['type'] = 'success';
                        }
                    } elseif ($action === 'disapprove') {
                        $comment->approved = 0;
                        $r = $this->model->save($comment);
                        if ($r instanceof Comment) {
                            $message['count']++;
                            $message['type'] = 'success';
                        }
                    }
                } else {

                }
                if ($treeBehavior) {
                    $this->model->addBehavior('Tree');
                }
            }
        }
        return $message;
    }

    /**
     * Toggle approved field in model record and increment or decrement the associated
     * models comment count appopriately.
     *
     * @param Model $model
     * @param string $commentId
     * @param array $options
     * @return boolean
     */
    public function commentToggleApprove(Model $model, $commentId, $options = array())
    {
        $model->Comment->recursive = -1;
        $data = $model->Comment->read(null, $commentId);
        if ($data) {
            if ($data['Comment']['approved'] == 0) {
                $data['Comment']['approved'] = 1;
                $direction = 'up';
            } else {
                $data['Comment']['approved'] = 0;
                $direction = 'down';
            }
            if ($model->Comment->save($data, false, array('approved'))) {
                $this->changeCommentCount($model, $data['Comment']['foreign_key'], $direction);
                return true;
            }
        }
        return false;
    }

    /**
     * Delete comment
     *
     * @param null $commentId
     * @return mixed
     *
    public function commentDelete($commentId = null)
    {
        return $this->model->delete($commentId);
    }

    /**
     * Handle adding comments
     *
     * @param Model $Model Object of the related model class
     * @param mixed $commentId parent comment id, 0 for none
     * @param array $options extra information and comment statistics
     * @throws BlackHoleException
     * @return boolean
     */
    public function commentAdd(Model $Model, $commentId = null, $options = array())
    {
        $options = array_merge(array('defaultTitle' => '', 'modelId' => null, 'userId' => null, 'data' => array(), 'permalink' => ''), (array)$options);
        extract($options);
        if (isset($options['permalink'])) {
            $Model->Comment->permalink = $options['permalink'];
        }
        $Model->Comment->recursive = -1;
        if (!empty($commentId)) {
            $Model->Comment->id = $commentId;
            if (!$Model->Comment->find('count', array('conditions' => array(
                'Comment.id' => $commentId,
                'Comment.approved' => true,
                'Comment.foreign_key' => $modelId)))
            ) {
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
            $event = new CakeEvent('Behavior.Commentable.beforeCreateComment', $Model, $data);
            CakeEventManager::instance()->dispatch($event);
            if ($event->isStopped() && !$event->result) {
                return false;
            } elseif ($event->result) {
                $data = $event->result;
            }
            $Model->Comment->create($data);
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
            if ($Model->Comment->save()) {
                $id = $Model->Comment->id;
                $data['Comment']['id'] = $id;
                $Model->Comment->data[$Model->Comment->alias]['id'] = $id;
                if (!isset($data['Comment']['approved']) || $data['Comment']['approved'] == true) {
                    $this->changeCommentCount($Model, $modelId);
                }
                $event = new CakeEvent('Behavior.Commentable.afterCreateComment', $Model, $Model->Comment->data);
                CakeEventManager::instance()->dispatch($event);
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
     * Increment or decrement the comment count cache on the associated model
     *
     * @param Model $Model Model to change count of
     * @param mixed $id The id to change count of
     * @param string $direction 'up' or 'down'
     * @return null
     */
    public function changeCommentCount(Model $Model, $id = null, $direction = 'up')
    {
        if ($Model->hasField('comments')) {
            if ($direction == 'up') {
                $direction = '+ 1';
            } elseif ($direction == 'down') {
                $direction = '- 1';
            } else {
                $direction = null;
            }
            $Model->id = $id;
            if (!is_null($direction) && $Model->exists(true)) {
                return $Model->updateAll(
                    array($Model->alias . '.comments' => $Model->alias . '.comments ' . $direction),
                    array($Model->alias . '.id' => $id));
            }
        }
        return false;
    }

    /**
     * Prepare models association to before fetch data
     *
     * @param array $options
     * @return boolean
     */
    public function commentBeforeFind(array $options)
    {
        $options = array_merge(
            [
                'userModel' => $this->settings[$this->model->alias()]['userModelAlias'],
                'userData' => null,
                'isAdmin' => false
            ],
            (array)$options
        );
        extract($options);

        $unbind = array();
        foreach (array('belongsTo', 'hasOne', 'hasMany', 'hasAndBelongsToMany') as $assocType) {
            if ($this->commentsModel->association($assocType)) {
                $unbind[$assocType] = array();
                foreach ($this->commentsModel->association($assocType) as $key => $assocConfig) {
                    $keep = false;
                    if (!empty($options['keep']) && in_array($key, $options['keep'])) {
                        $keep = true;
                    }
                    if (!in_array($key, array($userModel, $this->model->alias())) && !$keep) {
                        $unbind[$assocType][] = $key;
                    }
                }
            }
        }

        if (!empty($unbind)) {
            // @todo check this in unit test. this is wrong!
            $this->commentsModel->associations->remove($unbind);
        }

        $this->commentsModel->addAssociations([
            'belongsTo' => [
                $this->model->alias() => [
                    'fields' => [$this->model->primaryKey()]
                ],
                $userModel => [
                    'fields' => ['id', '$this->commentsModel->{$userModel}->displayField()', 'slug']
                ]
            ]
        ]);

        $conditions = ['Comments.approved' => 1];
        if (isset($id)) {
            $conditions[$this->model->alias() . '.' . $this->model->primaryKey()] = $id;
            $conditions[$this->commentsModel->alias() . '.model'] = $this->commentsModel->alias();
        }
        if ($isAdmin) {
            unset($conditions['Comments.approved']);
        }

        $spamField = $this->settings[$this->model->alias()]['spamField'];
        if ($this->commentsModel->hasField($spamField)) {
            $conditions['Comments.' . $spamField] = array('clean', 'ham');
        }
//        debug($conditions);
        return $conditions;

    }
    /* */
}