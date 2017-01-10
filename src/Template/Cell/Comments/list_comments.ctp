<?php
    foreach ($comments as $comment) {
        echo $this->element('Comments.comment', ['item' => $comment, 'class' => 'large-offset-1']);
        echo '<div class="large-offset-1">';
        echo $this->cell('Comments.Comments::addComment', [
            $foreignKey,
            $model,
            $this->request->session()->read('Auth.User.id'),
            $comment->id,
            $redirectUrl,
            true
        ])->render('addComment');
        echo '</div>';
        foreach ($comment->children as $c => $child) {
            echo $this->element('Comments.comment', ['item' => $child, 'class' => 'large-offset-2']);
        }
    }