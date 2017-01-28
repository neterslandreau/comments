Quick Start
===========

We have a Post model and want to have comments on the /posts/view page.

Inside the view (in this case it will be Template/Posts/view.ctp) 
we will add the next line at the end of the view file.

```php
    <?= $this->element('Comments.commentCells'); ?>
```

You should now be able to comment on that page.

You can change the type of display for your comments in the config/comments.php.
