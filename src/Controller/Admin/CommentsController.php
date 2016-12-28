<?php
namespace Comments\Controller\Admin;

use Comments\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

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
//        $this->Comments->find('all');
//        $this->Comments->recursive = 0;
//        $this->Comments->bindModel([
//            'belongsTo' => [
//                'UserModel' => [
//                    'className' => 'Users.User',
//                    'foreignKey' => 'user_id'
//                ]
//            ]
//        ], false);
//        $conditions = [];
//        $this->Paginator->settings = [
//            'Comment' => [
//                'conditions' => $conditions,
//                'contain' => [
//                    'UserModel'
//                ],
//                'order' => 'Comment.created DESC'
//            ]
//        ];
//        if ($type == 'spam') {
//            $this->Paginator->settings['Comments']['conditions'] = ['Comments.is_spam' => ['spam', 'spammanual']];
//        } elseif ($type == 'clean') {
//            $this->Paginator->settings['Comments']['conditions'] = ['Comments.is_spam' => ['ham', 'clean']];
//        }
//        $this->set('comments', $this->Paginator->paginate($this->Comments));
        $comments = $this->paginate($this->Comments);
//        debug($this->modelClass);

        $this->set(compact('comments'));
        $this->set('_serialize', ['comments']);
    }

    /**
     * Processes mailbox folders
     *
     * @param string $folder Name of the folder to process
     * @return void
     */
    public function process($type = null) {
        if (!empty($this->request->data)) {
            try {
                $message = $this->Comment->process($this->request->data['Comment']['action'], $this->request->data);
            } catch (Exception $e) {
                $message = $e->getMessage();
            }
            $this->Comments->flash($message);
        }
        $url = array('plugin' => 'comments', 'action' => 'index', 'admin' => true);
        $url = Hash::merge($url, $this->request->params['pass']);
        $this->redirect(Hash::merge($url, $this->request->params['named']));
    }
}