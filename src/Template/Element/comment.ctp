<div id="comment-<?= $item->id ?>" class="<?= $class ?>">
    <div class="left">
        <a><?= $item->author_name ?></a>
        <!--(<?= $this->Html->link($item->author_name, 'mailto: '.$item->author_email) ?>)-->
        wrote <?php if($item->title): ?>
        <i><?= $item->title.': ' ?></i>
        <?php endif; ?>
        <?= $item->body ?>
    </div>
    <div>
        &nbsp;<?= $this->Time->timeAgoInWords($item->created) ?>
    </div>
</div>