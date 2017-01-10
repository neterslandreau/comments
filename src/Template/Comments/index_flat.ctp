<!-- src/Template/Comments/index.ctp -->
<?php
/* *
echo $this->cell('Comments.FetchDataFlat');
/* */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Comment'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="comments index large-9 medium-8 columns content">
    <h3><?= __('Comments') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('parent_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('foreignKey') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('lft') ?></th>
                <th scope="col"><?= $this->Paginator->sort('rght') ?></th>
                <th scope="col"><?= $this->Paginator->sort('model') ?></th>
                <th scope="col"><?= $this->Paginator->sort('approved') ?></th>
                <th scope="col"><?= $this->Paginator->sort('title') ?></th>
                <th scope="col"><?= $this->Paginator->sort('slug') ?></th>
                <th scope="col"><?= $this->Paginator->sort('author_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('author_url') ?></th>
                <th scope="col"><?= $this->Paginator->sort('author_email') ?></th>
                <th scope="col"><?= $this->Paginator->sort('language') ?></th>
                <th scope="col"><?= $this->Paginator->sort('is_spam') ?></th>
                <th scope="col"><?= $this->Paginator->sort('comment_type') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($comments as $comment): ?>
            <tr>
                <td><?= h($comment->id) ?></td>
                <td><?= h($comment->parent_id) ?></td>
                <td><?= h($comment->foreignKey) ?></td>
                <td><?= h($comment->user_id) ?></td>
                <td><?= $this->Number->format($comment->lft) ?></td>
                <td><?= $this->Number->format($comment->rght) ?></td>
                <td><?= h($comment->model) ?></td>
                <td><?= h($comment->approved) ?></td>
                <td><?= h($comment->title) ?></td>
                <td><?= h($comment->slug) ?></td>
                <td><?= h($comment->author_name) ?></td>
                <td><?= h($comment->author_url) ?></td>
                <td><?= h($comment->author_email) ?></td>
                <td><?= h($comment->language) ?></td>
                <td><?= h($comment->is_spam) ?></td>
                <td><?= h($comment->comment_type) ?></td>
                <td><?= h($comment->created) ?></td>
                <td><?= h($comment->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $comment->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $comment->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $comment->id], ['confirm' => __('Are you sure you want to delete # {0}?', $comment->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?= $this->element('paging'); ?>
</div>
