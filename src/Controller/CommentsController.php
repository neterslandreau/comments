<?php
namespace Comments\Controller;

use Cake\ORM\TableRegistry;
use Cake\Event\Event;

use Comments\Controller\AppController;

/**
 * Comments Controller
 *
 * @property \Comments\Model\Table\CommentsTable $Comments
 */
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
        'Html'
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
        $this->Comments = TableRegistry::get($this->modelClass);
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $comments = $this->Comments->find('treeList');

        $this->set(compact('comments'));
        $this->set('_serialize', ['comments']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $this->autoRender = false;
        if ($this->request->is('post')) {
            if (!strlen($this->request->data['parent_id'])) {
                $this->request->data['parent_id'] = null;
            }
            $comment = $this->Comments->newEntity();
            $comment = $this->Comments->patchEntity($comment, $this->request->data);
            if ($this->Comments->save($comment)) {
                $this->Flash->success(__('The comment has been saved.'));
                return $this->redirect($this->request->data['redirectUrl']);
            } else {
                $this->Flash->error(__('The comment could not be saved. Please, try again.'));
                return $this->redirect($this->request->data['redirectUrl']);
            }
        }
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
            debug($this->request->data);
            $comment = $this->Comments->patchEntity($comment, $this->request->data);
            if ($this->Comments->save($comment)) {
                $this->Flash->success(__('The comment has been saved.'));
                return $this->redirect($this->request->data['redirectUrl']);
            } else {
                $this->Flash->error(__('The comment could not be saved. Please, try again.'));
                return $this->redirect($this->request->data['redirectUrl']);
            }
        }
    }
    /* */
}
