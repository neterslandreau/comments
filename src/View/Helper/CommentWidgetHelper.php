<?php
namespace Comments\View\Helper;

use Cake\View\Helper;
use Cake\View\View;
use Cake\Utility\Hash;
use Cake\Utility\Inflector;
use Cake\Core\Configure;

/**
 * CommentWidget helper
 */
class CommentWidgetHelper extends Helper
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];


    /**
     * Helpers
     *
     * @var array
     */
    public $helpers = array('Html', 'Js' => array('Jquery'));

    /**
     * Flag if this widget is properly configured
     *
     * @var boolean
     */
    public $enabled = true;

    /**
     * Helper options
     *
     * @var array
     */
    public $options = array(
        'target' => false,
        'ajaxAction' => false,
        'displayUrlToComment' => false,
        'urlToComment' => '',
        'allowAnonymousComment' => false,
        'url' => null,
        'ajaxOptions' => array(),
        'viewInstance' => null
    );

    /**
     * List of settings needed to be not empty in $this->request->params['Comments']
     *
     * @var array
     */
    protected $_passedParams = array(
        'displayType',
        'viewComments'
    );

    /**
     * Global widget parameters
     *
     * @var string
     */
    public $globalParams = array();

    /**
     * Constructor
     *
     * @param View $View
     * @param array $settings
     */
    public function __construct(View $View, $settings = array()) {
        $this->options(array());
        return parent::__construct($View, $settings);
    }

    /**
     * Before render Callback
     *
     * @param string $file
     * @return void
     */
    public function beforeRender($file = null) {
//        parent::beforeRender($file);
//        debug('beforeRender');
        $View = $this->__view();
//debug($View->viewVars);
        $this->enabled = !empty($View->viewVars['commentParams']);
        if ($this->enabled) {
            foreach ($this->_passedParams as $param) {
                if (empty($View->viewVars['commentParams'][$param])) {
                    $this->enabled = false;
                    break;
                }
            }
        }
    }

    /**
     * Setup options
     *
     * @param array $data
     * @return void
     */
    public function options($data) {
//        debug($this->options);
//        debug(array_merge(array_merge($this->globalParams, $this->options), array($data)));
        $this->globalParams = array_merge(array_merge($this->globalParams, $this->options), (array)$data);
//debug($this->globalParams);
        if (!empty($this->globalParams['target']) && empty($this->globalParams['ajaxOptions'])) {
            $this->globalParams['ajaxOptions'] = array(
                'rel' => 'nofollow',
                'update' => $this->globalParams['target'],
                'evalScripts' => true,
                'before' =>
                    $this->Js->get($this->globalParams['target'] . ' .comments')->effect('fadeOut', array('buffer' => false)) .
                    $this->Js->get('#busy-indicator')->effect('show', array('buffer' => false)),
                'complete' =>
                    $this->Js->get($this->globalParams['target'] . ' .comments')->effect('fadeIn', array('buffer' => false)) .
                    $this->Js->get('#busy-indicator')->effect('hide', array('buffer' => false)),
            );
        }
    }

    /**
     * Display comments
     *
     * ### Params
     *
     * - `displayType` The primary type of comments you want to display.  Default is flat, and built in types are
     *    flat, threaded and tree.
     * - `subtheme` an optional subtheme to use for rendering the comments, used with `displayType`.
     *    If your comments type is 'flat' and you use `'theme' => 'mytheme'` in your params.
     *   `elements/comments/flat_mytheme` is the directory the helper will look for your elements in.
     *
     * @param array $params Parameters for the comment rendering
     * @return string Rendered elements.
     */
    public function display($params = array()) {
        $result = '';
        if ($this->enabled) {
            $View = $this->__view();
//debug($params);
            $params = Hash::merge($View->viewVars['commentParams'], $params);
//debug($View->passedArgs);
            if (isset($params['displayType'])) {
                $theme = $params['displayType'];
                if (isset($params['subtheme'])) {
                    $theme .= '_' . $params['subtheme'];
                }
            } else {
                $theme = 'flat';
            }

            if (!is_null($this->globalParams['url'])) {
                $url = array();
            } else {
                $url = array();
                if (isset($View->request->params['userslug'])) {
                    $url[] = $View->request->params['userslug'];
                }
                if (!empty($View->passedArgs)) {
                    foreach ($View->passedArgs as $key => $value) {
                        if (is_numeric($key)) {
                            $url[] = $value;
                        }
                    }
                }
            }

            $model = $params['modelName'];
            $viewRecord = $this->globalParams['viewRecord'] = array();
            $viewRecordFull = $this->globalParams['viewRecordFull'] = array();
            if (isset($View->viewVars[Inflector::variable($model)][$model])) {
                $viewRecord = $View->viewVars[Inflector::variable($model)][$model];
                $viewRecordFull = $View->viewVars[Inflector::variable($model)];
            }

            if (isset($viewRecord['allow_comments'])) {
                $allowAddByModel = ($viewRecord['allow_comments'] == 1);
            } else {
                $allowAddByModel = 1;
            }
//            debug($params['comment']);
            $isAddMode = (isset($params['comment']) && !isset($params['comment_action']));
            $isAddMode = true;
            $adminRoute = Configure::read('Routing.admin');

            $allowAddByAuth = ($this->globalParams['allowAnonymousComment'] || !empty($View->viewVars['isAuthorized']));

            $params = array_merge($params, compact('url', 'allowAddByAuth', 'allowAddByModel', 'adminRoute', 'isAddMode', 'viewRecord', 'viewRecordFull', 'theme'));
            $this->globalParams = Hash::merge($this->globalParams, $params);
            $result = $this->element('main');
        }
//        debug($result);
        return $result;
    }

    /**
     * Link method used to add additional options in ajax mode
     *
     * @param string $title
     * @param mixed $url
     * @param array $options
     * @return string, url
     */
    public function link($title, $url = array(), $options = []) {
//        debug($options);
        if ($this->globalParams['target']) {
            return $this->Js->link($title, $this->prepareUrl($url), array_merge($this->globalParams['ajaxOptions'], $options));
        } else {
//debug($url);
            $link = $this->Html->link($title, $url, $options);
//debug($link);
            return $link;
        }
    }

    /**
     * Modify url in case of ajax request. Set ajaxAction that supposed to be stored in same controller.
     *
     * @param array $url
     * @return array, generated url
     */
    public function prepareUrl(&$url) {
        if ($this->globalParams['target']) {
            if (is_string($this->globalParams['ajaxAction'])) {
                $url['action'] = $this->globalParams['ajaxAction'];
            } elseif (is_array($this->globalParams['ajaxAction'])) {
                $url = array_merge($url, $this->globalParams['ajaxAction']);
            }
        }
        return $url;
    }

    /**
     * Render element from global theme
     *
     * @param string $name
     * @param array $params
     * @return string, rendered element
     */
    public function element($name, $params = array(), $extra = array()) {
//        debug($name);
//        debug($params);
        $View = $this->__view();
        if (strpos($name, '/') === false) {
            $name = 'comments/' . $this->globalParams['theme'] . '/' . $name;
        }
        $params = Hash::merge($this->globalParams, $params);
//debug($params);
//if ($name == 'main') {
//        debug($name);
//        debug($params);
//}
//        debug($name);
        $extra['ignoreMissing'] = true;
        $response = $View->element($name, $params, $extra);
        if (is_null($response) || strpos($response, 'Not Found:') !== false) {
            $response = $View->element('Comments.' . $name, array_merge($params));
        }
        return $response;
    }

    /**
     * Basic tree callback, used to generate tree of items element, rendered based on actual theme
     *
     * @param array $data
     * @return string
     */
    public function treeCallback($data) {
        return $this->element('item', array('comment' => $data['data'], 'data' => $data));
    }

    /**
     * Get current view class
     *
     * @return object, View class
     */
    private function __view() {
        if (!empty($this->globalParams['viewInstance'])) {
            return $this->globalParams['viewInstance'];
        } else {
            return $this->_View;
        }
    }
}
