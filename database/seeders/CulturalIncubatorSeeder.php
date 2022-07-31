<?php

namespace Database\Seeders;

use App\Models\CulturalIncubator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Helpers\Helper;

class CulturalIncubatorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $image = env('APP_URL').'/'.'images/incubator.png';

        for ($i = 1; $i <= 5; $i++) {

            $list_cultural_incubator = CulturalIncubator::create(
                [
                    'title' => $faker->text(10),
                    'subtitle' => $faker->text(10),
                    'description' => $faker->paragraph,
                    'start_date' => $faker->dateTimeBetween('-5 years', 'now'),
                    'end_date' => $faker->dateTimeBetween('now', '+5 years'),
                    'images' => $image,
                    'status' => '0'
                ]
            );
            $list_cultural_incubator['slug']= Helper::URLSlug($list_cultural_incubator['title']);

            $list_cultural_incubator->save();
        }

    }
}
