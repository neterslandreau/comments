<div id="comment-<?= $item->id ?>" class="<?= $class ?>">
    <div><h3><?= $item->title ?></h3></div>
    <div><?= $item->body ?></div>
    <div><?= $item->author_name ?></div>
    <div><?= $item->author_email ?></div>
    <div>Created: <?= $item->created ?></div>
    <div>Modified: <?= $item->modified ?></div>
</div>