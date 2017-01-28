<?= $this->Html->script(['Comments.comments.js'], ['block' => true]) ?>
<nav class="large-2 medium-3 columns" id="actions-sidebar">
<ul class="side-nav">
    <li class="heading">Bulk Actions</li>
    <li>
        <?= $this->Html->link(__d('comments', 'No filter'),
            ['action' => 'index']
        );?>
    </li>
    <li>
        <?= $this->Html->link(__d('comments', 'Filter spam comments'),
            ['action' => 'index', 'spam']
        );?>
    </li>
    <li>
        <?= $this->Html->link(__d('comments', 'Filter good comments'),
            ['action' => 'index', 'clean']
        );?>
    </li>
</ul>
    <?php
        echo $this->Form->create(null,
            [
                'id' => 'CommentsForm',
                'name' => 'CommentsForm',
                'url' => ['action' => 'process']
            ]
        );
    ?>
    <?php echo $this->Form->input('action', array(
        'type' => 'select',
        'options' => [
            'clean' => __d('comments', 'Mark as clean'),
            'ham' => __d('comments', 'Mark as ham'),
            'spam' => __d('comments', 'Mark as spam'),
            'deleteCommentOnly' => __d('comments', 'Delete'),
            'approve' => __d('comments', 'Approve'),
            'disapprove' => __d('comments', 'Dispprove')
        ]
    ));?>
    <?= $this->Form->submit('Process');?>
</nav>
<div class="comments view large-10 medium-9 columns content">
    <h3><?= __('Comments') ?></h3>
<table cellpadding="0" cellspacing="0">
    <tr>
        <th><?= $this->Paginator->sort('name');?></th>
        <th> Body </th>
        <th><?= $this->Paginator->sort('author_name');?></th>
        <th><?= $this->Paginator->sort('author_email');?></th>
        <th><?= $this->Paginator->sort('author_url');?></th>
        <th><?= $this->Paginator->sort('created');?></th>
        <th><?= $this->Paginator->sort('is_spam');?></th>
        <th><?= $this->Paginator->sort('approved');?></th>
        <th><?= __d('comments', 'Select...');?>
            <input id="mainCheck" style="width: 100%;" type="checkbox"></th>
        <th class="actions" colspan="2"><?= __d('comments', 'Actions');?></th>
    </tr>
    <?php foreach ($comments as $comment) : ?>
    <tr>
    <td>
        <?= h($comment->title); ?>
    </td>
    <td>
        <div class="hidden"><?= h($comment->body); ?> </div>
        <?= $this->Html->link(__('Hide'), '#', array('class' => 'toggle')); ?>
    </td>
    <td>
        <?= h($comment->author_name); ?>
    </td>
    <td>
        <?= h($comment->author_email); ?>
    </td>
    <td>
        <?= h($comment->author_url); ?>
    </td>
    <td>
        <?= $comment->created; ?>
    </td>
    <td>
        <?= $comment->is_spam; ?>
    </td>
    <td>
        <?= $comment->approved ? __d('comments', 'Yes') : __d('comments', 'No'); ?>
    </td>
    <td class="comment-check">
        <?= $this->Form->input($comment->id, ['label' => false, 'div' => false, 'class' => 'cbox', 'type' => 'checkbox']);?>
    </td>
    <td class="actions" colspan="2">
        <ul class="side-nav">
            <li>
                <?= $this->Html->link(__d('comments', 'Approve'), ['action' => 'process', 'approve', $comment->id]); ?>
            </li>
            <li>
                <?= $this->Html->link(__d('comments', 'Mark as clean'), ['action' => 'process', 'clean', $comment->id]); ?>
            </li>
            <li>
                <?= $this->Html->link(__d('comments', 'Mark as spam'), ['action' => 'process', 'spam', $comment->id]); ?>
            </li>
            <li>
                <?= $this->Html->link(__d('comments', 'Mark as ham'), ['action' => 'process', 'ham', $comment->id]); ?>
            </li>
            <li>
                <?= $this->Html->link(__d('comments', 'Disapprove'), ['action' => 'process', 'disapprove', $comment->id]); ?>
            </li>
            <li>
                <?= $this->Html->link(__d('comments', 'View'), ['action' => 'view', $comment->id]); ?>
            </li>
            <li>
                <?= $this->Html->link(__d('comments', 'Edit'), ['action' => 'edit', $comment->id]); ?>
            </li>
            <li>
                <?php echo $this->Form->postLink(__d('comments', 'Delete'),
                    ['action' => 'process', 'deleteCommentOnly', $comment->id],
                    ['confirm' => __d('comments', 'Are you sure you want to delete # {0}?', $comment->id)]
                ); ?>
            </li>
        </ul>
    </td>
    </tr>
    <?php endforeach; ?>
</table>
<?= $this->Form->end(); ?>

<?= $this->element('paging'); ?>
</div>
