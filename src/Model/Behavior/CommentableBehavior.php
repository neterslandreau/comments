<?php
namespace Comments\Model\Behavior;

use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\TableRegistry;
use Comments\Model\Entity\Comment;
use Cake\ORM\Entity;
/**
 * CommentableBehavior behavior
 */
class CommentableBehavior extends Behavior
{

    /**
     * @var array
     */
    protected $_defaultConfig = [
        'implementedMethods' => [
            'commentProcess' => 'process',
        ],
        'modelDefaults' => [
            'commentModel' => 'Comments.Comments',
            'spamField' => 'is_spam',
            'userModelAlias' => 'Users',
            'userModelClass' => 'Users',
            'userModel' => null,
        ],
        'definedActions' => [
            'clean',
            'ham',
            'spam',
            'deleteCommentOnly',
            'approve',
            'disapprove',
        ],
        'settings' => [],
    ];

    /**
     * @var null
     */
    public $model = null;

    /**
     * Initialize behavior
     *
     * @param array $config
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->model = TableRegistry::get($config['modelName']);

        if (!isset($this->_defaultConfig['settings'][$this->model->alias()])) {
            $this->_defaultConfig['settings'][$this->model->alias()] = $this->_defaultConfig['modelDefaults'];
        }
        $this->_defaultConfig['settings'][$this->model->alias()] = array_merge($this->_defaultConfig['settings'][$this->model->alias()], $config);
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
        $message = ['type' => 'error', 'body' => 'No comments were processed', 'count' => 0];
        if (!(in_array($action, $this->_defaultConfig['definedActions']))) {
            $message = [
                'body' => 'This action is not defined.'
            ];
            return $message;
        }
        $message['body'] = 'Yay!';
        foreach ($items as $item => $act) {
            $act = (bool)($act);
            if ($act) {
                if ($action !== 'deleteCommentOnly') {
                    if ($this->model->hasBehavior('Tree')) {
                        $treeBehavior = true;
                        $this->model->removeBehavior('Tree');
                    }
                    $comment = $this->model->get($item);
                    if ($action === 'ham' || $action === 'spam' || $action === 'clean') {
                        $comment->is_spam = $action;
                        $r = $this->model->save($comment);
                        if ($r instanceof Comment) {
                            $message['count']++;
                            $message['type'] = 'success';
//                            $message['body'] .= '<li>'.'</li>';
                        }
                    } elseif ($action === 'approve') {
                        $comment->approved = 1;
                        $r = $this->model->save($comment);
                        if ($r instanceof Comment) {
                            $message['count']++;
                            $message['type'] = 'success';
//                            $message['body'] .= '<li>'.'</li>';
                        }
                    } elseif ($action === 'disapprove') {
                        $comment->approved = 0;
                        $r = $this->model->save($comment);
                        if ($r instanceof Comment) {
                            $message['count']++;
                            $message['type'] = 'success';
//                            $message['body'] .= $comment->id.' has been marked not approved.<br />';
                        }
                    }
                    if ($treeBehavior) {
                        $this->model->addBehavior('Tree');
                    }
                }

                if ($action == 'deleteCommentOnly') {
                    $comment = $this->model->get($item);
                    $this->model->removeFromTree($comment);
                    if ($this->model->delete($comment)) {
                        $message['count']++;
                        $message['type'] = 'success';
//                        $message['body'] = '';
                    }
                }
            }
        }
        return $message;
    }

    /**
     * afterDelete callback
     * updates the number of comments for the object that was commented upon
     *
     * @param Event $event
     * @param EntityInterface $entity
     * @param \ArrayObject $options
     */
    public function afterDelete(Event $event, EntityInterface $entity, \ArrayObject $options)
    {
        $commentedModel = TableRegistry::get($entity->model);
        if (!$commentedModel->hasField('comments')) {
            return;
        }
        $commentCount = $event->subject()->find('all')
            ->where(['approved' => true, 'model' => $entity->model, 'foreign_key' => $entity->foreign_key])
            ->count();
        $query = $commentedModel->query();
        $query->update()
            ->set(['comments' => $commentCount])
            ->where(['id' => $entity->foreign_key])
            ->execute();
    }

    /**
     * afterSave callback
     * updates the number of comments for the object that was commented upon
     *
     * @param Event $event
     * @param EntityInterface $entity
     * @param \ArrayObject $options
     */
    public function afterSave(Event $event, EntityInterface $entity, \ArrayObject $options)
    {
        $commentedModel = TableRegistry::get($entity->model);
        if (!$commentedModel->hasField('comments')) {
            return;
        }
        $commentCount = $event->subject()->find('all')
            ->where(['approved' => true, 'model' => $entity->model, 'foreign_key' => $entity->foreign_key])
            ->count();
        $query = $commentedModel->query();
        $query->update()
            ->set(['comments' => $commentCount])
            ->where(['id' => $entity->foreign_key])
            ->execute();
    }

    /* */
}