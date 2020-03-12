<?php
return [
    'createPost' => [
        'type' => 2,
        'description' => 'Create a post',
    ],
    'updatePost' => [
        'type' => 2,
        'description' => 'Update a post',
    ],
    'updateOwnPost' => [
        'type' => 2,
        'description' => 'Update own post',
        'ruleName' => 'author',
        'children' => [
            'updatePost',
        ],
    ],
    'author' => [
        'type' => 1,
        'children' => [
            'createPost',
            'updateOwnPost',
        ],
    ],
    'manageUser' => [
        'type' => 2,
        'description' => 'Manage user',
    ],
    'admin' => [
        'type' => 1,
        'ruleName' => 'userPrivilege',
        'children' => [
            'manageUser',
            'updatePost',
            'author',
        ],
    ],
];
