<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(OAuthClientSecretSeeder::class);
        $this->call(SectionConfigurationSeeder::class);
        $this->call(BasicPageSeeder::class);
        $this->call(MenuSeeder::class);
        $this->call(CulturalIncubatorSeeder::class);
        $this->call(ArtistSeeder::class);
        $this->call(CollectionSeeder::class);
        $this->call(ArtWorkSeeder::class);
        $this->call(ArtMunchiesSeeder::class);

        // \App\Models\User::factory(10)->create();
    }
}
