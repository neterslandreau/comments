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
        $this->loadComponent('Comments.Comments');
        $this->Comments = TableRegistry::get($this->modelClass);
    }

    /**
     * Admin index action
     *
     * @param string
     * @return void
     */
    public function index($type = '') {
        $this->request->session()->write('Comments.filterFlag', $type);
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
        $this->set('filter', $type);
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
        $comment = $this->Comments->get($id, [
            'contain' => ['ParentComments', 'ChildComment', 'Users']
        ]);
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
    public function process($action = null, $id = null) {
        $this->autoRender = false;
        if (empty($this->request->data)) {
            $message = $this->Comments->process($action, [$id => 1]);
        } else {
            $action = array_shift($this->request->data);
            $message = $this->Comments->process($action, $this->request->data);
        }
        debug($message);
        $this->Flash->{$message['type']}($message['body']);
        $filterFlag = $this->request->session()->read('Comments.filterFlag');
        $url = array('prefix' => 'admin', 'plugin' => 'comments', 'action' => 'index', $filterFlag);
        $this->redirect($url);
    }
}