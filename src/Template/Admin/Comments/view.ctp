<?php
/**
 * Copyright 2009 - 2013, Cake Development Corporation (http://cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2009 - 2013, Cake Development Corporation (http://cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
//debug($comment->parent_comment);
?>
<div class="comments view">
    <h2><?php  echo __d('comments', 'Comment');?></h2>
    <dl><?php $i = 0; $class = ' class="altrow"';?>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('comments', 'Id'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
        <?php echo $comment->id; ?>
        &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('comments', 'Parent Comment'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
        <?php echo $this->Html->link($comment->parent_id, array('controller'=> 'comments', 'action'=>'view', $comment->parent_id)); ?>
        &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('comments', 'Commented On'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
        <?php echo $this->Html->link($comment['CommentedOn']['id'], array('controller'=> 'users', 'action'=>'view', $comment['CommentedOn']['id'])); ?>
        &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('comments', 'User'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
        <?php echo $this->Html->link($comment->user_id, array('controller'=> 'users', 'action'=>'view', $comment->user_id)); ?>
        &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('comments', 'Model'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
        <?php echo $comment->model; ?>
        &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('comments', 'Approved'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
        <?php echo $comment->approved; ?>
        &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('comments', 'Body'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
        <?php echo $comment->body; ?>
        &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('comments', 'Created'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
        <?php echo $comment->created; ?>
        &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __d('comments', 'Modified'); ?></dt>
        <dd<?php if ($i++ % 2 == 0) echo $class;?>>
        <?php echo $comment->modified; ?>
        &nbsp;
        </dd>
    </dl>
</div>
<div class="actions">
    <ul>
        <li><?php echo $this->Html->link(__d('comments', 'Edit Comment'), ['action'=>'edit', $comment->id]); ?> </li>
        <li><?= $this->Form->postLink(__d('comments', 'Delete Comment'), ['action' => 'delete', $comment->id], ['confirm' => __('Are you sure you want to delete # {0}?', $comment->id)]) ?></li>
        <li><?php echo $this->Html->link(__d('comments', 'List Comments'), array('action'=>'index')); ?> </li>
        <li><?php echo $this->Html->link(__d('comments', 'New Comment'), array('action'=>'add')); ?> </li>
        <li><?php echo $this->Html->link(__d('comments', 'List Comments'), array('controller'=> 'comments', 'action'=>'index')); ?> </li>
        <li><?php echo $this->Html->link(__d('comments', 'New Parent Comment'), array('controller'=> 'comments', 'action'=>'add')); ?> </li>
        <li><?php echo $this->Html->link(__d('comments', 'List Users'), array('controller'=> 'users', 'action'=>'index')); ?> </li>
        <li><?php echo $this->Html->link(__d('comments', 'New User'), array('controller'=> 'users', 'action'=>'add')); ?> </li>
    </ul>
</div>
<div class="related">
    <h3><?php echo __d('comments', 'Related Child Comments');?></h3>
    <?php if (!empty($comment->child_comment)):?>
    <table cellpadding = "0" cellspacing = "0">
        <tr>
            <th><?php echo __d('comments', 'Id'); ?></th>
            <th><?php echo __d('comments', 'Comment Id'); ?></th>
            <th><?php echo __d('comments', 'Foreign Key'); ?></th>
            <th><?php echo __d('comments', 'User Id'); ?></th>
            <th><?php echo __d('comments', 'Model'); ?></th>
            <th><?php echo __d('comments', 'Approved'); ?></th>
            <th><?php echo __d('comments', 'Body'); ?></th>
            <th><?php echo __d('comments', 'Created'); ?></th>
            <th><?php echo __d('comments', 'Modified'); ?></th>
            <th class="actions"><?php echo __d('comments', 'Actions');?></th>
        </tr>
        <?php
		$i = 0;
		foreach ($comment->child_comment as $item):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
		?>
        <tr<?php echo $class;?>>
        <td><?php echo $item['id'];?></td>
        <td><?php echo $item['comment_id'];?></td>
        <td><?php echo $item['foreign_key'];?></td>
        <td><?php echo $item['user_id'];?></td>
        <td><?php echo $item['model'];?></td>
        <td><?php echo $item['approved'];?></td>
        <td><?php echo $item['body'];?></td>
        <td><?php echo $item['created'];?></td>
        <td><?php echo $item['modified'];?></td>
        <td class="actions">
            <?php echo $this->Html->link(__d('comments', 'View'), array('controller'=> 'comments', 'action'=>'view', $item['id'])); ?>
            <?php echo $this->Html->link(__d('comments', 'Edit'), array('controller'=> 'comments', 'action'=>'edit', $item['id'])); ?>
            <?php
                echo $this->Form->postLink(__d('comments', 'Delete Comment'),
                    ['action' => 'delete', $item['id']],
                    ['confirm' => __('Are you sure you want to delete # {0}?', $item['id'])]
                );
            ?>
        </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>
    <div class="actions">
        <ul>
            <li><?php echo $this->Html->link(__d('comments', 'New Child Comment'), array('controller'=> 'comments', 'action'=>'add'));?> </li>
        </ul>
    </div>
    <?php
        if (!empty($comment->parent_comment)):
            $item = $comment->parent_comment;
    ?>
    <h3><?php echo __d('comments', 'Related Parent Comment');?></h3>
    <table cellpadding = "0" cellspacing = "0">
        <tr>
            <th><?php echo __d('comments', 'Id'); ?></th>
            <th><?php echo __d('comments', 'Comment Id'); ?></th>
            <th><?php echo __d('comments', 'Foreign Key'); ?></th>
            <th><?php echo __d('comments', 'User Id'); ?></th>
            <th><?php echo __d('comments', 'Model'); ?></th>
            <th><?php echo __d('comments', 'Approved'); ?></th>
            <th><?php echo __d('comments', 'Body'); ?></th>
            <th><?php echo __d('comments', 'Created'); ?></th>
            <th><?php echo __d('comments', 'Modified'); ?></th>
            <th class="actions"><?php echo __d('comments', 'Actions');?></th>
        </tr>
        <?php
		$i = 0;
        $class = null;
        if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
        }
        ?>
        <tr<?php echo $class;?>>
        <td><?php echo $item['id'];?></td>
        <td><?php echo $item['comment_id'];?></td>
        <td><?php echo $item['foreign_key'];?></td>
        <td><?php echo $item['user_id'];?></td>
        <td><?php echo $item['model'];?></td>
        <td><?php echo $item['approved'];?></td>
        <td><?php echo $item['body'];?></td>
        <td><?php echo $item['created'];?></td>
        <td><?php echo $item['modified'];?></td>
        <td class="actions">
            <?php echo $this->Html->link(__d('comments', 'View'), array('controller'=> 'comments', 'action'=>'view', $item['id'])); ?>
            <?php echo $this->Html->link(__d('comments', 'Edit'), array('controller'=> 'comments', 'action'=>'edit', $item['id'])); ?>
            <?php
                echo $this->Form->postLink(__d('comments', 'Delete Comment'),
            ['action' => 'delete', $item['id']],
            ['confirm' => __('Are you sure you want to delete # {0}?', $item['id'])]
            );
            ?>
        </td>
        </tr>
    </table>
    <?php endif; ?>

</div>
