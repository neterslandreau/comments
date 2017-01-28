<?php

return [
    'Comments' => [
        'table' => 'comments',
        'class' => 'Comments.Comments',
        'displayField' => 'title',
        'primaryKey' => 'id',
        'usersClass' => 'Users.Users',
        'displayType' => 'threaded', // finder for retrieiving comments (threaded or all)
    ],
];
