<div>
    <?php
        $id = ($parentId) ? $parentId : 'top';
        echo $this->Html->link(
            'Edit comment',
            '#',
            [
                'id' => 'editbutton_'.$id,
                'onclick' => 'return false'
            ]
        );
    ?>
    <!--<button id="button_<?= ($parentId) ? $parentId : 'top' ?>">Add comment</button>-->
</div>
<div class="large-offset-1 comment-form" id="editform_<?= ($parentId) ? $parentId : 'top' ?>" style="display:none;">
    <?= $this->Form->create($comment, [
        'url' => ['controller' => 'Comments', 'action' => 'edit']
    ]) ?>
    <fieldset>
        <legend><?= __($legend) ?></legend>
        <?php
            echo $this->Form->input('parent_id', ['value' => $parentId, 'type' => 'hidden']);
            echo $this->Form->input('foreign_key', ['value' => $foreignKey, 'type' => 'hidden']);
            echo $this->Form->input('user_id', ['value' => $userId, 'type' => 'hidden']);
            echo $this->Form->input('model', ['value' => $model, 'type' => 'hidden']);
            echo $this->Form->input('title', ['value' => isset($title) ? $title : '']);
            echo $this->Form->input('body', ['value' => isset($body) ? $body : '']);
            echo $this->Form->input('author_name', ['value' => $this->request->session()->read('Auth.User.first_name').' ' .$this->request->session()->read('Auth.User.last_name')]);
//            echo $this->Form->input('author_url');
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