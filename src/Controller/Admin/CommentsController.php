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
     * @var array
     */
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
        $this->loadComponent('Search.Prg');
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
        $conditions = $this->_adminIndexSearch();
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
        }
        $comments = $this->paginate($comments);

        $this->set(compact('comments'));
        $this->set('_serialize', ['comments']);
    }

    /**
     * Checks if the CakeDC Search plugin is present and if yes loads the PRG component
     *
     * @return array Conditions for the pagination
     */
    protected function _adminIndexSearch() {
        $conditions = array();
        if ($this->Prg) {
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
        if (!empty($this->request->data)) {
            try {
                $message = $this->Comments->process($this->request->data['Comment']['action'], $this->request->data);
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