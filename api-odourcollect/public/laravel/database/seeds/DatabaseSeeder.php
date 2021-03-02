<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->call(AdminsTableSeeder::class);
        //$this->call(UsersTableSeeder::class);
        //$this->call(LikeTypeTableSeeder::class);
		
		//$this->call(OdorAnnoySeeder::class); 
		//$this->call(OdorDurationSeeder::class); 
		//$this->call(OdorIntensitySeeder::class); 
		//$this->call(OdorParentTypeSeeder::class);
        //$this->call(OdorTypeSeeder::class);  
        //$this->call(OdorSeeder::class);
        $this->call(InterestSeeder::class);        
    }
}
