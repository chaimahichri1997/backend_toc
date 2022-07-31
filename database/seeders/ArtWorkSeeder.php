<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\Collection;
use App\Models\ArtWork;
use App\Models\CollectionArtwork;
use App\Models\Document;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ArtWorkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $artist_ids = Artist::pluck('id');


        for ($i = 1; $i <= 10; $i++) {

            $artWork = ArtWork::create(
                [
                    'image' => $faker->imageUrl,
                    'title' => $faker->name,
                    'reference' => $faker->name,
                    'date' => $faker->randomElement(['Circa 1989', 'executed in 1989', 'Printed in 2021']),
                    'category' => $faker->name,
                    'medium' => $faker->name,
                    'dimensions' => $faker->name,
                    'edition' => $faker->name,
                    'provenance' => $faker->name,
                    'height' => $faker->randomDigit,
                    'width' => $faker->randomDigit,
                    'depth' => $faker->randomDigit,
                    'framing' => $faker->randomElement(['Unframed', 'Framed']),
                    'makers_marks' => $faker->name,
                    'production_place' => $faker->address,
                    'description' => $faker->paragraph,
                    'status' => $faker->randomElement([ArtWork::AVAILABLE, ArtWork::ONLOAN, ArtWork::SOLD]),
                    'artist_id' => $faker->randomElement(json_decode(json_encode($artist_ids), TRUE)),
                    'InExplore' => "false",
                    'price' => "100",
                    'author_id' => '1',
                    'InBasket' => "false",
                    'etat' => "private"
                ]
            );

            $artWork->save();
        }

        $collections = Collection::paginate(5);
        $artworks = ArtWork::paginate(5);

        DB::table('collection_artworks')->delete();

        if ($artworks) {
            foreach ($collections as $collection) {
                foreach ($artworks as $artwork) {
                    CollectionArtwork::firstOrCreate([
                        'collection_id' => $collection->id,
                        'artwork_id' => $artwork->id,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }
            }
        }
    }
}
