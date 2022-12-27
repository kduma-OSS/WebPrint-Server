<?php

return [
    'dialog-status' => [
        'cancelled' => [
            'title' => 'Print job is Canceled',
            'description' => 'This print job was cancelled.',
        ],
        'sent' => [
            'title' => 'Print job is already sent for printing',
            'description' => 'This print job has already been sent to printer.',
        ],
        'new' => [
            'title' => 'Print job not active',
            'description' => 'This print job is not active for printing. It has already been sent to printer or cancelled.',
        ],
    ],
    'print-job-status-label' => 'Status:',
    'job-status' => [
        'new' => 'Waiting in print queue',
        'printing' => 'Currently processing',
        'finished' => 'Job sent to printer',
        'failed' => 'Printing Failed',
    ],
    'go-back-button' => 'Go Back',
    'printer-label' => 'Printer',
    'select-printer-placeholder' => 'Select Printer',
    'no-printers-available-message' => 'No printers available',
    'job-metadate-label' => 'Job Metadata',
    'job-metadata-key-label' => 'Key',
    'job-metadata-value-label' => 'Value',
    'printer-settings-label' => 'Printer Settings',
    'customize-print-options-button' => 'Customize Print Options',
    'printer-defaults-placeholder' => 'Printer Defaults',
    'cancel-button' => 'Cancel',
    'print-button' => 'Print',
    'errors' => [
        'ip-error' => [
            'title' => 'IP Restriction Mismatch',
            'message_1' => 'This print job is restricted to specific IP address.',
            'message_2' => 'Your IP address (:ip) is not permitted.',
        ],
    ],
    'page-heading' => 'Print:',
];
