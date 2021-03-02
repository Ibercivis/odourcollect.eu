<?php

use Illuminate\Database\Seeder;

use Carbon\Carbon;

class OdorTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 1,
            'name' => 'Fresh waste',
            'slug' => 'fresh-waste',
            'icon' => 'waste-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 1,
            'name' => 'Decomposed waste',
            'slug' => 'decomposed-waste',
            'icon' => 'waste-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 1,
            'name' => 'Leachate',
            'slug' => 'leachate',
            'icon' => 'waste-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 1,
            'name' => 'Biogas',
            'slug' => 'biogas',
            'icon' => 'waste-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 1,
            'name' => 'Biofilter',
            'slug' => 'biofilter',
            'icon' => 'waste-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 1,
            'name' => 'Ammonia',
            'slug' => 'ammonia',
            'icon' => 'waste-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 1,
            'name' => 'Amines',
            'slug' => 'amines',
            'icon' => 'waste-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 1,
            'name' => 'Other',
            'slug' => 'other-waste',
            'icon' => 'waste-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 1,
            'name' => "I don't know",
            'slug' => "i-dont-know-waste",
            'icon' => 'waste-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 2,
            'name' => 'Waste water',
            'slug' => 'waste-water',
            'icon' => 'water-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 2,
            'name' => 'Rotten eggs',
            'slug' => 'rotten-eggs',
            'icon' => 'water-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 2,
            'name' => 'Sludge',
            'slug' => 'sludge',
            'icon' => 'water-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 2,
            'name' => 'Chlorine',
            'slug' => 'chlorine',
            'icon' => 'water-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 2,
            'name' => 'Other',
            'slug' => 'other-waste-water',
            'icon' => 'water-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 2,
            'name' => "I don't know",
            'slug' => "i-dont-know-waste-water",
            'icon' => 'water-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 3,
            'name' => 'Dead animal',
            'slug' => 'dead-animal',
            'icon' => 'agriculture-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 3,
            'name' => 'Cooked meat',
            'slug' => 'cooked-meat',
            'icon' => 'agriculture-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 3,
            'name' => 'Organic fertilizers (manure/slurry)',
            'slug' => 'organic-fertilizers',
            'icon' => 'agriculture-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 3,
            'name' => 'Animal feed',
            'slug' => 'animal-feed',
            'icon' => 'agriculture-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 3,
            'name' => 'Cabbage soup',
            'slug' => 'cabbage-soup',
            'icon' => 'agriculture-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 3,
            'name' => 'Rotten eggs',
            'slug' => 'rotten-eggs-agriculture',
            'icon' => 'agriculture-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 3,
            'name' => 'Ammonia',
            'slug' => 'ammonia-agriculture',
            'icon' => 'agriculture-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 3,
            'name' => 'Amines',
            'slug' => 'amines-agriculture',
            'icon' => 'agriculture-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 3,
            'name' => 'Other',
            'slug' => 'other-agriculture',
            'icon' => 'agriculture-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 3,
            'name' => "I don't know",
            'slug' => "i-dont-know-agriculture",
            'icon' => 'agriculture-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 4,
            'name' => 'Fat / Oil',
            'slug' => 'fat-oil',
            'icon' => 'food-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 4,
            'name' => 'Coffee',
            'slug' => 'coffee',
            'icon' => 'food-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 4,
            'name' => 'Cocoa',
            'slug' => 'cocoa',
            'icon' => 'food-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 4,
            'name' => 'Milk / Dairy',
            'slug' => 'milk-dairy',
            'icon' => 'food-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 4,
            'name' => 'Animal food',
            'slug' => 'animal-food',
            'icon' => 'food-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 4,
            'name' => 'Ammonia',
            'slug' => 'ammonia-food',
            'icon' => 'food-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 4,
            'name' => 'Malt / Hop',
            'slug' => 'malt-hop',
            'icon' => 'food-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 4,
            'name' => 'Fish',
            'slug' => 'fish',
            'icon' => 'food-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 4,
            'name' => 'Bakeries',
            'slug' => 'bakeries',
            'icon' => 'food-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 4,
            'name' => 'Raw meat',
            'slug' => 'raw-meat',
            'icon' => 'food-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 4,
            'name' => 'Ammines',
            'slug' => 'ammines-food',
            'icon' => 'food-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 4,
            'name' => 'Cabbage soup',
            'slug' => 'cabbage-soup-food',
            'icon' => 'food-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 4,
            'name' => 'Rotten eggs',
            'slug' => 'rotten-eggs-food',
            'icon' => 'food-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 4,
            'name' => 'Bread / Cookies',
            'slug' => 'bread-cookies',
            'icon' => 'food-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 4,
            'name' => 'Alcohol',
            'slug' => 'alcohol',
            'icon' => 'food-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 4,
            'name' => 'Aroma / Flavour',
            'slug' => 'aroma-flavour',
            'icon' => 'food-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 4,
            'name' => 'Other',
            'slug' => 'other-food',
            'icon' => 'food-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 4,
            'name' => "I don't know",
            'slug' => "i-dont-know-food",
            'icon' => 'food-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 5,
            'name' => 'Cabbage soup',
            'slug' => 'cabbage-soup-industrial',
            'icon' => 'industry-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 5,
            'name' => 'Oil / Petrochemical',
            'slug' => 'oil-petrochemical',
            'icon' => 'industry-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 5,
            'name' => 'Gas',
            'slug' => 'gas',
            'icon' => 'industry-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 5,
            'name' => 'Asphalt / Rubber',
            'slug' => 'asphalt-rubber',
            'icon' => 'industry-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 5,
            'name' => 'Chemical',
            'slug' => 'chemical',
            'icon' => 'industry-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 5,
            'name' => 'Ammonia',
            'slug' => 'ammonia-industrial',
            'icon' => 'industry-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 5,
            'name' => 'Leather',
            'slug' => 'leather',
            'icon' => 'industry-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 5,
            'name' => 'Metal',
            'slug' => 'metal',
            'icon' => 'industry-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 5,
            'name' => 'Plastic',
            'slug' => 'plastic',
            'icon' => 'industry-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 5,
            'name' => 'Sulphur',
            'slug' => 'sulphur',
            'icon' => 'industry-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 5,
            'name' => 'Alcohol',
            'slug' => 'alcohol-industrial',
            'icon' => 'industry-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 5,
            'name' => 'Ketone / Ester / Acetate / Ether',
            'slug' => 'ketone-ester-acetate-ether',
            'icon' => 'industry-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 5,
            'name' => 'Amines',
            'slug' => 'amines-industrial',
            'icon' => 'industry-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 5,
            'name' => 'Glue / Adhesive',
            'slug' => 'glue-adhesive',
            'icon' => 'industry-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 6,
            'name' => 'Urine',
            'slug' => 'urine',
            'icon' => 'urban-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 6,
            'name' => 'Traffic',
            'slug' => 'traffic',
            'icon' => 'urban-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 6,
            'name' => 'Sewage',
            'slug' => 'sewage',
            'icon' => 'urban-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 6,
            'name' => 'Waste bin',
            'slug' => 'waste-bin',
            'icon' => 'urban-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);

        DB::table('odor_types')->insert([
            'id_odor_parent_type' => 6,
            'name' => 'Waste truck',
            'slug' => 'waste-truck',
            'icon' => 'urban-spot.png',
            'color' => '#000000',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
        
    }
}
