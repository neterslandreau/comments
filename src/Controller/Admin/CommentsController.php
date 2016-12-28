<?php
namespace Comments\Controller\Admin;

use Comments\Controller\AppController;
use Cake\ORM\TableRegistry;

class CommentsController extends AppController
{
    /**
     * Name
     *
     * @var string
     */
    public $name = 'Comments';

    /**
     * Helpers
     *
     * @var array
     */
    public $helpers = array(
        'Text',
        'Time'
    );

    /**
     * Uses
     *
     * @var array
     */
    public $uses = array(
        'Comments.Comments'
    );

    /**
     * Preset for search views
     *
     * @var array
     */
    public $presetVars = array();

    /**
     *
     */
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Paginator');
        $this->loadComponent('Comments.Comments', ['active' => false]);
        $this->Comments = TableRegistry::get($this->modelClass);
    }

    /**
     * Admin index action
     *
     * @param string
     * @return void
     */
    public function index($type = '') {
        $this->Comments->recursive = 0;
        $this->Comments->bindModel([
            'belongsTo' => [
                'UserModel' => [
                    'className' => 'Users.User',
                    'foreignKey' => 'user_id'
                ]
            ]
        ], false);
        $conditions = array();
        $this->Paginator->settings = array(
            'Comment' => [
                'conditions' => $conditions,
                'contain' => [
                    'UserModel'
                ],
                'order' => 'Comment.created DESC'
            ]
        );
        if ($type == 'spam') {
            $this->Paginator->settings['Comments']['conditions'] = array('Comments.is_spam' => array('spam', 'spammanual'));
        } elseif ($type == 'clean') {
            $this->Paginator->settings['Comments']['conditions'] = array('Comments.is_spam' => array('ham', 'clean'));
        }
        $this->set('comments', $this->Paginator->paginate('Comments'));
    }

}