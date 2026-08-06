<?php

return [

    'title' => 'Notifications',

    'index' => [
        'title'       => 'Notifications',
        'heading'     => 'Notifications',
        'subheading'  => 'View all system notifications.',
    ],

    'show' => [
        'title'                  => 'Notification Details',
        'heading'                => 'Notification Details',
        'subheading'             => 'Full notification information.',
        'back_to_list'           => 'Back to notifications list',
        'data_title'             => 'Notification Data',
        'info_title'             => 'Information',
        'id'                     => 'ID',
        'type'                   => 'Type',
        'notifiable'             => 'Notifiable',
        'read_at'                => 'Read At',
        'created_at'             => 'Created At',
        'updated_at'             => 'Updated At',
        'not_read'               => 'Not read',
    ],

    'filters' => [
        'type_label'             => 'Type',
        'type_placeholder'       => 'Filter by notification type',
        'read_label'             => 'Read Status',
        'read_all'               => 'All',
        'unread'                 => 'Unread',
        'read'                   => 'Read',
        'submit'                 => 'Filter',
        'reset'                  => 'Reset',
    ],

    'table' => [
        'type'           => 'Type',
        'message'        => 'Message',
        'received_at'    => 'Received At',
        'actions'        => 'Actions',
        'view'           => 'View',
        'empty'          => 'No notifications found.',
        'view_order'     => 'View Order',
        'view_design'    => 'View Design',
        
    ],

    'messages' => [
        'marked_read' => 'Notification marked as read.',
        'all_marked_read' => 'All notifications marked as read.',
    ],

    'mark_all_read' => 'Mark All as Read',
];
