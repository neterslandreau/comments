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

    /* */
}
