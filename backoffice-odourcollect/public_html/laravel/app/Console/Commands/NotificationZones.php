<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotificationEmail;
use Illuminate\Support\Facades\DB;
use App\NotificationEmailModel;
use App\NotificationZone;
use App\NotificationZoneOdourType;
use Carbon\Carbon;

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
    protected $description = 'Zone Admin - Email notification';

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
        $notificationZones = DB::table('notification_zones')->get();

        foreach ($notificationZones as $notificationZone){
            $odourTypes = NotificationZoneOdourType::where('id_notification_zone', $notificationZone->id)->select("id_odour_type")->get();

            $odours = DB::table('odors')        
            ->join('odor_zones', 'odor_zones.id_odor', '=', 'odors.id')
            ->join('odor_types','odors.id_odor_type','=','odor_types.id')
            ->where('odor_zones.id_zone', $notificationZone->zone_id)
            ->whereIn('id_odor_parent_type', $odourTypes)        
            ->where('id_odor_intensity', '>=', ($notificationZone->min_intensity + 1)) //id=1 power=0
            ->where('id_odor_intensity', '<=', ($notificationZone->max_intensity + 1)) 
            ->where('id_odor_annoy', '>=', ($notificationZone->min_hedonic_tone + 5)) //id=1 index=-4
            ->where('id_odor_annoy', '<=', ($notificationZone->max_hedonic_tone + 5))
            ->whereNull('odors.deleted_at')
            ->where('odors.created_at', '>', Carbon::now()->subHours($notificationZone->hours)->toDateTimeString() )
            ->where('status', '=', "published")
            ->where('odors.verified', '=', 1)
            ->get();


            if (count($odours) >= $notificationZone->number_observations){
                $zoneAdmins = array();
            
                $users = DB::table('users')->get();
                foreach ($users as $user){            
                    $user->belong = false;
                    $belong_zone = DB::table('user_zones')->where('id_user', $user->id)->where('id_zone', $notificationZone->zone_id)->orderBy('id', 'desc')->first();
                    if ($belong_zone){
                        if ($belong_zone->deleted_at == NULL){
                            array_push($zoneAdmins, $user->email);
                        }
                    }                        
                }

                $subject = 'Zone notification';
                $body = $odours;
                
                $email_to_user = new NotificationEmailModel();
                $email_to_user->zone_id = $notificationZone->zone_id;
                $email_to_user->subject = $subject;
                $email_to_user->body = $body;
                
                Mail::to($zoneAdmins)->send(new NotificationEmail($email_to_user));
            }
        }



        return redirect()->back()->withInput()->withErrors(['success']);

    }
}
