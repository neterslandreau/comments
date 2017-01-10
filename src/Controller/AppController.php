<?php

namespace Comments\Controller;

use App\Controller\AppController as BaseController;
use Cake\View\CellTrait;

class AppController extends BaseController
{
    use CellTrait;
    public function initialize()
    {
        parent::initialize();
    }
}
