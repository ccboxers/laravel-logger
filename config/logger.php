<?php
return [
    /**
     * 登录用户
     */
    "users" => [
        [
            'name' => env('LOGGER_USERS_NAME', 'admin'),
            'email' => env('LOGGER_USERS_EMAIL', 'admin@admin.com'),
            'password' => env('LOGGER_USERS_PASSWORD', 'admin123456'),
        ],
    ],

    /**
     * 是否启用文件日志
     */
    "file" => true,

    /**
     * 文件日志渠道
     */
    "channels" => ["single"],

    /**
     * 是否启用数据库日志
     */
    "database" => false,

    /**
     * 数据库日志是否真删除
     */
    "delete" => false,

    /**
     * 每页显示条数
     */
    "limit" => 25,
];
