<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Comments'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="comments form large-9 medium-8 columns content">
    <?= $this->Form->create($comment) ?>
    <fieldset>
        <legend><?= __('Add Comment') ?></legend>
        <?php
            echo $this->Form->input('parent_id', ['options' => ['parent_id' => $parent_id], 'type' => 'hidden']);
            echo $this->Form->input('foreignKey', ['value' => $foreignKey, 'type' => 'hidden']);
            echo $this->Form->input('user_id', ['options' => ['user_id' => $user_id], 'type' => 'hidden']);
//            echo $this->Form->input('lft');
//            echo $this->Form->input('rght');
            echo $this->Form->input('model', ['value' => $model, 'type' => 'hidden']);
            echo $this->Form->input('approved');
            echo $this->Form->input('title');
//            echo $this->Form->input('slug');
            echo $this->Form->input('body');
            echo $this->Form->input('author_name');
            echo $this->Form->input('author_url');
            echo $this->Form->input('author_email');
//            echo $this->Form->input('language');
            echo $this->Form->input('is_spam', ['type' => 'hidden', 'value' => 'clean']);
            echo $this->Form->input('comment_type', ['type' => 'hidden', 'value' => 'comment']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
