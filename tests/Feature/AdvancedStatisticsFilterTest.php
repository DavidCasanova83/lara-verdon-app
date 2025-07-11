<?php

namespace Tests\Feature;

use App\Livewire\AdvancedStatistics;
use App\Models\FormSubmission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdvancedStatisticsFilterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_filters_submissions_by_city()
    {
        // Créer des données de test
        FormSubmission::create([
            'city' => 'annot',
            'country' => 'France',
            'department' => '04 - Alpes-de-Haute-Provence',
            'email' => 'test1@example.com',
            'consent_newsletter' => true,
            'consent_data_processing' => true,
            'profile' => 'Famille',
            'age_groups' => ['25-40'],
            'specific_requests' => ['Escalade'],
            'general_requests' => ['Randonnées']
        ]);

        FormSubmission::create([
            'city' => 'entrevaux',
            'country' => 'Belgique',
            'department' => 'Inconnu',
            'email' => 'test2@example.com',
            'consent_newsletter' => false,
            'consent_data_processing' => true,
            'profile' => 'Couple',
            'age_groups' => ['40-60'],
            'specific_requests' => ['Train à Vapeur'],
            'general_requests' => ['Patrimoine culturel']
        ]);

        // Tester le composant
        Livewire::test(AdvancedStatistics::class)
            ->assertSee('2') // Total count initial
            ->set('selectedCity', 'annot')
            ->assertSee('1') // Après filtrage par ville
            ->assertDispatched('refresh-charts'); // Vérifier que l'événement est déclenché
    }

    /** @test */
    public function it_filters_submissions_by_country()
    {
        // Créer des données de test
        FormSubmission::create([
            'city' => 'annot',
            'country' => 'France',
            'department' => '04 - Alpes-de-Haute-Provence',
            'email' => 'test1@example.com',
            'consent_newsletter' => true,
            'consent_data_processing' => true,
            'profile' => 'Famille',
            'age_groups' => ['25-40'],
            'specific_requests' => ['Escalade'],
            'general_requests' => ['Randonnées']
        ]);

        FormSubmission::create([
            'city' => 'entrevaux',
            'country' => 'Belgique',
            'department' => 'Inconnu',
            'email' => 'test2@example.com',
            'consent_newsletter' => false,
            'consent_data_processing' => true,
            'profile' => 'Couple',
            'age_groups' => ['40-60'],
            'specific_requests' => ['Train à Vapeur'],
            'general_requests' => ['Patrimoine culturel']
        ]);

        // Tester le composant
        Livewire::test(AdvancedStatistics::class)
            ->assertSee('2') // Total count initial
            ->set('selectedCountry', 'France')
            ->assertSee('1') // Après filtrage par pays
            ->assertDispatched('refresh-charts'); // Vérifier que l'événement est déclenché
    }

    /** @test */
    public function it_resets_filters_correctly()
    {
        // Créer des données de test
        FormSubmission::create([
            'city' => 'annot',
            'country' => 'France',
            'department' => '04 - Alpes-de-Haute-Provence',
            'email' => 'test1@example.com',
            'consent_newsletter' => true,
            'consent_data_processing' => true,
            'profile' => 'Famille',
            'age_groups' => ['25-40'],
            'specific_requests' => ['Escalade'],
            'general_requests' => ['Randonnées']
        ]);

        // Tester le reset
        Livewire::test(AdvancedStatistics::class)
            ->set('selectedCity', 'annot')
            ->set('selectedCountry', 'France')
            ->call('resetFilters')
            ->assertSet('selectedCity', '')
            ->assertSet('selectedCountry', '')
            ->assertDispatched('refresh-charts'); // Vérifier que l'événement est déclenché
    }

    /** @test */
    public function it_updates_chart_data_when_filters_change()
    {
        // Créer des données de test
        FormSubmission::create([
            'city' => 'annot',
            'country' => 'France',
            'department' => '04 - Alpes-de-Haute-Provence',
            'email' => 'test1@example.com',
            'consent_newsletter' => true,
            'consent_data_processing' => true,
            'profile' => 'Famille',
            'age_groups' => ['25-40'],
            'specific_requests' => ['Escalade'],
            'general_requests' => ['Randonnées']
        ]);

        FormSubmission::create([
            'city' => 'entrevaux',
            'country' => 'Belgique',
            'department' => 'Inconnu',
            'email' => 'test2@example.com',
            'consent_newsletter' => false,
            'consent_data_processing' => true,
            'profile' => 'Couple',
            'age_groups' => ['40-60'],
            'specific_requests' => ['Train à Vapeur'],
            'general_requests' => ['Patrimoine culturel']
        ]);

        // Tester que les données des graphiques changent avec les filtres
        $component = Livewire::test(AdvancedStatistics::class);
        
        // Vérifier les données initiales
        $initialChartData = $component->get('chartData');
        $this->assertCount(2, $initialChartData['cities']); // 2 villes
        
        // Appliquer un filtre par ville
        $component->set('selectedCity', 'annot');
        $filteredChartData = $component->get('chartData');
        
        // Vérifier que les données ont été filtrées
        $this->assertCount(1, $filteredChartData['cities']); // 1 seule ville
        $this->assertArrayHasKey('annot', $filteredChartData['cities']);
        $this->assertArrayNotHasKey('entrevaux', $filteredChartData['cities']);
    }
}