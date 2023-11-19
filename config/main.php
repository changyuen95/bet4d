<?php

return [

    'admin' => env('APP_ADMIN_URL', 'http://admin.localhost'),
    'main_url' => env('APP_URL', null),
    'strict_attendance' => false,
    'admin_email'=>env('MAIL_RECEIVER',null),

];
