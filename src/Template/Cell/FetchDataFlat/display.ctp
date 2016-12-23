<!-- src/Template/Cell/FetchDataFlat/display.ctp -->
<?php foreach ($comments as $comment) : ?>
<div class="notification-icon">
    <?= $comment->title ?>
</div>
<?php endforeach; ?>