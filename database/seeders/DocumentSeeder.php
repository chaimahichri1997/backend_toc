<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\Collection;
use App\Models\ArtWork;
use App\Models\CollectionArtwork;
use App\Models\Document;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $artwork_ids = ArtWork::pluck('id');


        for ($i = 1; $i <= 10; $i++) {

            $document = Document::create(
                [
                    'image' => $faker->imageUrl,
                    'title' => $faker->name,
                    'artwork_id' => $faker->randomElement(json_decode(json_encode($artwork_ids), TRUE)),
                    'author_id' => '1'

                ]
            );

            $document->save();
        }
    }
}
