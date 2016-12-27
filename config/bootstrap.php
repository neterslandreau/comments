<?php
use Cake\Core\Configure;
use Cake\Core\Exception\MissingPluginException;
use Cake\Core\Plugin;
use Cake\Event\EventManager;
use Cake\Log\Log;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

Configure::load('Comments.comments');
collection((array)Configure::read('Comments.config'))->each(function ($file) {
   Configure::load($file);
});

TableRegistry::config('Comments', ['className' => Configure::read('Comments.table')]);