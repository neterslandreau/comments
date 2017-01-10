<?php
namespace Comments\View\Cell;

use Cake\View\Cell;

/**
 * FetchDataThreaded cell
 */
class CommentsCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * @param $foreignKey
     * @param $model
     * @param $userId
     * @param $parentId
     * @param $redirectUrl
     * @param bool $child
     */
    public function addComment($foreignKey, $model, $userId, $parentId, $redirectUrl, $child = false)
    {
        $this->loadModel('Comments');
        $comment = $this->Comments->newEntity();
        if (!strlen($parentId)) {
            $parentId = null;
        }
        $this->set('comment', $comment);
        $this->set('foreignKey', $foreignKey);
        $this->set('model', $model);
        $this->set('userId', $userId);
        $this->set('parentId', $parentId);
        $this->set('redirectUrl', $redirectUrl);
        if (!$child) {
            $this->set('label', 'Add Top Comment');
            $this->set('legend', 'Add Comment to Article');
        } else {
            $this->set('label', 'Add Child Comment');
            $this->set('legend', 'Add Comment to comment');

        }
    }

    /**
     * @param $foreignKey
     * @param $model
     * @param $userId
     * @param $redirectUrl
     */
    public function listComments($foreignKey, $model, $userId, $redirectUrl)
    {
        $this->loadModel('Comments');
        $query = $this->Comments;
        $comments = $query->find('threaded')->where(['foreignKey' => $foreignKey]);

        $this->set('redirectUrl', $redirectUrl);
        $this->set('userId', $userId);
        $this->set('model', $model);
        $this->set('foreignKey', $foreignKey);
        $this->set('comments', $comments);
    }

    public function listChildComments($foreignKey, $model, $userId, $redirectUrl) {
        $this->loadModel('Comments');
        $comments = $this->Comments->find('threaded')->where(['foreignKey' => $foreignKey]);

        $this->set('redirectUrl', $redirectUrl);
        $this->set('userId', $userId);
        $this->set('model', $model);
        $this->set('foreignKey', $foreignKey);
        $this->set('comments', $comments);
    }

    public function addChildComment($foreignKey, $model, $userId, $parentId, $redirectUrl)
    {
        $this->loadModel('Comments');
        $comment = $this->Comments->newEntity();

        $this->set('comment', $comment);
        $this->set('foreignKey', $foreignKey);
        $this->set('model', $model);
        $this->set('userId', $userId);
        $this->set('parentId', $parentId);
        $this->set('redirectUrl', $redirectUrl);
        $this->set('label', 'Add Child Comment');
        $this->set('legend', 'Add Comment to comment');

    }
}
