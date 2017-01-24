<div>
    <?php
        $txt = ($parentId) ? 'Reply' : 'Add Comment';
        $id = ($parentId) ? $parentId : 'top';
        $bodyId = ($parentId) ? $parentId : 'top';
    ?>
    <?= $this->Html->link(
            $txt,
            '#',
            [
                'id' => 'button_'.$id,
                'onclick' => 'return false'
            ]
        ) ?>
    <?php if ($parentId) : ?>
        <?= $this->Html->link(
                'Quote',
                '#',
                [
                    'id' => 'quotebutton_'.$id,
                    'onclick' => 'return false'
                ]
            ) ?>
    <?php endif; ?>
    <!--<button id="button_<?= ($parentId) ? $parentId : 'top' ?>">Add comment</button>-->
</div>
<div class="large-offset-1 comment-form" id="form_<?= ($parentId) ? $parentId : 'top' ?>" style="display:none">
    <?= $this->Form->create($comment, [
        'url' => ['controller' => 'Comments', 'action' => 'add']
    ]) ?>
    <fieldset>
        <legend><?= __($legend) ?></legend>
        <?php
            echo $this->Form->input('parent_id', ['value' => $parentId, 'type' => 'hidden']);
            echo $this->Form->input('foreign_key', ['value' => $foreignKey, 'type' => 'hidden']);
            echo $this->Form->input('user_id', ['value' => $userId, 'type' => 'hidden']);
            echo $this->Form->input('model', ['value' => $model, 'type' => 'hidden']);
            echo $this->Form->input('title');
            echo $this->Form->input('body', ['id' => 'formbody_'.$bodyId]);
            echo $this->Form->input('author_name', ['value' => $this->request->session()->read('Auth.User.first_name').' ' .$this->request->session()->read('Auth.User.last_name')]);
            //echo $this->Form->input('author_url');
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