<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

class OdorParentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('odor_parent_types')->insert([
            'name' => 'Waste',
            'slug' => 'waste',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_parent_types')->insert([
            'name' => 'Waste Water',
            'slug' => 'waste-water',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_parent_types')->insert([
            'name' => 'Agriculture / Livestock',
            'slug' => 'agriculture-livestock',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_parent_types')->insert([
            'name' => 'Food Industries',
            'slug' => 'food-industries',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_parent_types')->insert([
            'name' => 'Industrial',
            'slug' => 'insdustrial',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_parent_types')->insert([
            'name' => 'Urban',
            'slug' => 'urban',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

    }
}
