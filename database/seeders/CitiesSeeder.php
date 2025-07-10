<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\City;

class CitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            [
                'name' => 'La Palud-sur-Verdon',
                'slug' => 'la-palud-sur-verdon',
                'image' => 'la-palud-sur-verdon.jpg'
            ],
            [
                'name' => 'Saint-André-les-Alpes',
                'slug' => 'saint-andre-les-alpes',
                'image' => 'saint-andre-les-alpes.jpg'
            ],
            [
                'name' => 'Colmars-les-Alpes',
                'slug' => 'colmars-les-alpes',
                'image' => 'colmars-les-alpes.jpg'
            ],
            [
                'name' => 'Entrevaux',
                'slug' => 'entrevaux',
                'image' => 'entrevaux.jpg'
            ],
            [
                'name' => 'Annot',
                'slug' => 'annot',
                'image' => 'annot.jpg'
            ]
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}
