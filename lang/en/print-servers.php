<?php

return [
    'heading' => 'Print Servers',
    'never_connected_status' => 'Never Connected',
    'seen_online_status' => 'Seen :diff_for_humans',
    'online-status' => 'Online',
    'create-new-print-server-label' => 'Create a new print server',
    'no-print-servers-label' => 'No print servers',
    'no-permissions-to-create-print-server-label' => 'You don\'t have permission to create new print servers.',
    'heading_create' => 'Create Print Server',
    'heading_show' => 'Print Server',
    'name_label' => 'Name',
    'create' => [
        'title' => 'Print Server',
        'description' => 'Provide print server details',
    ],
    'update' => [
        'title' => 'Print Server',
        'description' => 'Update print server details',
    ],
    'delete' => [
        'button' => 'Delete Server',
        'title' => 'Delete Print Server',
        'description' => 'Permanently delete this Print Server',
        'message' => 'Once a server is deleted, all of its resources and data will be permanently deleted. Before deleting this server, please download any data or information regarding this server that you wish to retain.',
        'modal' => [
            'title' => 'Delete Server',
            'content' => 'Are you sure you want to delete this server? Once a server is deleted, all of its resources and data will be permanently deleted.',
        ],
        'message_disabled' => 'Server can\'t be deleted at this moment. Please delete printers first.',
    ],
    'token' => [
        'button' => 'Generate new Access Token',
        'modal-title' => 'Print Server Token',
        'modal-token' => 'Please copy your new API token. For your security, it won\'t be shown again.',
        'modal-env' => 'If you need environmental variables for setting in WebPrint Service, use following:',
        'modal-docker' => 'If you need a command to create docker instance with WebPrint Service, use following:',
        'title' => 'Generate API Token',
        'description' => 'Token will allow to connect WebPrint Service to this server',
        'overwrite_modal' => [
            'button' => 'Regenerate API Token',
            'title' => 'This Print Server already has generated API Token',
            'content' => 'If you regenerate API Token, the old token will stop working.',
            'content_last_used' => 'Old token was last used :last_used.',
        ],
        'message' => 'For WebPrint Service installation, you will need API Token. To begin, press following button.',
    ],
];
