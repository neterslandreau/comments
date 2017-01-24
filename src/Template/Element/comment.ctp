<?php
    //$parser = new cebe\markdown\Markdown();
?>
<div id="comment-<?= $item->id ?>" class="<?= $class ?>">
    <div class="left">
        <a><?= $item->author_name ?></a>
        <!--(<?= $this->Html->link($item->author_name, 'mailto: '.$item->author_email) ?>)-->
        wrote <?php if($item->title): ?>
        <i><?= $item->title.': ' ?></i>
        <?php endif; ?>
        <span id="body_<?= $item->id ?>"><?= $item->body ?></span>
    </div>
    <div>
        &nbsp;<?= $this->Time->timeAgoInWords($item->created) ?>
    </div>
    <?php
        if ($commentsModel->isOwnedBy($item->id, $this->request->session()->read('Auth.User.id'))) :
    ?>
    <!--<div class="actions" style="border: 1px solid #000;">-->
        <?php /*
                 echo $this->cell('Comments.Comments::editComment', [
                    $item,
                    $this->request->session()->read('Auth.User.id'),
                    $redirectUrl,
                ])->render('editComment');
        */ ?>
    <!--</div>-->
    <?php endif; ?>
</div>