<?php
    $displayType = Cake\Core\Configure::read('Comments.displayType');
    foreach ($comments as $comment) :
?>
        <?= $this->element('Comments.comments/comment', ['commentsModel' => $commentsModel, 'item' => $comment, 'class' => 'large-offset-1']); ?>
        <?= $this->cell('Comments.Comments::addComment', [
            $foreign_key,
            $model,
            $this->request->session()->read('Auth.User.id'),
            $comment->id,
            $redirectUrl,
            true
        ])->render('addComment'); ?>
    <?php if ($displayType === 'threaded') : ?>
        <?php foreach ($comment->children as $c => $child) : ?>
            <?= $this->element('Comments.comment', ['item' => $child, 'class' => 'large-offset-2']); ?>
        <?php endforeach; ?>
    <?php endif; ?>
<?php endforeach; ?>