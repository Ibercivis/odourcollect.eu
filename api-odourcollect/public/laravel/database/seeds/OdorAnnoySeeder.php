<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

class OdorAnnoySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('odor_annoys')->insert([
            'index' => -4,
            'name' => "Extremely unpleasant",
            'slug' => 'extremely-unpleasant',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_annoys')->insert([
            'index' => -3,
            'name' => "Moderate unpleasant",
            'slug' => 'moderate-unpleasant',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_annoys')->insert([
            'index' => -2,
            'name' => "Unpleasant",
            'slug' => 'unpleasant',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_annoys')->insert([
            'index' => -1,
            'name' => "Slightly unpleasant",
            'slug' => 'slightly-unpleasant',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_annoys')->insert([
            'index' => 0,
            'name' => "Neutral",
            'slug' => 'neutral',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_annoys')->insert([
            'index' => 1,
            'name' => "Slightly pleasant",
            'slug' => 'slightly-pleasant',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_annoys')->insert([
            'index' => 2,
            'name' => "Pleasant",
            'slug' => 'pleasant',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_annoys')->insert([
            'index' => 3,
            'name' => "Moderate pleasant",
            'slug' => 'moderate-pleasant',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_annoys')->insert([
            'index' => 4,
            'name' => "Extremely pleasan",
            'slug' => 'extremely-pleasan',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

    }
}
