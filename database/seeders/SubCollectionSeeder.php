<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\Collection;
use App\Models\ArtWork;
use App\Models\CollectionArtwork;
use App\Models\Document;
use App\Models\SubCollection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $collection_ids = Collection::pluck('id');


        for ($i = 1; $i <= 10; $i++) {

            $subcollection = SubCollection::create(
                [
                    'image' => $faker->imageUrl,
                    'title' => $faker->name,
                    'collection_id' => 2,
                    'author_id' => '1'

                ]
            );

            $subcollection->save();
        }
    }
}
