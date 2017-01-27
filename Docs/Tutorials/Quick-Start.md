Quick Start
===========

We have a Post model and want to have comments on the /posts/view page. So we need to choose how we want to display the comments - flat or threaded.  In our example we will use the flat type comments. Inside the Comments plugin edit the config/comments.php and indicate the display type.

Inside the view (in this case it will be View/Posts/view.ctp) 
we will add the next line at the end of the view file.

```php
    <?= $this->element('Comments.commentCells'); ?>
```

You should now be able to comment on that page.
