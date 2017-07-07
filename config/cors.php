<?php

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel CORS
     |--------------------------------------------------------------------------
     |
     | allowedOrigins, allowedHeaders and allowedMethods can be set to array('*')
     | to accept any value.
     |
     */
    'supportsCredentials' => true,
    /*'allowedOrigins' => ['*'],
    'allowedHeaders' => ['*'],
    'allowedMethods' => ['*'],
    'exposedHeaders' => [],
    'maxAge' => 0,*/

    'allowedOrigins' => ['*'],
    'allowedMethods' => ['GET, POST, PATCH, PUT, DELETE, OPTIONS'],
    'allowedHeaders' => ['Origin', 'X-Requested-With', 'Content-Type', 'Accept', 'X-Auth-Token', 'X-CSRF-TOKEN'],
];

