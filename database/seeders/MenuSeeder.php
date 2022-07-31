<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;


class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Menu::create([
            'title' => 'home',
            'slug' => 'home',
            'order' => 1
        ]);
        Menu::create([
            'title' => 'about',
            'slug' => 'about',
            'order' => 2
            
        ]);
        Menu::create([
            'title' => 'services',
            'slug' => 'services',
            'order' => 3
        ]);
        Menu::create([
            'title' => 'cultural incubator',
            'slug' => 'cultural-incubator',
            'order' => 4
        ]);
        Menu::create([
            'title' => 'our hub',
            'slug' => 'our-hub',
            'order' => 5
        ]);
        Menu::create([
            'title' => 'clients',
            'slug' => 'clients',
            'order' => 6
        ]);
        Menu::create([
            'title' => 'contact',
            'slug' => 'contact',
            'order' => 7
        ]);

        Menu::create([
            'title' => 'art munchies',
            'slug' => 'art_munchies',
            'order' => 8,
            'parent_id' => 5,
        ]);
        Menu::create([
            'title' => 'private collections',
            'slug' => 'private_collections',
            'order' => 9,
            'parent_id' => 5,
        ]);
        Menu::create([
            'title' => 'explore',
            'slug' => 'explore',
            'order' => 10,
            'parent_id' => 5,
        ]);
        Menu::create([
            'title' => 'artists',
            'slug' => 'artists',
            'order' => 11,
            'parent_id' => 5,
        ]);
    }
}
