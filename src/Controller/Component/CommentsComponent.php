<?php
namespace Comments\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;
use Cake\Event\Event;
use Cake\Controller\Exception\MissingActionException;
use Cake\Core\Configure;

/**
 * Comments component
 */
class CommentsComponent extends Component
{

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
        $model = TableRegistry::get($controller->modelClass);
        $loadedBehaviors = $model->behaviors()->loaded();

        if (!in_array('Commentable', $loadedBehaviors)) {
            $model->addBehavior('Comments.Commentable', [
                'userModelAlias' => Configure::read('Comments.usersAlias'),
                'userModelClass' => Configure::read('Comments.usersClass'),
                'modelName' => $controller->modelClass,
            ]);
        }
    }

    /**
     * Callback
     *
     * @param Event $event
     */
    public function startup(Event $event) {

        if (in_array($event->subject()->request->action, $this->deleteActions)) {
            // add your softdelete behavior stuff here
        }
    }

    /**
     * Generate permalink to page
     *
     * @return string URL to the comment
     * @deprecated unused only kept as reminder
     */
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
     * @deprecated
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
