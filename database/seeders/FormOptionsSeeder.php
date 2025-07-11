<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FormOption;

class FormOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Countries
        $countries = [
            ['key' => 'france', 'value' => 'France'],
            ['key' => 'allemagne', 'value' => 'Allemagne'],
            ['key' => 'belgique', 'value' => 'Belgique'],
            ['key' => 'suisse', 'value' => 'Suisse'],
            ['key' => 'italie', 'value' => 'Italie'],
            ['key' => 'espagne', 'value' => 'Espagne'],
            ['key' => 'pays-bas', 'value' => 'Pays-Bas'],
            ['key' => 'royaume-uni', 'value' => 'Royaume-Uni'],
            ['key' => 'autre', 'value' => 'Autre'],
        ];

        foreach ($countries as $index => $country) {
            FormOption::create([
                'category' => 'countries',
                'key' => $country['key'],
                'value' => $country['value'],
                'sort_order' => $index + 1,
            ]);
        }

        // Age groups
        $ageGroups = [
            ['key' => '0-17', 'value' => '0-17 ans'],
            ['key' => '18-25', 'value' => '18-25 ans'],
            ['key' => '26-35', 'value' => '26-35 ans'],
            ['key' => '36-50', 'value' => '36-50 ans'],
            ['key' => '51-65', 'value' => '51-65 ans'],
            ['key' => '65+', 'value' => '65+ ans'],
        ];

        foreach ($ageGroups as $index => $group) {
            FormOption::create([
                'category' => 'age_groups',
                'key' => $group['key'],
                'value' => $group['value'],
                'sort_order' => $index + 1,
            ]);
        }

        // General requests
        $generalRequests = [
            ['key' => 'randonnee', 'value' => 'Randonnée'],
            ['key' => 'hebergement', 'value' => 'Hébergement'],
            ['key' => 'restauration', 'value' => 'Restauration'],
            ['key' => 'activites-sportives', 'value' => 'Activités sportives'],
            ['key' => 'culture-patrimoine', 'value' => 'Culture et patrimoine'],
            ['key' => 'famille', 'value' => 'Activités famille'],
            ['key' => 'detente-bien-etre', 'value' => 'Détente et bien-être'],
            ['key' => 'gastronomie', 'value' => 'Gastronomie'],
            ['key' => 'shopping', 'value' => 'Shopping'],
            ['key' => 'transport', 'value' => 'Transport'],
            ['key' => 'demandes-habitants', 'value' => 'Demandes d\'habitants'],
        ];

        foreach ($generalRequests as $index => $request) {
            FormOption::create([
                'category' => 'general_requests',
                'key' => $request['key'],
                'value' => $request['value'],
                'sort_order' => $index + 1,
            ]);
        }

        // Specific requests by city (using existing data from City model)
        $citySpecificRequests = [
            'annot' => [
                ['key' => 'escalade', 'value' => 'Escalade'],
                ['key' => 'train-vapeur', 'value' => 'Train à Vapeur'],
                ['key' => 'gres-annot', 'value' => 'Grès d\'Annot'],
            ],
            'colmars-les-alpes' => [
                ['key' => 'lac-allos', 'value' => 'Lac d\'Allos'],
                ['key' => 'cascade-lance', 'value' => 'Cascade de la Lance'],
                ['key' => 'maison-musee', 'value' => 'Maison Musée'],
            ],
            'entrevaux' => [
                ['key' => 'nice', 'value' => 'Nice'],
                ['key' => 'cote-azur', 'value' => 'Côte d\'Azur'],
                ['key' => 'chemin-ronde', 'value' => 'Chemin de ronde'],
                ['key' => 'citadelle', 'value' => 'Citadelle'],
                ['key' => 'gorge-daluis', 'value' => 'Gorge de Daluis'],
                ['key' => 'train-vapeur', 'value' => 'Train à Vapeur'],
            ],
            'la-palud-sur-verdon' => [
                ['key' => 'blanc-martel', 'value' => 'Blanc-Martel'],
                ['key' => 'route-cretes', 'value' => 'Route des Crêtes'],
                ['key' => 'escalade-via-cordatta', 'value' => 'Escalade et via cordatta'],
            ],
            'saint-andre-les-alpes' => [
                ['key' => 'lac-castillon', 'value' => 'Lac de Castillon'],
                ['key' => 'parapente', 'value' => 'Parapente'],
            ],
        ];

        foreach ($citySpecificRequests as $citySlug => $requests) {
            foreach ($requests as $index => $request) {
                FormOption::create([
                    'category' => 'specific_requests',
                    'key' => $request['key'],
                    'value' => $request['value'],
                    'city_slug' => $citySlug,
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }
}
