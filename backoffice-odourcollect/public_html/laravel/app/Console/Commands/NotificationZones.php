<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationEmail;
use Illuminate\Support\Facades\DB;
use App\Email;

class NotificationZones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:zones';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $totalUsers = DB::table('users')
        ->whereRaw('Date(created_at) = CURDATE()')
        ->count();


        $subject = 'notification';
        $body = 'total new users today: '.$totalUsers;

        $email = 'vval@bifi.es';
        $email_to_user = new Email();
        $email_to_user->id_user = '3';//$user->id;
        $email_to_user->email = $email;
        $email_to_user->subject = $subject;
        $email_to_user->body = $body;
        //$email_to_user->save();
        Mail::to("vval@bifi.es")->send(new NotificationEmail($email_to_user));
        return redirect()->back()->withInput()->withErrors(['success']);

    }
}
