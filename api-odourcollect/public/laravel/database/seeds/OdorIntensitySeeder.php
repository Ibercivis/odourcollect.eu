<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

class OdorIntensitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('odor_intensities')->insert([
            'power' => 0,
            'name' => "Not perceptible",
            'slug' => 'not-perceptible',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_intensities')->insert([
            'power' => 1,
            'name' => "Very weak",
            'slug' => 'very-weak',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_intensities')->insert([
            'power' => 2,
            'name' => "Weak",
            'slug' => 'weak',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_intensities')->insert([
            'power' => 3,
            'name' => "Distinct",
            'slug' => 'distinct',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_intensities')->insert([
            'power' => 4,
            'name' => "Strong",
            'slug' => 'strong',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_intensities')->insert([
            'power' => 5,
            'name' => "Very strong",
            'slug' => 'very-strong',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_intensities')->insert([
            'power' => 6,
            'name' => "Extremely strong",
            'slug' => 'extremely-strong',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
