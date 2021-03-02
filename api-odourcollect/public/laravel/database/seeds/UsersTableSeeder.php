<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /* DB::table('users')->insert([
            'name' => 'Oriol',
            'surname' => 'Bach Rius',
            'username' => 'slingshot08',
            'email' => 'obach@100x100.net',
            'password' => \Hash::make('100x100'),
            'active' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]); */

        DB::table('users')->insert([
            'name' => 'Rosa',
            'surname' => 'Arias',
            'username' => 'rosaodour',
            'email' => 'rosa@odourcollect.com',
            'password' => \Hash::make('oddourcollect'),
            'active' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('users')->insert([
            'name' => 'Nora',
            'surname' => 'Salas',
            'username' => 'noraodour',
            'email' => 'nora@odourcollect.com',
            'password' => \Hash::make('oddourcollect'),
            'active' => 1,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        //factory(App\User::class, 15)->create();

    }
}
