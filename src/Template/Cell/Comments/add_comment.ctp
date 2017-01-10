<div class="large-offset-1">
    <?= $this->Form->create($comment, [
        'url' => ['controller' => 'Comments', 'action' => 'add']
    ]) ?>
    <fieldset>
        <legend><?= __($legend) ?></legend>
        <?php
            echo $this->Form->input('parent_id', ['value' => $parentId, 'type' => 'hidden']);
            echo $this->Form->input('foreignKey', ['value' => $foreignKey, 'type' => 'hidden']);
            echo $this->Form->input('user_id', ['value' => $userId, 'type' => 'hidden']);
            echo $this->Form->input('model', ['value' => $model, 'type' => 'hidden']);
            echo $this->Form->input('title');
            echo $this->Form->input('body');
            echo $this->Form->input('author_name', ['value' => $this->request->session()->read('Auth.User.first_name').' ' .$this->request->session()->read('Auth.User.last_name')]);
 //           echo $this->Form->input('author_url');
            echo $this->Form->input('author_email', ['value' => $this->request->session()->read('Auth.User.email')]);
            echo $this->Form->input('approved', ['value' => true, 'type' => 'hidden']);
            echo $this->Form->input('is_spam', ['type' => 'hidden', 'value' => 'clean']);
            echo $this->Form->input('comment_type', ['type' => 'hidden', 'value' => 'comment']);
            echo $this->Form->input('redirectUrl', ['value' => $redirectUrl, 'type' => 'hidden']);
        ?>
    </fieldset>
    <?= $this->Form->button(__($label)) ?>
    <?= $this->Form->end() ?>

</div>