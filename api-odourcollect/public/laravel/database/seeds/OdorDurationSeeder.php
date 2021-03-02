<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

class OdorDurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('odor_durations')->insert([
            'duration' => 1,
            'name' => "1 Minute",
            'slug' => 'one-minute',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_durations')->insert([
            'duration' => 5,
            'name' => "1 to 5 Minutes",
            'slug' => 'one-to-five-minutes',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_durations')->insert([
            'duration' => 10,
            'name' => "More than 10 Minutes",
            'slug' => 'more-than-ten-minutes',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

    }
}
