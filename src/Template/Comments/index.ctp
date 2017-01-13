<?php //echo $this->Form->input('comments', ['options' => $comments]); ?>
<?php
    echo $this->Html->script(['http://code.jquery.com/jquery-latest.min.js', 'Comments.comments'], ['block' => true]);
    foreach($comments as $comment) {
//        debug($comment);
    }
?>