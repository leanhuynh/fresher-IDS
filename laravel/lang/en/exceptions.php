<?php

return [
    'database' => [
        'update' => 'database has some errors when updating record!!',
        'error' => 'database has some errors!!'
    ],

    'search' => [
        'id' => 'not found id!',
    ],

    'not_found' => [
        'role' => 'Not found role!!',
        'hotel' => 'Not found hotel!!',
        'user' => 'Not found user!!',
        'auth' => 'Not found auth!!',
        'owner' => 'Not found owner!!',
        'password' => 'The password field is required.',
    ],

    'exist' => [
        'role' => 'Role exists in database!!',
        'hotel' => 'Hotel exists in database!!',
        'user' => 'User exists in database!!',
    ],

    'unexpected' => 'An unexpected error occurred: ',

    'unknown' => 'An unknown error occured!!',

    'permission' => [
        'action' => [
            'view' => [
                'role' => "You don't have permission to view the role!!",
                'hotel' => "You don't have permission to view the hotel!!",
                'user' => "You don't have permission to view the user!!",
                'page' => "You don't have permission to view the page!!",
            ],

            'create' => [
                'role' => "You don't have permission to create the role!!",
                'hotel' => "You don't have permission to create the hotel!!",
                'user' => "You don't have permission to create the user!!",
            ],

            'edit' => [
                'role' => "You don't have permission to edit the role!!",
                'hotel' => "You don't have permission to edit the hotel!!",
                'user' => "You don't have permission to edit the user!!",
            ],
            
            'delete' => [
                'role' => "You don't have permission to delete the role!!",
                'hotel' => "You don't have permission to delete the hotel!!",
                'user' => "You don't have permission to delete the user!!",
            ]
        ]
    ],

    
];