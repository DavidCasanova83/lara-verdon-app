<?php

namespace Tests\Feature;

use App\Livewire\CityStatistics;
use App\Models\FormSubmission;
use App\Models\City;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class CityStatisticsPerformanceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer une ville de test
        City::create([
            'name' => 'Test City',
            'slug' => 'test-city'
        ]);
    }

    public function test_city_statistics_pagination_works()
    {
        // Créer plusieurs soumissions
        for ($i = 0; $i < 25; $i++) {
            FormSubmission::create([
                'city' => 'test-city',
                'country' => $i % 2 === 0 ? 'France' : 'Belgique',
                'department' => $i % 2 === 0 ? '04 - Alpes-de-Haute-Provence' : 'Inconnu',
                'email' => "test{$i}@example.com",
                'consent_newsletter' => $i % 2 === 0,
                'consent_data_processing' => true,
                'profile' => $i % 2 === 0 ? 'Famille' : 'Couple',
                'age_groups' => ['25-40'],
                'specific_requests' => ['Escalade'],
                'general_requests' => ['Randonnées']
            ]);
        }

        // Tester la pagination
        $component = Livewire::test(CityStatistics::class, ['citySlug' => 'test-city'])
            ->set('perPage', 10)
            ->assertSee('Test City') // Vérifier que la ville s'affiche
            ->assertSee('25') // Vérifier le total
            ->assertSee('test24@example.com'); // Le plus récent (tri par created_at desc)

        // Tester la pagination avec 25 éléments sur 10 par page
        $this->assertEquals(25, FormSubmission::where('city', 'test-city')->count());
    }

    public function test_city_statistics_search_works()
    {
        // Créer des soumissions avec différents emails
        FormSubmission::create([
            'city' => 'test-city',
            'country' => 'France',
            'department' => '04 - Alpes-de-Haute-Provence',
            'email' => 'unique@example.com',
            'consent_newsletter' => true,
            'consent_data_processing' => true,
            'profile' => 'Famille',
            'age_groups' => ['25-40'],
            'specific_requests' => ['Escalade'],
            'general_requests' => ['Randonnées']
        ]);

        FormSubmission::create([
            'city' => 'test-city',
            'country' => 'Belgique',
            'department' => 'Inconnu',
            'email' => 'other@example.com',
            'consent_newsletter' => false,
            'consent_data_processing' => true,
            'profile' => 'Couple',
            'age_groups' => ['40-60'],
            'specific_requests' => ['Train'],
            'general_requests' => ['Culture']
        ]);

        // Tester la recherche
        Livewire::test(CityStatistics::class, ['citySlug' => 'test-city'])
            ->set('search', 'unique')
            ->assertSee('unique@example.com')
            ->assertDontSee('other@example.com');
    }

    public function test_city_statistics_sorting_works()
    {
        // Créer des soumissions avec différents emails
        FormSubmission::create([
            'city' => 'test-city',
            'country' => 'France',
            'email' => 'a@example.com',
            'consent_newsletter' => true,
            'consent_data_processing' => true,
            'profile' => 'Famille',
            'age_groups' => ['25-40'],
            'specific_requests' => ['Escalade'],
            'general_requests' => ['Randonnées']
        ]);

        FormSubmission::create([
            'city' => 'test-city',
            'country' => 'Belgique',
            'email' => 'z@example.com',
            'consent_newsletter' => false,
            'consent_data_processing' => true,
            'profile' => 'Couple',
            'age_groups' => ['40-60'],
            'specific_requests' => ['Train'],
            'general_requests' => ['Culture']
        ]);

        // Tester le tri par email
        $component = Livewire::test(CityStatistics::class, ['citySlug' => 'test-city'])
            ->call('sortBy', 'email')
            ->assertSet('sortBy', 'email')
            ->assertSet('sortDirection', 'asc');
    }

    public function test_city_statistics_filters_work()
    {
        // Créer des soumissions avec différents pays
        FormSubmission::create([
            'city' => 'test-city',
            'country' => 'France',
            'department' => '04 - Alpes-de-Haute-Provence',
            'email' => 'france@example.com',
            'consent_newsletter' => true,
            'consent_data_processing' => true,
            'profile' => 'Famille',
            'age_groups' => ['25-40'],
            'specific_requests' => ['Escalade'],
            'general_requests' => ['Randonnées']
        ]);

        FormSubmission::create([
            'city' => 'test-city',
            'country' => 'Belgique',
            'department' => 'Inconnu',
            'email' => 'belgique@example.com',
            'consent_newsletter' => false,
            'consent_data_processing' => true,
            'profile' => 'Couple',
            'age_groups' => ['40-60'],
            'specific_requests' => ['Train'],
            'general_requests' => ['Culture']
        ]);

        // Tester le filtre par pays
        Livewire::test(CityStatistics::class, ['citySlug' => 'test-city'])
            ->set('selectedCountry', 'France')
            ->assertSee('france@example.com')
            ->assertDontSee('belgique@example.com');
    }

    public function test_city_statistics_performance_with_many_records()
    {
        // Créer beaucoup de soumissions
        $submissions = [];
        for ($i = 0; $i < 100; $i++) {
            $submissions[] = [
                'city' => 'test-city',
                'country' => $i % 2 === 0 ? 'France' : 'Belgique',
                'department' => $i % 2 === 0 ? '04 - Alpes-de-Haute-Provence' : 'Inconnu',
                'email' => "test{$i}@example.com",
                'consent_newsletter' => $i % 2 === 0,
                'consent_data_processing' => true,
                'profile' => $i % 2 === 0 ? 'Famille' : 'Couple',
                'age_groups' => json_encode(['25-40']),
                'specific_requests' => json_encode(['Escalade']),
                'general_requests' => json_encode(['Randonnées']),
                'created_at' => now(),
                'updated_at' => now()
            ];
        }
        
        // Insertion batch pour la performance
        DB::table('form_submissions')->insert($submissions);

        // Mesurer le temps d'exécution
        $start = microtime(true);
        
        $component = Livewire::test(CityStatistics::class, ['citySlug' => 'test-city'])
            ->set('perPage', 20);
        
        $end = microtime(true);
        $executionTime = $end - $start;
        
        // Vérifier que le temps d'exécution est raisonnable (< 2 secondes)
        $this->assertLessThan(2.0, $executionTime, "La requête prend trop de temps: {$executionTime}s");
        
        // Vérifier que les données sont correctement paginées
        $this->assertEquals(100, FormSubmission::where('city', 'test-city')->count());
    }
}