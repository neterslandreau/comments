<?php
namespace Comments\Controller\Admin;

use Comments\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Event\Event;

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
        'Time',
    );

    /**
     * filterFlag
     *
     * @var null
     */
    public $filterFlag = null;

    /**
     * displayType
     *
     * @var null
     */
    public $displayType = null;

    /**
     * Initialize
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
     * beforeFilter
     *
     * @param Event $event
     */
    public function beforeFilter(Event $event)
    {
        $this->filterFlag = ($this->request->session()->check('Comments.Admin.filterFlag')) ? $this->request->session()->read('Comments.Admin.filterFlag') : '';
        $this->displayType = ($this->request->session()->check('Comments.Admin.displayType')) ? $this->request->session()->read('Comments.Admin.displayType') : 'all';
    }

    /**
     * Admin index action
     *
     * @return void
     */
    public function index() {
        if ($this->request->is('post')) {
            $this->displayType = (isset($_REQUEST['displayType'])) ? $_REQUEST['displayType'] : $this->displayType;
            $this->filterFlag = (isset($_REQUEST['filterFlag'])) ? $_REQUEST['filterFlag'] : $this->filterFlag;
            $this->request->session()->write('Comments.Admin.filterFlag', $this->filterFlag);
            $this->request->session()->write('Comments.Admin.displayType', $this->displayType);
        }

        $this->request->session()->write('Comments.Admin.filterFlag', $this->filterFlag);
        if ($this->filterFlag == 'spam') {
            $comments = $this->Comments->find($this->displayType)
                ->contain(['Users'])
                ->where(['is_spam' => 'spam'])
                ->orWhere(['is_spam' => 'spammanual']);
        } elseif ($this->filterFlag == 'clean') {
            $comments = $this->Comments->find($this->displayType)
                ->contain(['Users'])
                ->where(['is_spam' => 'clean'])
                ->orWhere(['is_spam' => 'ham']);
        } else {
            $comments = $this->Comments->find($this->displayType)
                ->contain(['Users']);
        }

        $comments = $this->paginate($comments);
        $this->set('filterFlag', $this->filterFlag);
        $this->set('displayType', $this->displayType);
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
     * @todo implement this feature
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
            $message = $this->Comments->commentProcess($action, [$id => 1]);
        } else {
            $action = array_shift($this->request->data);
            $message = $this->Comments->commentProcess($action, $this->request->data);
        }
        $this->Flash->{$message['type']}($message['body']);
        $url = array('prefix' => 'admin', 'plugin' => 'comments', 'action' => 'index');
        $this->redirect($url);
    }
}