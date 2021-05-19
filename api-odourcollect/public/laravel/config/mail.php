<?php
return [ 
        
        'driver' => 'smtp',
        
        'host' => env('MAIL_HOST'),
        
        'port' => 587,
        
        'encryption' => 'tls', 
        
        'username' => env('MAIL_USERNAME'),
        
        'from' => ['address' => env('MAIL_FROM_ADDRESS'), 'name' => env('MAIL_FROM_NAME')],
        
        'password' => env('MAIL_PASSWORD'),
        
        'sendmail' => '/usr/sbin/sendmail -bs' 
];
