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
     *
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
     * @var array
     *
    public $components = [
        'RequestHandler',
        'Paginator',
        'Search.Prg',
        'Comments.Comments', ['active' => false],
    ];

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
//        $conditions = $this->_adminIndexSearch();
//        debug($conditions);
//        $comments = $this->Comments->find()
//            ->where($conditions);

        if ($type == 'spam') {
            $comments = $this->Comments->find()
                ->contain(['Users'])
                ->where(['is_spam' => 'spam'])
                ->orWhere(['is_spam' => 'spammanual']);
        } elseif ($type == 'clean') {
            $comments = $this->Comments->find()
                ->contain(['Users'])
                ->where(['is_spam' => 'clean'])
                ->orWhere(['is_spam' => 'ham']);
        } else {
            $comments = $this->Comments->find()
                ->contain(['Users']);
        }
        $comments = $this->paginate($comments);
//debug($comments);
        $this->set(compact('comments'));
        $this->set('_serialize', ['comments']);
    }

    /**
     * View method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
//        debug($this->Comments->associations());
        $comment = $this->Comments->get($id, [
            'contain' => ['ParentComments', 'ChildComment', 'Users']
        ]);
//debug($comment->Users);
        $this->set('comment', $comment);
        $this->set('_serialize', ['comment']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $comment = $this->Comments->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $comment = $this->Comments->patchEntity($comment, $this->request->data);
            if ($this->Comments->save($comment)) {
                $this->Flash->success(__('The comment has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The comment could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('comment'));
        $this->set('_serialize', ['comment']);
    }

    /**
     * Checks if the CakeDC Search plugin is present and if yes loads the PRG component
     *
     * @return array Conditions for the pagination
     */
    protected function _adminIndexSearch() {
        debug('here');
        $conditions = array();
        if (class_exists('PrgComponent', false)) {
            debug('there');
            $this->Comments->addBehavior('Search.Searchable');
            $this->Comments->filterArgs = [
                array('field' => 'is_spam', 'name' => 'is_spam', 'type' => 'value'),
                array('field' => 'approved', 'name' => 'approved', 'type' => 'value')
            ];
            $this->Prg->commonProcess();
            $conditions = $this->Comments->find('searchable', $this->Prg->parsedParams());
            $this->set('searchEnabled', true);
        }
        return $conditions;
    }

    /**
     * Processes mailbox folders
     *
     * @param string $folder Name of the folder to process
     * @return void
     */
    public function process($type = null) {
        $this->autoRender = false;
        if (!empty($this->request->data)) {
            $action = array_shift($this->request->data);
            $message = $this->Comments->process($action, $this->request->data);
            $this->Flash->{$message['type']}($message['body']);
        }
//        $url = array('prefix' => 'admin', 'plugin' => 'comments', 'action' => 'index');
//        $this->redirect($url);
    }

    /**
     * Delete method
     *
     * @param string|null $id Comment id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $comment = $this->Comments->get($id);
        if ($this->Comments->delete($comment)) {
            $this->Flash->success(__('The comment has been deleted.'));
        } else {
            $this->Flash->error(__('The comment could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}