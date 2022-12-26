<?php

return [
    'no-url-label' => 'No Location',
    'no-client-applications-label' => 'No client applications',
    'create-new-client-application-label' => 'Create a new client application',
    'no-permissions-to-create-application-label' => 'You don\'t have permission to create new client applications.',
    'heading_show' => 'Client Application',
    'heading_create' => 'Create Client Application',
    'name_label' => 'Name',
    'printers_label' => 'Permitted Printers',
    'create' => [
        'title' => 'Client Application',
        'description' => 'Provide client application details',
    ],
    'update' => [
        'title' => 'Client Application',
        'description' => 'Update client application details',
    ],
    'delete' => [
        'button' => 'Delete Application',
        'title' => 'Delete Client Application',
        'description' => 'Permanently delete this Client Application',
        'message' => 'Once a app is deleted, all of its resources and data will be permanently deleted. Before deleting this app, please download any data or information regarding this app that you wish to retain.',
        'modal' => [
            'title' => 'Delete Application',
            'content' => 'Are you sure you want to delete this app? Once a app is deleted, all of its resources and data will be permanently deleted.',
        ],
        'message_disabled' => 'Application can\'t be deleted at this moment. Please related resources first.',
    ],
    'token' => [
        'button' => 'Generate new Access Token',
        'modal-title' => 'Client Application Token',
        'modal-token' => 'Please copy your new API token. For your security, it won\'t be shown again.',
        'modal-env' => 'If you need environmental variables for setting connection to WebPrint API, use following:',
        'title' => 'Generate API Token',
        'description' => 'Token will allow to connect to WebPrint API',
        'overwrite_modal' => [
            'button' => 'Regenerate API Token',
            'title' => 'This Client Application already has generated API Token',
            'content' => 'If you regenerate API Token, the old token will stop working.',
            'content_last_used' => 'Old token was last used :last_used.',
        ],
        'message' => 'For WebPrint API connection, you will need API Token. To begin, press following button.',
    ],
];
