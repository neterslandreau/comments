<?php
namespace Comments\View\Cell;

use Cake\View\Cell;

/**
 * FetchDataFlat cell
 */
class FetchDataFlatCell extends Cell
{

    /**
     * List of valid options that can be passed into this
     * cell's constructor.
     *
     * @var array
     */
    protected $_validCellOptions = [];

    /**
     * Default display method.
     *
     * @return void
     */
    public function display()
    {
        $this->loadModel('Comments.Comments');
        $comments = $this->Comments->find('all');
        $this->set(compact($comments));
        $this->set('comments', $comments);

    }
}
