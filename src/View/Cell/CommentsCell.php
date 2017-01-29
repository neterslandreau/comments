<?php
namespace Comments\View\Cell;

use Cake\Core\Configure;
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

        $comment = $this->Comments->newEntity();
        $this->set('comment', $comment);
        $this->set('foreignKey', $foreignKey);
        $this->set('model', $model);
        $this->set('userId', $userId);
        $this->set('parentId', $parentId);
        $this->set('redirectUrl', $redirectUrl);
        $this->set('action', 'add');
        if (!$child) {
            $this->set('legend', 'Add Top Level Comment');
            $this->set('label', 'Add Top Level Comment');
        } else {
            $this->set('legend', 'Reply to Comment');
            $this->set('label', 'Reply');
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
        $comments = $this->Comments->find(Configure::read('Comments.displayType'))
            ->where([
                'foreign_key' => $foreignKey,
                'approved' => true,
                'OR' => [['is_spam' => 'clean'], ['is_spam' => 'ham']]
            ]);

        $this->set('redirectUrl', $redirectUrl);
        $this->set('userId', $userId);
        $this->set('model', $model);
        $this->set('foreign_key', $foreignKey);
        $this->set('comments', $comments);
        $this->set('commentsModel', $this->Comments);
    }

    /**
     * Edit a comment
     *
     * @param $commentId
     * @param $userId
     * @todo implement at some future time perhaps
     */
    public function editComment($comment, $userId, $redirectUrl)
    {
        $this->loadModel('Comments');
        if ($this->Comments->isOwnedBy($comment->id, $userId)) {
            $this->set('comment', $comment);
            $this->set('foreignKey', $comment->foreign_key);
            $this->set('model', $comment->model);
            $this->set('userId', $comment->user_id);
            $this->set('parentId', $comment->parent_id);
            $this->set('title', $comment->title);
            $this->set('body', $comment->body);
            $this->set('legend', 'Edit Comment');
            $this->set('label', 'Edit Comment');
            $this->set('redirectUrl', $redirectUrl);
            $this->set('action', 'edit');
        } else {
            $this->set('error', 'not allowed');
        }
    }
}
