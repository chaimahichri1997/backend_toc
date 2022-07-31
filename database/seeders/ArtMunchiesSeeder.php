<?php

namespace Database\Seeders;

use App\Models\ArtMunchie;
use Illuminate\Database\Seeder;

class ArtMunchiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $image_art_1 = 'http://192.168.80.107:8000/images/art_munchie_1.png';
        $image_art_2 = 'http://192.168.80.107:8000/images/art_munchie_2.png';
        $image_art_3 = 'http://192.168.80.107:8000/images/art_munchie_3.png';

        ArtMunchie::create([
            'title' => 'QUOZ ARTS FEST BY ALSERKAL TO RETURN IN JANUARY',
            'body' => 'Sed fermentum, massa laoreet porta euismod, lorem justo varius elit, quis viverra nisi justo placerat ex. Aenean blandit tortor augue, non accumsan leo tincidunt feugiat. Duis eget nibh et dolor sagittis finibus. Donec suscipit hendrerit augue, vitae pulvinar leo hendrerit sed. Proin id erat tempus, pellentesque velit eget, molestie augue. Aliquam non odio sollicitudin.',
            'url' => 'www.google.com',
            'image' => $image_art_1,
            'date' => $faker->date('Y-m-d'),
            'category' => 'category1',
            'region' => 'region1',
        ]);
        ArtMunchie::create([
            'title' => 'QUOZ ARTS FEST BY ALSERKAL TO RETURN IN JANUARY',
            'body' => 'Sed fermentum, massa laoreet porta euismod, lorem justo varius elit, quis viverra nisi justo placerat ex. Aenean blandit tortor augue, non accumsan leo tincidunt feugiat. Duis eget nibh et dolor sagittis finibus. Donec suscipit hendrerit augue, vitae pulvinar leo hendrerit sed. Proin id erat tempus, pellentesque velit eget, molestie augue. Aliquam non odio sollicitudin.',
            'url' => 'www.google.com',
            'image' => $image_art_2 ,
            'date' => $faker->date('Y-m-d'),
            'category' => 'category2',
            'region' => 'region2',
        ]);
        ArtMunchie::create([
           'title' => 'QUOZ ARTS FEST BY ALSERKAL TO RETURN IN JANUARY',
           'body' => 'Sed fermentum, massa laoreet porta euismod, lorem justo varius elit, quis viverra nisi justo placerat ex. Aenean blandit tortor augue, non accumsan leo tincidunt feugiat. Duis eget nibh et dolor sagittis finibus. Donec suscipit hendrerit augue, vitae pulvinar leo hendrerit sed. Proin id erat tempus, pellentesque velit eget, molestie augue. Aliquam non odio sollicitudin.',
           'url' => 'www.google.com',
            'image' => $image_art_3,
            'date' => $faker->date('Y-m-d'),
            'category' => 'category3',
            'region' => 'region3',
        ]);
        ArtMunchie::create([
           'title' => 'QUOZ ARTS FEST BY ALSERKAL TO RETURN IN JANUARY',
           'body' => 'Sed fermentum, massa laoreet porta euismod, lorem justo varius elit, quis viverra nisi justo placerat ex. Aenean blandit tortor augue, non accumsan leo tincidunt feugiat. Duis eget nibh et dolor sagittis finibus. Donec suscipit hendrerit augue, vitae pulvinar leo hendrerit sed. Proin id erat tempus, pellentesque velit eget, molestie augue. Aliquam non odio sollicitudin.',
           'url' => 'www.google.com',
            'image' => $image_art_1,
            'date' => $faker->date('Y-m-d'),
            'category' => 'category4',
            'region' => 'region4',
        ]);
        ArtMunchie::create([
           'title' => 'QUOZ ARTS FEST BY ALSERKAL TO RETURN IN JANUARY',
           'body' => 'Sed fermentum, massa laoreet porta euismod, lorem justo varius elit, quis viverra nisi justo placerat ex. Aenean blandit tortor augue, non accumsan leo tincidunt feugiat. Duis eget nibh et dolor sagittis finibus. Donec suscipit hendrerit augue, vitae pulvinar leo hendrerit sed. Proin id erat tempus, pellentesque velit eget, molestie augue. Aliquam non odio sollicitudin.',
           'url' => 'www.google.com',
            'image' => $image_art_2,
            'date' => $faker->date('Y-m-d'),
            'category' => 'category5',
            'region' => 'region5',
        ]);
       
    }
}
