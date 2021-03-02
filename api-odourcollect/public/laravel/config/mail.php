<?php
return [ 
        
        'driver' => 'smtp',
        
        'host' => 'smtp.sendgrid.net',
        
        'port' => 587,
        
            'encryption' => 'tls', 
        
        'username' => 'apikey',
        
        'from' => ['address' => env('MAIL_FROM_ADDRESS'), 'name' => 'odourcollect'],
        
        'password' => env('MAIL_PASSWORD'),
        
        'sendmail' => '/usr/sbin/sendmail -bs' 
];