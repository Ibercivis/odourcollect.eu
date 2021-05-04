<?php
return [ 
        
    'driver' => 'smtp',
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'encryption' => 'tls',
    'username' => 'odourcollect@ibercivis.es',
    'from' =>
    array (
     'address' => 'odourcollect@ibercivis.es',
     'name' => 'odourcollect',
    ),
    'password' => 'Y3bmR>Yu',
    'sendmail' => '/usr/sbin/sendmail -bs' 
];