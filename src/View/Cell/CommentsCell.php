<?php
namespace Comments\View\Cell;

use Cake\View\Cell;

/**
 * Comments cell
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
     * Add a comment
     *
     * @param $foreignKey
     * @param $model
     * @param $userId
     * @param $parentId
     * @param $redirectUrl
     * @param bool $child
     */
    public function addComment($foreignKey, $model, $userId, $parentId, $redirectUrl, $child = false)
    {
        if (!strlen($parentId)) {
            $parentId = null;
        }
        $this->loadModel('Comments');
        $topCommentCount = $this->Comments->find()
            ->where(['foreign_key' => $foreignKey])
            ->where(['parent_id IS NULL'])
            ->count();

        $comment = $this->Comments->newEntity();
        $this->set('topCommentCount', $topCommentCount);
        $this->set('comment', $comment);
        $this->set('foreignKey', $foreignKey);
        $this->set('model', $model);
        $this->set('userId', $userId);
        $this->set('parentId', $parentId);
        $this->set('redirectUrl', $redirectUrl);
        if (!$child) {
            $this->set('legend', 'Add Top Level Comment');
            $this->set('label', 'Add Top Level Comment');
        } else {
            $this->set('legend', 'Add Comment to Comment');
            $this->set('label', 'Add Comment to Comment');
        }
    }

    /**
     * List the comments
     *
     * @param $foreignKey
     * @param $model
     * @param $userId
     * @param $redirectUrl
     */
    public function listComments($foreignKey, $model, $userId, $redirectUrl)
    {
        $this->loadModel('Comments');
        $comments = $this->Comments->find('threaded')->where(['foreign_key' => $foreignKey]);

        $this->set('redirectUrl', $redirectUrl);
        $this->set('userId', $userId);
        $this->set('model', $model);
        $this->set('foreign_key', $foreignKey);
        $this->set('comments', $comments);
    }
}
