<?php
namespace Comments\Model\Behavior;

use Cake\ORM\Behavior;
use Cake\ORM\Table;

/**
 * CommentableBehavior behavior
 */
class CommentableBehavior extends Behavior
{

    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function initialize(array $config)
    {
        parent::initialize($config);
    }
}
