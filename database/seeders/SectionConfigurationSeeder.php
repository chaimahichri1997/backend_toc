<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SectionConfiguration;


class SectionConfigurationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ///////////////////// Create section configuration ////////////////
        // Slider //
        SectionConfiguration::create([
            'name' => 'slider-text',
            'type' => 'slider',
            'fields' => ['first_title','second_title','last_title']
        ]);
        SectionConfiguration::create([
            'name' => 'slider-media',
            'type' => 'slider',
            'fields' => ['media']
        ]);
        // image-left-text-right //
        SectionConfiguration::create([
            'name' => 'image-left-text-right',
            'type' => 'image-left-text-right',
            'fields' => ['title','subtitle','media','description']
        ]);
        // slogan //
        SectionConfiguration::create([
            'name' => 'slogan',
            'type' => 'slogan',
            'fields' => ['title','subtitle','name']
        ]);
        // number-text-left-image-right //
        SectionConfiguration::create([
            'name' => 'number-text-left-image-right',
            'type' => 'services',
            'fields' => ['title','subtitle','description','media']
        ]);
        // image-left-number-text-right //
        SectionConfiguration::create([
            'name' => 'image-left-number-text-right',
            'type' => 'services',
            'fields' => ['title','subtitle','description','media']
        ]);
        // contact //
        SectionConfiguration::create([
            'name' => 'contact',
            'type' => 'contact',
            'fields' => ['title','description_left','description_right']
        ]);
        // text-left-image-right //
        SectionConfiguration::create([
            'name' => 'text-left-image-right',
            'type' => 'text-left-image-right',
            'fields' => ['title','subtitle','description','media']
        ]);
        // description-left-image-name-right //
        SectionConfiguration::create([
            'name' => 'description-left-image-name-right',
            'type' => 'founders',
            'fields' => ['description','media','title','subtitle']
        ]);
        // name-image-left-description-right //
        SectionConfiguration::create([
            'name' => 'name-image-left-description-right',
            'type' => 'founders',
            'fields' => ['title','subtitle','description','media']
        ]);
        // clients //
        SectionConfiguration::create([
            'name' => 'clients',
            'type' => 'clients',
            'fields' => ['name']
        ]);
    }
}
