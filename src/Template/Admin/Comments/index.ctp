<?= $this->Html->script(['Comments.comments.js'], ['block' => true]) ?>
<h2><?= __d('comments', 'Comments');?></h2>


<ul>
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
<?= $this->Form->create(null,
    ['url' => ['action' => 'process']]
);?>
<?= $this->Form->input('action', array(
        'type' => 'select',
        'options' => [
            'ham' => __d('comments', 'Mark as ham'),
            'spam' => __d('comments', 'Mark as spam'),
            'delete' => __d('comments', 'Delete'),
            'approve' => __d('comments', 'Approve'),
            'disapprove' => __d('comments', 'Dispprove')
        ]
    ));
?>
<?= $this->Form->submit('Process');?>
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
        <th class="actions"><?= __d('comments', 'Actions');?></th>
    </tr>
    <?php
	$i = 0;
	foreach ($comments as $comment) :
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
    <tr<?= $class;?>>
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
    <td class="actions">
        <?= $this->Html->link(__d('comments', 'Approve'), ['action' => 'approve', $comment->id]); ?>
        <?= $this->Html->link(__d('comments', 'Mark as spam'), ['action' => 'spam', $comment->id]); ?>
        <?= $this->Html->link(__d('comments', 'Mark as ham'), ['action' => 'ham', $comment->id]); ?>
        <?= $this->Html->link(__d('comments', 'Disapprove'), ['action' => 'disapprove', $comment->id]); ?>
        <?= $this->Html->link(__d('comments', 'View'), ['action' => 'view', $comment->id]); ?>
        <?= $this->Html->link(__d('comments', 'Edit'), ['action' => 'edit', $comment->id]); ?>
        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $comment->id], ['confirm' => __('Are you sure you want to delete # {0}?', $comment->id)]) ?>
    </td>
    </tr>
    <?php endforeach; ?>
</table>
<?= $this->Form->end(); ?>

<?= $this->element('paging'); ?>

<script type="text/javascript">
    $("td div.hidden").show();
    $("td a.toggle").click(function(event) {
        $this = $(this);
        if ($this[0].innerHTML == 'Show') {
            $this[0].innerHTML = 'Hide';
        } else {
            $this[0].innerHTML = 'Show';
        }
        $(this).parent().find("div.hidden").toggle();
        event.preventDefault();
    });
</script>