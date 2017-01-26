<?php
namespace Comments\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cake\Event\Event;
use Cake\Controller\Exception\MissingActionException;

/**
 * Comments component
 */
class CommentsComponent extends Component
{

    /**
     * Enabled
     *
     * @var boolean $enabled
     */
    public $active = true;

    /**
     * Controller
     *
     * @var mixed $controller
     */
    public $Controller = null;

    /**
     * Name of actions this component should use
     *
     * Customizable in beforeFilter()
     *
     * @var array $actionNames
     */
    public $actionNames = array(
        'view', 'comments'
    );

    /**
     * Actions used for deleting of some model record, which doesn't use SoftDelete
     * (so we want comments delete directly)
     *
     * Causes than Comment association will NOT be automatically unbind()ed,
     * independently on $this->unbindAssoc
     *
     * Customizable in beforeFilter()
     *
     * @var array $deleteActions
     */
    public $deleteActions = [];

    /**
     * Name of 'commentable' model
     *
     * Customizable in beforeFilter(), or default controller's model name is used
     *
     * @var string Model name
     */
    public $modelName = null;

    /**
     * Name of association for comments
     *
     * Customizable in beforeFilter()
     *
     * @var string Association name
     */
    public $assocName = 'Comments';

    /**
     * Name of user model associated to comment
     *
     * Customizable in beforeFilter()
     *
     * @var string Name of the user model
     */
    public $userModel = 'Users';

    /**
     * Class Name for user model in ClassRegistry format.
     * Ex: For User model stored in User plugin need to use Users.User
     *
     * Customizable in beforeFilter()
     *
     * @var string user model class name
     */
    public $userModelClass = 'Users.Users';

    /**
     * Flag if this component should permanently unbind association to Comment model in order to not
     * query model for not necessary data in Controller::view() action
     *
     * Customizable in beforeFilter()
     *
     * @var boolean
     */
    public $unbindAssoc = true;

    /**
     * Flag to allow anonymous user make comments
     *
     * Customizable in beforeFilter()
     *
     * @var boolean
     */
    public $allowAnonymousComment = false;

    /**
     * Settings to use when CommentsComponent needs to do a flash message with SessionComponent::setFlash().
     * Available keys are:
     *
     * - `element` - The element to use, defaults to 'default'.
     * - `key` - The key to use, defaults to 'flash'
     * - `params` - The array of additional params to use, defaults to array()
     *
     * @var array
     */
    public $flash = array(
        'element' => 'default',
        'key' => 'flash',
        'params' => array()
    );

    /**
     * Constructor.
     *
     * @param ComponentRegistry $collection
     * @param array $settings
     * @return CommentsComponent
     */
    public function __construct(ComponentRegistry $collection, $settings = array()) {
        parent::__construct($collection, $settings);
        foreach ($settings as $setting => $value) {
            if (isset($this->{$setting})) {
                $this->{$setting} = $value;
            }
        }
    }

    /**
     * Initialize Callback
     *
     * @param array $config
     * @return void
     */
    public function initialize(array $config) {
        $controller = $this->_registry->getController();
        $this->Controller = $controller;
        if (empty($this->Cookie) && !empty($this->Controller->Cookie)) {
            $this->Cookie = $this->Controller->Cookie;
        }
        if (empty($this->Session) && !empty($this->Controller->Session)) {
            $this->Session = $this->Controller->Session;
        }
        if (empty($this->Auth) && !empty($this->Controller->Auth)) {
            $this->Auth = $this->Controller->Auth;
        }
        if (!$this->active) {
            return;
        }

        $model = TableRegistry::get($controller->modelClass);
        $this->modelName = $controller->modelClass;
        $this->modelAlias = $model->alias();
        $this->viewVariable = strtolower(Inflector::singularize($this->modelName));
        $controller->helpers = array_merge($controller->helpers, [
            'Comments.CommentWidget',
            'Comments.Cleaner',
            'Comments.Tree'
        ]);
        $loadedBehaviors = $model->behaviors()->loaded();

        if (!in_array('Commentable', $loadedBehaviors)) {
            $model->addBehavior('Comments.Commentable', [
                'userModelAlias' => $this->userModel,
                'userModelClass' => $this->userModelClass,
                'modelName' => $this->modelName
            ]);
        }
    }

    /**
     * Callback
     *
     * @param Event $event
     */
    public function startup(Event $event) {
//        debug('component startup');
        $model = TableRegistry::get($event->subject()->modelClass);
        $this->Controller = $event->subject();
        if (!$this->active) {
            return;
        }

        $this->Auth = $this->Controller->Auth;
        if (!empty($this->Auth) && $this->Auth->user()) {
            $event->subject()->set('isAuthorized', ($this->Auth->user('id') != ''));
        }

        if (in_array($event->subject()->request->action, $this->deleteActions)) {
            // add your softdelete behavior stuff here
        } elseif ($this->unbindAssoc) {
            $matches = $model->associations()->type('HasMany', 'HasOne');
            foreach ($matches as $match) {
                $model->associations()->remove($match->name());
            }
        }
    }

    /**
     * Callback
     *
     * @param $event
     */
    public function beforeFilter($event)
    {
    }

    /**
     * Callback
     *
     * @param Event $event
     * @return void
     */
    public function beforeRender(Event $event) {
//        debug('component before render');
        try {
            if ($this->active && in_array($this->Controller->request->action, $this->actionNames)) {
                $type = $this->_call('initType');
                $this->commentParams = array_merge($this->commentParams, array('displayType' => $type));
                $this->_call('view', array($type));
                $this->_call('prepareParams');
                $this->Controller->set('commentParams', $this->commentParams);
            }
        } catch (BlackHoleException $exception) {
            return $this->Controller->blackHole($exception->getMessage());
        } catch (MissingActionException $exception) {
        }
    }

    /**
     * Process comments
     *
     * @param null $action
     * @param null $id
     * @return mixed
     */
    public function commentProcess($action = null, $id = null) {
        $this->autoRender = false;
        if (!empty($this->request->data)) {
            $action = array_shift($this->request->data);
            $message = $this->Comments->process($action, $this->request->data);
            $this->Flash->{$message['type']}($message['body']);
        } else {
            $message = $this->Comments->process($action, [$id => 1]);
        }
        return $message;
    }

    /**
     * Determine used type of display (flat/threaded/tree)
     *
     * @return string Type of comment display
     *
    public function callback_initType() {
        debug('component init type');
        $types = array('flat', 'threaded', 'tree');
        $param = 'Comments.' . $this->modelName;
        if (!empty($this->Controller->passedArgs['comment_view_type'])) {
            $type = $this->Controller->passedArgs['comment_view_type'];
            if (in_array($type, $types)) {
                $this->Cookie->write($param, $type, true, '+2 weeks');
                return $type;
            }
        }

        $type = $this->Cookie->read($param);
        if ($type) {
            if (in_array($type, $types)) {
                return $type;
            } else {
                $this->Cookie->delete('Comments');
            }
        }
        return 'flat';
    }

    /**
     * Handles controllers actions like list/add related comments
     *
     * @param string $displayType
     * @param bool   $processActions
     * @throws RuntimeException
     * @return void
     *
    public function callback_view($displayType, $processActions = true) {
        $table = TableRegistry::get($this->modelName);
        if (!is_object($table) ||
            !(array_merge($table->associations()->type('hasOne'), $table->associations()->type('hasMany'))[0]->name() == $this->assocName))
            throw new RuntimeException('CommentsComponent: model ' . $this->modelName . ' or association ' . $this->assocName . ' doesn\'t exist');

        if (empty($this->Controller->viewVars[$this->viewVariable][$table->primarykey()])) {
            throw new RuntimeException('CommentsComponent: missing view variable ' . $this->viewVariable . ' or value for primary key ' . $table->primaryKey() . ' of model ' . $this->modelName);
        }

        $id = $this->Controller->passedArgs[0];
        $options = compact('displayType', 'id');
        if ($processActions) {
            $this->_processActions($options);
        }

        try {
            $data = $this->_call('fetchData' . Inflector::camelize($displayType), array($options));
        } catch (BadMethodCallException $exception) {
            $data = $this->_call('fetchData', array($options));
        }

        $this->Controller->set($this->viewComments, $data);
    }

    /**
     * Paginateable tree representation of the comment data.
     *
     * @param array $options
     * @return array
     *
    public function callback_fetchDataTree($options) {
debug($options);
        $Comments = TableRegistry::get($this->assocName);
        $settings = $this->_prepareModel($options);
        $settings += array('order' => array('Comments.lft' => 'asc'));
        debug($settings);
        debug($settings);
        $paginate = $settings;
        $paginate['limit'] = 10;
//debug($paginate);
//debug($this->Controller->Paginator->getDefaults('Articles', $options));
//        $overloadPaginate = !empty($this->Controller->paginate['Comments']) ? $this->Controller->paginate['Comments'] : array();
//        $this->Controller->Paginator->settings['Comments'] = array_merge($paginate, $overloadPaginate);
        $data = $this->Controller->paginate($Comments, $paginate);
debug($data);
//foreach ($data as $datum) {
//    debug($datum->rght);
//}
        $parents = array();
        if (is_object($data->first())) {
            $rec = $data->first();
            $settings['conditions'][] = array('Comments.lft <' => $rec->lft);
            $settings['conditions'][] = array('Comments.rght >' => $rec->rght);
debug($settings);
//            $parents = $this->Controller->{$this->modelName}->Comments->find('all', $settings);
        }
//        return array_merge($parents, $data);
    }

    /**
     * Flat representation of the comment data.
     *
     * @param array $options
     * @return array
     *
    public function callback_fetchDataFlat($options) {
        $Comments = TableRegistry::get($this->assocName);
        $paginate = $this->_prepareModel($options);
        return $this->Controller->paginate($Comments, $paginate);
    }

    /**
     * Threaded comment data, one-paginateable, the whole data is fetched.
     *
     * @param array $options
     * @return array
     *
    public function callback_fetchDataThreaded($options) {
        $Comments = TableRegistry::get($this->assocName);
        $settings = $this->_prepareModel($options);
        $settings['fields'] = array(
            'Comments.author_email', 'Comments.author_name', 'Comments.author_url',
            'Comments.id', 'Comments.user_id', 'Comments.foreign_key', 'Comments.parent_id', 'Comments.approved',
            'Comments.title', 'Comments.body', 'Comments.slug', 'Comments.created',
            $this->Controller->{$this->modelName}->alias() . '.' . $this->Controller->{$this->modelName}->primaryKey(),
            $this->userModel . '.' . $Comments->{$this->userModel}->primaryKey(),
            $this->userModel . '.' . $Comments->{$this->userModel}->displayField());

        if ($Comments->{$this->userModel}->hasField('slug')) {
            $settings['fields'][] = $this->userModel . '.slug';
        }

        $settings += array('order' => array(
            'Comments.parent_id' => 'asc',
            'Comments.created' => 'asc'));
        return $Comments->find('threaded', $settings);
    }

    /**
     * Default method, calls callback_fetchData
     *
     * @param array $options
     * @return array
     *
    public function callback_fetchData($options) {
        return $this->callback_fetchDataFlat($options);
    }

    /**
     * Prepare model association to fetch data
     *
     * @param array $options
     * @return boolean
     *
    protected function _prepareModel($options) {
        debug('component prepare mode');
        $params = array(
            'isAdmin' => $this->Auth->user('is_admin') == true,
            'userModel' => $this->userModel,
            'userData' => $this->Auth->user());
        return $this->Controller->{$this->modelName}->commentBeforeFind(array_merge($params, $options));
    }

    /**
     * Prepare passed parameters.
     *
     * @return void
     *
    public function callback_prepareParams() {
//        debug('component prepare params');
        $this->commentParams = array_merge($this->commentParams, array(
            'viewComments' => $this->viewComments,
            'modelName' => $this->modelAlias,
            'userModel' => $this->userModel));
        $allowedParams = array('comment', 'comment_action', 'quote');
        foreach ($allowedParams as $param) {
            if (isset($this->Controller->passedArgs[$param])) {
                $this->commentParams[$param] = $this->Controller->passedArgs[$param];
            }
        }
    }

    /**
     * Handle adding comments
     *
     * @param integer $modelId
     * @param integer $commentId Parent comment id
     * @param string  $displayType
     * @param array   $data
     *
    public function callback_add($modelId, $commentId, $displayType, $data = array()) {
        debug($modelId);
        debug($commentId);
        debug($data);
        debug($this->Controller->request->data);
        if (!empty($this->Controller->data)) {
            if (!empty($this->Controller->data['Comment']['title'])) {
                $data['Comment']['title'] = $this->cleanHtml($this->Controller->data['Comment']['title']);
            }
            $data['Comment']['body'] = $this->cleanHtml($this->Controller->data['Comment']['body']);
            $modelName = $this->Controller->{$this->modelName}->alias;
            if (!empty($this->Controller->{$this->modelName}->fullName)) {
                $modelName = $this->Controller->{$this->modelName}->fullName;
            }
            $permalink = '';
            if (method_exists($this->Controller->{$this->modelName}, 'permalink')) {
                $premalink = $this->Controller->{$this->modelName}->permalink($modelId);
            }
            $options = array(
                'userId' => $this->Auth->user('id'),
                'modelId' => $modelId,
                'modelName' => $modelName,
                'defaultTitle' => isset($this->Controller->defaultTitle) ? $this->Controller->defaultTitle : '',
                'data' => $data,
                'permalink' => $permalink);
            $result = $this->Controller->{$this->modelName}->commentAdd($commentId, $options);

            if (!is_null($result)) {
                if ($result) {
                    try {
                        $options['commentId'] = $result;
                        $this->_call('afterAdd', array($options));
                    } catch (BadMethodCallException $exception) {
                    }
                    $this->flash(__d('comments', 'The Comment has been saved.'));
                    $this->redirect(array('#' => 'comment' . $result));
                    if (!empty($this->ajaxMode)) {
                        $this->ajaxMode = null;
                        $this->Controller->set('redirect', null);
                        if (isset($this->Controller->passedArgs['comment'])) {
                            unset($this->Controller->passedArgs['comment']);
                        }
                        $this->_call('view', array($this->commentParams['displayType'], false));
                    }
                } else {
                    $this->flash(__d('comments', 'The Comment could not be saved. Please, try again.'));
                }
            }
        } else {
            if (!empty($_GET['quote'])) {
                if (!empty(_GET['comment'])) {
                    $message = $this->_call('getFormatedComment', array($_GET['comment']));
                    if (!empty($message)) {
                        $$_GET['Comment']['body'] = $message;
                    }
                }
            }
        }
    }

    /**
     * Fetch and format a comment message.
     *
     * @param string $commentId
     * @return string
     *
    public function callback_getFormatedComment($commentId) {
        debug(('get formatted comment'));
        $comment = $this->Controller->{$this->modelName}->Comment->find('first', array(
            'recursive' => -1,
            'fields' => array('Comment.body', 'Comment.title'),
            'conditions' => array('Comment.id' => $commentId)));
        if (!empty($comment)) {

        } else {
            return null;
        }
        return "[quote]\n" . $comment['Comment']['body'] . "\n[end quote]";
    }

    /**
     * Handles approval of comments.
     *
     * @param string $modelId
     * @param string $commentId
     * @throws BlackHoleException
     * @return void
     *
    public function callback_toggleApprove($modelId, $commentId) {
//        debug('component toggle approve');
        if (!isset($this->Controller->passedArgs['comment_action'])
            || !($this->Controller->passedArgs['comment_action'] == 'toggle_approve' && $this->Controller->Auth->user('is_admin') == true)) {
            throw new BlackHoleException(__d('comments', 'Nonrestricted operation'));
        }
        if ($this->Controller->{$this->modelName}->commentToggleApprove($commentId)) {
            $this->flash(__d('comments', 'The Comment status has been updated.'));
        } else {
            $this->flash(__d('comments', 'Error appear during comment status update. Try later.'));
        }
    }

    /**
     * Deletes comments
     *
     * @param string $modelId
     * @param string $commentId
     * @return void
     *
    public function callback_delete($modelId, $commentId) {
//        debug('component delete');
        if ($this->Controller->{$this->modelName}->commentDelete($commentId)) {
            $this->flash(__d('comments', 'The Comment has been deleted.'));
        } else {
            $this->flash(__d('comments', 'Error appear during comment deleting. Try later.'));
        }
        $this->redirect();
    }

    /**
     * Flash message - for ajax queries, sets 'messageTxt' view vairable,
     * otherwise uses the Session component and values from CommentsComponent::$flash.
     *
     * @param string $message The message to set.
     * @return void
     *
    public function flash($message) {
        $isAjax = isset($this->Controller->params['isAjax']) ? $this->Controller->params['isAjax'] : false;
        if ($isAjax) {
            $this->Controller->set('messageTxt', $message);
        } else {
            $this->Controller->Session->setFlash($message, $this->flash['element'], $this->flash['params'], $this->flash['key']);
        }
    }

    /**
     * Redirect
     * Redirects the user to the wanted action by persisting passed args excepted
     * the ones used internally by the component
     *
     * @param array $urlBase
     * @return void
     *
    public function redirect($urlBase = array()) {
        $isAjax = isset($this->Controller->params['isAjax']) ? $this->Controller->params['isAjax'] : false;

        $url = array_merge(
            array_diff_key($this->Controller->passedArgs, array_flip($this->_supportNamedParams)),
            $urlBase);
        if ($isAjax) {
            $this->Controller->set('redirect', $url);
        } else {
            $this->Controller->redirect($url);
        }
        if ($isAjax) {
            $this->ajaxMode = true;
            $this->Controller->set('ajaxMode', true);
        }
    }

    /**
     * Generate permalink to page
     *
     * @return string URL to the comment
     *
    public function permalink() {
        $params = array();
        foreach (array('admin', 'controller', 'action', 'plugin') as $name) {
            if (isset($this->Controller->request->params['name'])) {
                $params[$name] = $this->Controller->request->params['name'];
            }
        }

        if (isset($this->Controller->request->params['pass'])) {
            $params = array_merge($params, $this->Controller->params['pass']);
        }

        if (isset($this->Controller->request->params['named'])) {
            foreach ($this->Controller->request->params['named'] as $k => $v) {
                if (!in_array($k, $this->_supportNamedParams)) {
                    $params[$k] = $v;
                }
            }
        }
        return Router::url($params, true);
    }

    /**
     * Call action from component or overridden action from controller.
     *
     * @param string $method
     * @param array  $args
     * @throws MissingActionException
     * @return mixed
     */
    protected function _call($method, $args = array()) {
//        debug($method);
        $methodName = 'callback_comments' . Inflector::camelize(Inflector::underscore($method));
        $localMethodName = 'callback_' . $method;
        if (method_exists($this->Controller, $methodName)) {
            return call_user_func_array(array($this->Controller, $methodName), $args);
        } elseif (method_exists($this, $localMethodName)) {
            return call_user_func_array(array($this, $localMethodName), $args);
        } else {
            throw new MissingActionException($method);
        }
    }

    /**
     * Non view action process method
     *
     * @param array
     * @return boolean
     *
    protected function _processActions($options) {
        extract($options);
        if (isset($_GET['comment'])) {
            if ($this->allowAnonymousComment || $this->Auth->user()) {
                if (isset($_GET['comment_action'])) {
                    $commentAction = $_GET['comment_action'];
                    $isAdmin = (bool)$this->Auth->user('is_admin');
                    if (!$isAdmin) {
                        if (in_array($commentAction, array('delete'))) {
                            call_user_func(array($this, '_' . Inflector::variable($commentAction)), $id, $_GET['comment']);
                            return;
                        } else {
                            return $this->Controller->blackHole("CommentsComponent: comment_Action '$commentAction' is for admins only");
                        }
                    }
                    if (!in_array($commentAction, array('toggle_approve', 'delete'))) {
                        return $this->Controller->blackHole("CommentsComponent: unsupported comment_Action '$commentAction'");
                    }
                    $this->_call(Inflector::variable($commentAction), array($id, $_GET['comment']));
                } else {
                    Configure::write('Comment.action', 'add');
                    $parent = empty($_GET['comment']) ? null : $_GET['comment'];
                    $this->_call('add', array($id, $parent, $displayType));
                }
            } else {
                $this->Controller->Session->write('Auth.redirect', $this->Controller->request['url']);
                $this->Controller->redirect($this->Controller->Auth->loginAction);
            }
        }
    }
    /* */
}
