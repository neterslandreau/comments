<?php
    use Cake\Utility\Inflector;
    $modelEntity = strtolower(Inflector::camelize(Inflector::singularize($this->request->params['controller'])));
    $redirectUrl = Cake\Routing\Router::url([
        'controller' => $this->request->params['controller'],
        'action' => $this->request->params['action'], ${$modelEntity}->id
    ]);
?>
<?= $this->Html->script(['Comments.comments.js'], ['block' => true]);?>
<?= $this->cell('Comments.Comments::addComment', [${$modelEntity}->id, $model, $this->request->session()->read('Auth.User.id'), '', $redirectUrl])->render('AddComment'); ?>
<?= $this->cell('Comments.Comments::listComments', [${$modelEntity}->id, $model, $this->request->session()->read('Auth.User.id'), $redirectUrl])->render('ListComments'); ?>
