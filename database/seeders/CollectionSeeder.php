<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('collections')->delete();

        $faker = \Faker\Factory::create();

        for ($i = 1; $i <= 4; $i++) {

            $collections = Collection::create(
                [
                    'name' => $faker->name,
                    'tags' => $faker->name,
                    'image' => $faker->imageUrl,
                    'author_id' => '1',
                    'status' => 'private',
                    'favorite'=>'no'

                ]
            );

            $collections->save();
        }
    }
}
