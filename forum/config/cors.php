<?php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'registration/check', 'create/message', 'threads'],

    'allowed_methods' => ['*'],

    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*', 'categoryid'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,
];