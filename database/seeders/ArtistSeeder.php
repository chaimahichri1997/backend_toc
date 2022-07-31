<?php

namespace Database\Seeders;

use App\Helpers\Helper;
use App\Models\Artist;
use App\Models\CulturalIncubator;
use Illuminate\Database\Seeder;

class ArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        for ($i = 1; $i <= 100; $i++) {

            $artist = Artist::create(
                [
                    'name' => $faker->name,
                    'type' => $faker->jobTitle,
                    'region' => $faker->century,
                    'image' => $faker->imageUrl,
                    'born_year' => $faker->date('Y-m-d'),
                    'death_year' => $faker->date('Y-m-d'),
                    'born_location' => $faker->city,
                    'about_artist' => $faker->paragraph,
                    'website' => 'www.google.com',
                    'quote' => $faker->paragraph,
                    'quote_by' => $faker->paragraph,
                    'represented_by' => $faker->paragraph,
                    'collections' => '',
                    'major_shows' => $faker->paragraph,
                    'author_id' => '1',
                    'status' => 'private',

                ]
            );

            $artist->save();
        }
    }
}
