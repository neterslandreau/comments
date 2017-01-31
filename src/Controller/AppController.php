<?php

namespace Comments\Controller;

use App\Controller\AppController as BaseController;

class AppController extends BaseController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
        $this->loadComponent('Paginator');
        $this->loadComponent('Comments.Comments');

    }
}
