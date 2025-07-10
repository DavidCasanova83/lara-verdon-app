# Analyse Complète - Application Formulaire Touristique Astro

## 📋 Vue d'ensemble du projet

### Description

Application web de formulaires touristiques pour le Verdon Tourisme, permettant aux visiteurs de différents bureaux d'information de remplir des questionnaires en 3 étapes. L'application collecte des données démographiques, de profil et de demandes spécifiques pour chaque ville.

### Technologies actuelles

- **Framework**: Astro 5.3.0
- **Styling**: Tailwind CSS 4.0.6
- **Base de données**: AWS DynamoDB
- **JavaScript**: Alpine.js pour l'interactivité
- **Déploiement**: Vercel
- **Charts**: Chart.js pour les statistiques

---

## 🏗️ Architecture actuelle

### Structure des dossiers

```
astro-form-vt/
├── api/                    # API endpoints (Vercel Functions)
│   ├── saveAnswer.js      # Sauvegarde des réponses
│   ├── statistics.js      # Comptage des formulaires
│   └── getAllForms.js     # Récupération de toutes les données
├── src/
│   ├── pages/            # Pages Astro
│   │   ├── [city]/       # Pages dynamiques par ville
│   │   │   ├── form1.astro
│   │   │   ├── form2.astro
│   │   │   └── form3.astro
│   │   ├── statistiques.astro
│   │   └── index.astro
│   ├── components/       # Composants réutilisables
│   ├── data/            # Données statiques
│   └── styles/          # Styles globaux
└── public/              # Assets statiques
```

---

## 🎯 Fonctionnalités principales

### 1. Page d'accueil (`/`)

- **Design**: Grille de cartes carrées avec images de fond
- **Villes disponibles**: 5 destinations (La Palud-sur-Verdon, Saint-André-les-Alpes, Colmars-les-Alpes, Entrevaux, Annot)
- **Navigation**: Redirection vers `/ville/form1` au clic

### 2. Formulaire en 3 étapes

#### Étape 1 (`/ville/form1`)

**Données collectées :**

- Pays de résidence (France, Belgique, Allemagne, Italie, Pays-Bas, Suisse, Espagne, Angleterre, Autre)
- Département (si France sélectionné)
- Email de contact
- Consentements :
  - Newsletter
  - Traitement des données RGPD

**Fonctionnalités :**

- Sélection par boutons avec état visuel
- Champ texte pour "Autre" pays
- Liste déroulante des départements français
- Validation côté client

#### Étape 2 (`/ville/form2`)

**Données collectées :**

- Profil visiteur (Seul, Couple, Famille, Groupe d'amis, Inconnu)
- Tranches d'âge (0-18, 18-25, 25-40, 40-60, 60+, Inconnu)

**Fonctionnalités :**

- Sélection multiple pour les tranches d'âge
- Boutons avec états visuels
- Validation obligatoire

#### Étape 3 (`/ville/form3`)

**Données collectées :**

- Demandes spécifiques par ville (options prédéfinies)
- Demandes générales (18 options)
- Autres demandes (champ texte libre)

**Options spécifiques par ville :**

- **Annot**: Escalade, Train à Vapeur, Grès d'Annot
- **Colmars-les-Alpes**: Lac d'Allos, Cascade de la Lance, Maison Musée
- **Entrevaux**: Nice, Cote d'azur, Chemin de ronde, Citadelle, Gorge de Daluis, Train à Vapeur
- **La Palud-sur-Verdon**: Blanc-Martel, Route des Crêtes, Escalade et via cordatta
- **Saint-André-les-Alpes**: Lac de Castillon, Parapente

### 3. Système de données

- **Stockage temporaire**: localStorage pour la persistance entre étapes
- **Sauvegarde finale**: API POST vers `/api/saveAnswer`
- **Base de données**: DynamoDB avec UUID comme clé primaire

### 4. Page de statistiques (`/statistiques`)

**Graphiques affichés :**

- Répartition par ville (graphique en barres)
- Répartition par profil (graphique circulaire)
- Répartition par département (graphique en barres)
- Répartition par tranche d'âge (graphique circulaire)
- Demandes spécifiques (graphique en barres)
- Demandes générales (graphique en barres)
- Autres demandes (graphique circulaire)

---

## 🎨 Design et UX

### Palette de couleurs

- **Primaire**: Bleu (#189187, #0F5F5C)
- **Secondaire**: Vert (#2CA08B, #74C69D)
- **Accent**: Orange (#F4A261, #E76F51)
- **Fond**: Gris clair (#bg-gray-100)
- **Texte**: Gris foncé (#text-gray-800)

### Composants UI

- **Boutons**: États hover, focus, disabled
- **Cartes**: Ombres, transitions, effets hover
- **Formulaires**: Validation visuelle, messages d'erreur
- **Responsive**: Mobile-first avec breakpoints Tailwind

### Typographie

- **Police**: Atkinson (woff)
- **Tailles**: 20px base, hiérarchie claire
- **Poids**: Regular (400), Bold (700)

---

## 🔧 Spécifications techniques

### API Endpoints

1. **POST /api/saveAnswer**

   - Sauvegarde les données du formulaire
   - Retourne success/error avec données

2. **GET /api/statistics**

   - Compte total des formulaires
   - Retourne {success: true, count: number}

3. **GET /api/getAllForms**
   - Récupère toutes les données
   - Retourne {success: true, data: array}

### Structure des données

```json
{
  "primaire": "uuid",
  "city": "string",
  "country": "string",
  "department": "string",
  "email": "string",
  "consentNewsletter": "boolean",
  "consentDataProcessing": "boolean",
  "profile": "string",
  "ageGroups": ["array"],
  "specificRequests": ["array"],
  "generalRequests": ["array"],
  "otherRequest": "string",
  "timestamp": "ISO string"
}
```

---

## 🚀 Migration vers Laravel 12 + Livewire

### Architecture MVC proposée

#### 1. Structure des dossiers Laravel

```
laravel-form-vt/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── FormController.php
│   │   │   ├── StatisticsController.php
│   │   │   └── CityController.php
│   │   └── Livewire/
│   │       ├── FormStep1.php
│   │       ├── FormStep2.php
│   │       ├── FormStep3.php
│   │       └── Statistics.php
│   ├── Models/
│   │   ├── FormSubmission.php
│   │   └── City.php
│   └── Services/
│       └── StatisticsService.php
├── database/
│   ├── migrations/
│   │   ├── create_form_submissions_table.php
│   │   └── create_cities_table.php
│   └── seeders/
│       └── CitiesSeeder.php
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   └── app.blade.php
│   │   ├── pages/
│   │   │   ├── home.blade.php
│   │   │   └── statistics.blade.php
│   │   └── livewire/
│   │       ├── form-step1.blade.php
│   │       ├── form-step2.blade.php
│   │       ├── form-step3.blade.php
│   │       └── statistics.blade.php
│   └── css/
│       └── app.css
└── routes/
    └── web.php
```

#### 2. Modèles de données

**FormSubmission Model :**

```php
class FormSubmission extends Model
{
    protected $fillable = [
        'city',
        'country',
        'department',
        'email',
        'consent_newsletter',
        'consent_data_processing',
        'profile',
        'age_groups',
        'specific_requests',
        'general_requests',
        'other_request'
    ];

    protected $casts = [
        'age_groups' => 'array',
        'specific_requests' => 'array',
        'general_requests' => 'array',
        'consent_newsletter' => 'boolean',
        'consent_data_processing' => 'boolean'
    ];
}
```

**City Model :**

```php
class City extends Model
{
    protected $fillable = ['name', 'slug', 'image'];

    public function getSpecificOptionsAttribute()
    {
        return [
            'annot' => ['Escalade', 'Train à Vapeur', 'Grès d\'Annot'],
            'colmars-les-alpes' => ['Lac d\'Allos', 'Cascade de la Lance', 'Maison Musée'],
            'entrevaux' => ['Nice', 'Cote d\'azur', 'Chemin de ronde', 'Citadelle', 'Gorge de Daluis', 'Train à Vapeur'],
            'la-palud-sur-verdon' => ['Blanc-Martel', 'Route des Crêtes', 'Escalade et via cordatta'],
            'saint-andre-les-alpes' => ['Lac de Castillon', 'Parapente']
        ][$this->slug] ?? [];
    }
}
```

#### 3. Composants Livewire

**FormStep1 Component :**

```php
class FormStep1 extends Component
{
    public $city;
    public $country = 'France';
    public $otherCountry = '';
    public $department = '';
    public $departmentUnknown = false;
    public $email = '';
    public $consentNewsletter = false;
    public $consentDataProcessing = false;

    public function mount($city)
    {
        $this->city = $city;
        // Récupérer les données de session si elles existent
        $sessionData = session('form_data', []);
        if (!empty($sessionData)) {
            $this->country = $sessionData['country'] ?? 'France';
            $this->otherCountry = $sessionData['other_country'] ?? '';
            $this->department = $sessionData['department'] ?? '';
            $this->email = $sessionData['email'] ?? '';
            $this->consentNewsletter = $sessionData['consent_newsletter'] ?? false;
            $this->consentDataProcessing = $sessionData['consent_data_processing'] ?? false;
        }
    }

    public function nextStep()
    {
        $this->validate([
            'email' => 'required|email',
            'consentDataProcessing' => 'required|accepted',
            'country' => 'required',
            'department' => 'required_if:country,France'
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez entrer une adresse email valide.',
            'consentDataProcessing.required' => 'Vous devez accepter le traitement des données.',
            'consentDataProcessing.accepted' => 'Vous devez accepter le traitement des données.',
            'country.required' => 'Veuillez sélectionner un pays.',
            'department.required_if' => 'Veuillez sélectionner un département.'
        ]);

        // Sauvegarder en session
        session(['form_data' => [
            'city' => $this->city,
            'country' => $this->country === 'Autre' ? $this->otherCountry : $this->country,
            'other_country' => $this->otherCountry,
            'department' => $this->department,
            'email' => $this->email,
            'consent_newsletter' => $this->consentNewsletter,
            'consent_data_processing' => $this->consentDataProcessing
        ]]);

        return redirect()->route('form.step2', $this->city);
    }

    public function render()
    {
        return view('livewire.form-step1');
    }
}
```

**FormStep2 Component :**

```php
class FormStep2 extends Component
{
    public $city;
    public $profile = '';
    public $profileUnknown = false;
    public $ageGroups = [];
    public $ageUnknown = false;

    public function mount($city)
    {
        $this->city = $city;
        // Récupérer les données de session
        $sessionData = session('form_data', []);
        if (!empty($sessionData)) {
            $this->profile = $sessionData['profile'] ?? '';
            $this->profileUnknown = $sessionData['profile_unknown'] ?? false;
            $this->ageGroups = $sessionData['age_groups'] ?? [];
            $this->ageUnknown = $sessionData['age_unknown'] ?? false;
        }
    }

    public function nextStep()
    {
        $this->validate([
            'profile' => 'required_without:profileUnknown',
            'ageGroups' => 'required|array|min:1'
        ], [
            'profile.required_without' => 'Veuillez sélectionner un profil ou marquer comme inconnu.',
            'ageGroups.required' => 'Veuillez sélectionner au moins une tranche d\'âge.',
            'ageGroups.min' => 'Veuillez sélectionner au moins une tranche d\'âge.'
        ]);

        // Sauvegarder en session
        session(['form_data' => array_merge(
            session('form_data', []),
            [
                'profile' => $this->profileUnknown ? 'Inconnu' : $this->profile,
                'profile_unknown' => $this->profileUnknown,
                'age_groups' => $this->ageGroups,
                'age_unknown' => $this->ageUnknown
            ]
        )]);

        return redirect()->route('form.step3', $this->city);
    }

    public function render()
    {
        return view('livewire.form-step2');
    }
}
```

**FormStep3 Component :**

```php
class FormStep3 extends Component
{
    public $city;
    public $specificRequests = [];
    public $generalRequests = [];
    public $otherRequest = '';

    public function mount($city)
    {
        $this->city = $city;
        // Récupérer les données de session
        $sessionData = session('form_data', []);
        if (!empty($sessionData)) {
            $this->specificRequests = $sessionData['specific_requests'] ?? [];
            $this->generalRequests = $sessionData['general_requests'] ?? [];
            $this->otherRequest = $sessionData['other_request'] ?? '';
        }
    }

    public function submit()
    {
        $this->validate([
            'specificRequests' => 'array',
            'generalRequests' => 'array',
            'otherRequest' => 'nullable|string|max:500'
        ], [
            'otherRequest.max' => 'Le texte ne peut pas dépasser 500 caractères.'
        ]);

        // Vérifier qu'au moins une demande est sélectionnée
        if (empty($this->specificRequests) && empty($this->generalRequests) && empty(trim($this->otherRequest))) {
            $this->addError('requests', 'Veuillez sélectionner au moins une demande ou préciser votre demande.');
            return;
        }

        // Récupérer toutes les données de session
        $formData = array_merge(
            session('form_data', []),
            [
                'specific_requests' => $this->specificRequests,
                'general_requests' => $this->generalRequests,
                'other_request' => $this->otherRequest
            ]
        );

        // Créer la soumission
        $submission = FormSubmission::create([
            'city' => $formData['city'],
            'country' => $formData['country'],
            'department' => $formData['department'],
            'email' => $formData['email'],
            'consent_newsletter' => $formData['consent_newsletter'],
            'consent_data_processing' => $formData['consent_data_processing'],
            'profile' => $formData['profile'],
            'age_groups' => $formData['age_groups'],
            'specific_requests' => $formData['specific_requests'],
            'general_requests' => $formData['general_requests'],
            'other_request' => $formData['other_request']
        ]);

        // Nettoyer la session
        session()->forget('form_data');

        session()->flash('success', 'Merci ! Votre formulaire a été enregistré avec succès.');
        return redirect()->route('form.step1', $this->city);
    }

    public function render()
    {
        return view('livewire.form-step3');
    }
}
```

#### 4. Routes Laravel

```php
Route::get('/', [CityController::class, 'index'])->name('home');
Route::get('/{city}/form1', [FormController::class, 'step1'])->name('form.step1');
Route::get('/{city}/form2', [FormController::class, 'step2'])->name('form.step2');
Route::get('/{city}/form3', [FormController::class, 'step3'])->name('form.step3');
Route::post('/{city}/submit', [FormController::class, 'submit'])->name('form.submit');
Route::get('/statistiques', [StatisticsController::class, 'index'])->name('statistics');
```

#### 5. Base de données

**Migration form_submissions :**

```php
Schema::create('form_submissions', function (Blueprint $table) {
    $table->id();
    $table->string('city');
    $table->string('country');
    $table->string('department')->nullable();
    $table->string('email');
    $table->boolean('consent_newsletter')->default(false);
    $table->boolean('consent_data_processing');
    $table->string('profile');
    $table->json('age_groups');
    $table->json('specific_requests');
    $table->json('general_requests');
    $table->text('other_request')->nullable();
    $table->timestamps();
});
```

#### 6. Statistiques avec Livewire

```php
class Statistics extends Component
{
    public $totalCount;
    public $citiesData;
    public $profilesData;
    public $departmentsData;
    public $ageGroupsData;
    public $specificRequestsData;
    public $generalRequestsData;
    public $otherRequestsData;

    public function mount()
    {
        $this->loadStatistics();
    }

    public function loadStatistics()
    {
        $this->totalCount = FormSubmission::count();

        // Données par ville
        $this->citiesData = FormSubmission::selectRaw('city, count(*) as count')
            ->groupBy('city')
            ->pluck('count', 'city')
            ->toArray();

        // Données par profil
        $this->profilesData = FormSubmission::selectRaw('profile, count(*) as count')
            ->groupBy('profile')
            ->pluck('count', 'profile')
            ->toArray();

        // Données par département
        $this->departmentsData = FormSubmission::selectRaw('department, count(*) as count')
            ->whereNotNull('department')
            ->groupBy('department')
            ->pluck('count', 'department')
            ->toArray();

        // Données par tranche d'âge
        $this->ageGroupsData = $this->getAgeGroupsData();

        // Demandes spécifiques
        $this->specificRequestsData = $this->getSpecificRequestsData();

        // Demandes générales
        $this->generalRequestsData = $this->getGeneralRequestsData();

        // Autres demandes
        $this->otherRequestsData = $this->getOtherRequestsData();
    }

    private function getAgeGroupsData()
    {
        $submissions = FormSubmission::all();
        $ageGroups = [];

        foreach ($submissions as $submission) {
            foreach ($submission->age_groups as $ageGroup) {
                $ageGroups[$ageGroup] = ($ageGroups[$ageGroup] ?? 0) + 1;
            }
        }

        return $ageGroups;
    }

    private function getSpecificRequestsData()
    {
        $submissions = FormSubmission::all();
        $requests = [];

        foreach ($submissions as $submission) {
            foreach ($submission->specific_requests as $request) {
                $requests[$request] = ($requests[$request] ?? 0) + 1;
            }
        }

        return $requests;
    }

    private function getGeneralRequestsData()
    {
        $submissions = FormSubmission::all();
        $requests = [];

        foreach ($submissions as $submission) {
            foreach ($submission->general_requests as $request) {
                $requests[$request] = ($requests[$request] ?? 0) + 1;
            }
        }

        return $requests;
    }

    private function getOtherRequestsData()
    {
        return FormSubmission::selectRaw('other_request, count(*) as count')
            ->whereNotNull('other_request')
            ->where('other_request', '!=', '')
            ->groupBy('other_request')
            ->pluck('count', 'other_request')
            ->toArray();
    }

    public function render()
    {
        return view('livewire.statistics');
    }
}
```

#### 7. Service pour les options du formulaire

```php
class FormOptionsService
{
    public function getCountries()
    {
        return [
            'France', 'Belgique', 'Allemagne', 'Italie', 'Pays-Bas',
            'Suisse', 'Espagne', 'Angleterre', 'Autre'
        ];
    }

    public function getDepartments()
    {
        return [
            '01 - Ain', '02 - Aisne', '03 - Allier', '04 - Alpes-de-Haute-Provence',
            '05 - Hautes-Alpes', '06 - Alpes-Maritimes', '07 - Ardèche', '08 - Ardennes',
            '09 - Ariège', '10 - Aube', '11 - Aude', '12 - Aveyron', '13 - Bouches-du-Rhône',
            '14 - Calvados', '15 - Cantal', '16 - Charente', '17 - Charente-Maritime',
            '18 - Cher', '19 - Corrèze', '2A - Corse-du-Sud', '2B - Haute-Corse',
            '21 - Côte-dOr', '22 - Côtes-dArmor', '23 - Creuse', '24 - Dordogne',
            '25 - Doubs', '26 - Drôme', '27 - Eure', '28 - Eure-et-Loir', '29 - Finistère',
            '30 - Gard', '31 - Haute-Garonne', '32 - Gers', '33 - Gironde', '34 - Hérault',
            '35 - Ille-et-Vilaine', '36 - Indre', '37 - Indre-et-Loire', '38 - Isère',
            '39 - Jura', '40 - Landes', '41 - Loir-et-Cher', '42 - Loire', '43 - Haute-Loire',
            '44 - Loire-Atlantique', '45 - Loiret', '46 - Lot', '47 - Lot-et-Garonne',
            '48 - Lozère', '49 - Maine-et-Loire', '50 - Manche', '51 - Marne',
            '52 - Haute-Marne', '53 - Mayenne', '54 - Meurthe-et-Moselle', '55 - Meuse',
            '56 - Morbihan', '57 - Moselle', '58 - Nièvre', '59 - Nord', '60 - Oise',
            '61 - Orne', '62 - Pas-de-Calais', '63 - Puy-de-Dôme', '64 - Pyrénées-Atlantiques',
            '65 - Hautes-Pyrénées', '66 - Pyrénées-Orientales', '67 - Bas-Rhin',
            '68 - Haut-Rhin', '69 - Rhône', '70 - Haute-Saône', '71 - Saône-et-Loire',
            '72 - Sarthe', '73 - Savoie', '74 - Haute-Savoie', '75 - Paris',
            '76 - Seine-Maritime', '77 - Seine-et-Marne', '78 - Yvelines',
            '79 - Deux-Sèvres', '80 - Somme', '81 - Tarn', '82 - Tarn-et-Garonne',
            '83 - Var', '84 - Vaucluse', '85 - Vendée', '86 - Vienne', '87 - Haute-Vienne',
            '88 - Vosges', '89 - Yonne', '90 - Territoire de Belfort', '91 - Essonne',
            '92 - Hauts-de-Seine', '93 - Seine-Saint-Denis', '94 - Val-de-Marne',
            '95 - Val-d-Oise', '971 - Guadeloupe', '972 - Martinique', '973 - Guyane',
            '974 - La Réunion', '976 - Mayotte'
        ];
    }

    public function getProfiles()
    {
        return ['Seul', 'Couple', 'Famille', 'Groupe d\'amis'];
    }

    public function getAgeGroups()
    {
        return ['0-18', '18-25', '25-40', '40-60', '60+'];
    }

    public function getSpecificOptions($citySlug)
    {
        return [
            'annot' => ['Escalade', 'Train à Vapeur', 'Grès d\'Annot'],
            'colmars-les-alpes' => ['Lac d\'Allos', 'Cascade de la Lance', 'Maison Musée'],
            'entrevaux' => ['Nice', 'Cote d\'azur', 'Chemin de ronde', 'Citadelle', 'Gorge de Daluis', 'Train à Vapeur'],
            'la-palud-sur-verdon' => ['Blanc-Martel', 'Route des Crêtes', 'Escalade et via cordatta'],
            'saint-andre-les-alpes' => ['Lac de Castillon', 'Parapente']
        ][$citySlug] ?? [];
    }

    public function getGeneralOptions()
    {
        return [
            'Randonnées', 'Pêche', 'Train', 'Villages alentours', 'Patrimoine culturel',
            'Patrimoine naturel', 'Visite guidée', 'Accès et transports', 'Informations pratiques',
            'Evènements et animations', 'Baignade et nautisme', 'Boutique Verdon Tourisme',
            'Activité d\'eau vive', 'Vélo et VTT', 'Autres activités de pleine nature',
            'Commerces', 'Produits locaux', 'Restaurants', 'Hébergements', 'Sociaux pro',
            'Demandes d\'habitants'
        ];
    }
}
```

---

## 🎨 Interface utilisateur

### Composants Blade/Livewire

- **Layout principal** : Header minimal, contenu centré
- **Cartes de villes** : Images de fond, overlay, effets hover
- **Formulaires** : Validation en temps réel, états visuels
- **Graphiques** : Chart.js intégré via CDN
- **Responsive** : Mobile-first avec Tailwind CSS

### Styles

- **Tailwind CSS** : Configuration identique
- **Police Atkinson** : Fichiers woff dans public/fonts
- **Couleurs** : Palette identique via variables CSS
- **Animations** : Transitions smooth, effets hover

### Configuration Tailwind CSS

```javascript
// tailwind.config.js
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./app/Livewire/**/*.php",
  ],
  theme: {
    extend: {
      fontFamily: {
        atkinson: ["Atkinson", "sans-serif"],
      },
      colors: {
        primary: {
          50: "#f0f9ff",
          500: "#189187",
          600: "#0F5F5C",
          700: "#0c4a6e",
        },
        secondary: {
          500: "#2CA08B",
          600: "#74C69D",
        },
        accent: {
          500: "#F4A261",
          600: "#E76F51",
        },
      },
      animation: {
        "fade-in": "fadeIn 0.5s ease-in-out",
        "slide-up": "slideUp 0.3s ease-out",
      },
      keyframes: {
        fadeIn: {
          "0%": { opacity: "0" },
          "100%": { opacity: "1" },
        },
        slideUp: {
          "0%": { transform: "translateY(10px)", opacity: "0" },
          "100%": { transform: "translateY(0)", opacity: "1" },
        },
      },
    },
  },
  plugins: [require("@tailwindcss/forms")],
};
```

### Configuration CSS global

```css
/* resources/css/app.css */
@import "tailwindcss/base";
@import "tailwindcss/components";
@import "tailwindcss/utilities";

@font-face {
  font-family: "Atkinson";
  src: url("/fonts/atkinson-regular.woff") format("woff");
  font-weight: 400;
  font-style: normal;
  font-display: swap;
}

@font-face {
  font-family: "Atkinson";
  src: url("/fonts/atkinson-bold.woff") format("woff");
  font-weight: 700;
  font-style: normal;
  font-display: swap;
}

body {
  font-family: "Atkinson", sans-serif;
  font-size: 20px;
  line-height: 1.7;
}

/* Animations personnalisées */
.form-step-enter {
  opacity: 0;
  transform: translateX(20px);
}

.form-step-enter-active {
  opacity: 1;
  transform: translateX(0);
  transition: all 0.3s ease-out;
}

/* Styles pour les boutons de sélection */
.selection-button {
  @apply px-4 py-2 rounded transition-all duration-200;
}

.selection-button.selected {
  @apply bg-blue-500 text-white shadow-lg;
}

.selection-button:not(.selected) {
  @apply bg-gray-200 text-black hover:bg-gray-300;
}

/* Styles pour les cartes de villes */
.city-card {
  @apply min-w-[250px] min-h-[250px] bg-cover bg-center relative shadow-lg 
         hover:shadow-xl transition-all duration-300 flex flex-col justify-between 
         items-center text-center border border-gray-200 p-4 hover:border-emerald-600;
}

.city-card-overlay {
  @apply absolute inset-0 bg-black/50;
}

.city-card-content {
  @apply text-lg font-semibold text-white relative z-10 text-nowrap;
}
```

---

## 🔄 Workflow de développement

### Phase 1 : Setup Laravel

1. Installation Laravel 12
2. Configuration Livewire 3
3. Setup Tailwind CSS
4. Configuration base de données

### Phase 2 : Modèles et migrations

1. Création des modèles City et FormSubmission
2. Migrations avec seeders
3. Relations et accesseurs

### Phase 3 : Composants Livewire

1. FormStep1 avec validation
2. FormStep2 avec sélections multiples
3. FormStep3 avec options dynamiques
4. Statistics avec graphiques

### Phase 4 : Interface utilisateur

1. Layout principal
2. Page d'accueil avec cartes
3. Pages de formulaires
4. Page de statistiques

### Phase 5 : Tests et optimisation

1. Tests unitaires
2. Tests d'intégration
3. Optimisation des performances
4. Déploiement

#### Tests unitaires

```php
// tests/Unit/FormSubmissionTest.php
class FormSubmissionTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_form_submission()
    {
        $data = [
            'city' => 'la-palud-sur-verdon',
            'country' => 'France',
            'department' => '83 - Var',
            'email' => 'test@example.com',
            'consent_newsletter' => true,
            'consent_data_processing' => true,
            'profile' => 'Couple',
            'age_groups' => ['25-40', '40-60'],
            'specific_requests' => ['Blanc-Martel'],
            'general_requests' => ['Randonnées'],
            'other_request' => 'Test request'
        ];

        $submission = FormSubmission::create($data);

        $this->assertDatabaseHas('form_submissions', [
            'city' => 'la-palud-sur-verdon',
            'email' => 'test@example.com'
        ]);
    }

    public function test_validation_rules()
    {
        $this->expectException(QueryException::class);

        FormSubmission::create([
            'city' => 'la-palud-sur-verdon',
            // Email manquant - devrait échouer
        ]);
    }
}
```

```php
// tests/Feature/FormFlowTest.php
class FormFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_step1_to_step2()
    {
        $response = $this->post(route('form.step1', 'la-palud-sur-verdon'), [
            'country' => 'France',
            'department' => '83 - Var',
            'email' => 'test@example.com',
            'consent_data_processing' => true
        ]);

        $response->assertRedirect(route('form.step2', 'la-palud-sur-verdon'));
        $this->assertSessionHas('form_data');
    }

    public function test_form_complete_flow()
    {
        // Étape 1
        $this->post(route('form.step1', 'la-palud-sur-verdon'), [
            'country' => 'France',
            'department' => '83 - Var',
            'email' => 'test@example.com',
            'consent_data_processing' => true
        ]);

        // Étape 2
        $this->post(route('form.step2', 'la-palud-sur-verdon'), [
            'profile' => 'Couple',
            'age_groups' => ['25-40']
        ]);

        // Étape 3
        $response = $this->post(route('form.step3', 'la-palud-sur-verdon'), [
            'specific_requests' => ['Blanc-Martel'],
            'general_requests' => ['Randonnées']
        ]);

        $response->assertRedirect(route('form.step1', 'la-palud-sur-verdon'));
        $this->assertDatabaseHas('form_submissions', [
            'city' => 'la-palud-sur-verdon',
            'email' => 'test@example.com'
        ]);
    }
}
```

#### Tests Livewire

```php
// tests/Feature/Livewire/FormStep1Test.php
class FormStep1Test extends TestCase
{
    use RefreshDatabase;

    public function test_can_render_form_step1()
    {
        $component = Livewire::test(FormStep1::class, ['city' => 'la-palud-sur-verdon']);

        $component->assertSee('Étape 1');
        $component->assertSee('France');
        $component->assertSee('Belgique');
    }

    public function test_validation_errors()
    {
        $component = Livewire::test(FormStep1::class, ['city' => 'la-palud-sur-verdon']);

        $component->call('nextStep');

        $component->assertHasErrors(['email', 'consentDataProcessing']);
    }

    public function test_can_proceed_to_next_step()
    {
        $component = Livewire::test(FormStep1::class, ['city' => 'la-palud-sur-verdon']);

        $component->set('email', 'test@example.com')
                 ->set('consentDataProcessing', true)
                 ->set('country', 'France')
                 ->set('department', '83 - Var')
                 ->call('nextStep');

        $component->assertRedirect(route('form.step2', 'la-palud-sur-verdon'));
    }
}
```

#### Optimisation des performances

```php
// app/Http/Controllers/StatisticsController.php
class StatisticsController extends Controller
{
    public function index()
    {
        // Cache les statistiques pour 1 heure
        $statistics = Cache::remember('form_statistics', 3600, function () {
            return [
                'total_count' => FormSubmission::count(),
                'cities_data' => FormSubmission::selectRaw('city, count(*) as count')
                    ->groupBy('city')
                    ->pluck('count', 'city')
                    ->toArray(),
                'profiles_data' => FormSubmission::selectRaw('profile, count(*) as count')
                    ->groupBy('profile')
                    ->pluck('count', 'profile')
                    ->toArray(),
                // ... autres données
            ];
        });

        return view('pages.statistics', compact('statistics'));
    }
}
```

#### Configuration de production

```php
// config/cache.php - Configuration Redis
'default' => env('CACHE_DRIVER', 'redis'),

'stores' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'cache',
    ],
],

// config/queue.php - Configuration des jobs
'default' => env('QUEUE_CONNECTION', 'redis'),

'connections' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'default',
        'queue' => env('REDIS_QUEUE', 'default'),
        'retry_after' => 90,
        'block_for' => null,
    ],
],
```

#### Déploiement avec Forge/Laravel Vapor

```bash
# Script de déploiement
#!/bin/bash

# Pull des dernières modifications
git pull origin main

# Installation des dépendances
composer install --no-dev --optimize-autoloader

# Compilation des assets
npm ci
npm run build

# Migrations
php artisan migrate --force

# Cache et optimisation
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Redémarrage des services
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
```

---

## 📊 Avantages de la migration

### Laravel 12 + Livewire

- **Sécurité** : Validation côté serveur, protection CSRF
- **Performance** : Cache, optimisation des requêtes
- **Maintenabilité** : Architecture MVC claire
- **Évolutivité** : Facile d'ajouter de nouvelles fonctionnalités
- **Base de données** : Relations, migrations, seeders
- **Authentification** : Système intégré si nécessaire

### Fonctionnalités supplémentaires possibles

- **Export des données** : CSV, Excel
- **Filtres avancés** : Par date, ville, profil
- **Notifications** : Email de confirmation
- **Dashboard admin** : Gestion des villes, options
- **API REST** : Pour intégrations externes

---

## 🚀 Instructions de développement

### Prérequis

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL/PostgreSQL
- Laravel 12
- Livewire 3

### Installation

```bash
# 1. Créer le projet Laravel
composer create-project laravel/laravel laravel-form-vt

# 2. Installer Livewire
composer require livewire/livewire

# 3. Installer les dépendances frontend
npm install
npm install -D tailwindcss @tailwindcss/forms

# 4. Configurer Tailwind
npx tailwindcss init
```

### Configuration

1. **.env** : Configurer la base de données
2. **config/livewire.php** : Configuration Livewire
3. **tailwind.config.js** : Configuration Tailwind
4. **resources/css/app.css** : Importer Tailwind

#### Configuration .env

```env
APP_NAME="Formulaire Touristique"
APP_ENV=local
APP_KEY=base64:your-key-here
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_form_vt
DB_USERNAME=root
DB_PASSWORD=

CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

#### Configuration Livewire

```php
// config/livewire.php
return [
    'class_namespace' => 'App\\Http\\Livewire',
    'view_path' => resource_path('views/livewire'),
    'layout' => 'layouts.app',
    'asset_url' => null,
    'app_url' => null,
    'middleware_group' => ['web'],
    'temporary_file_upload' => [
        'disk' => 'local',
        'rules' => null,
        'directory' => null,
        'middleware' => null,
        'preview_mimes' => [
            'png', 'gif', 'bmp', 'svg', 'wav', 'mp4',
            'mov', 'avi', 'wmv', 'mp3', 'm4a',
            'jpg', 'jpeg', 'mpga', 'webp', 'wma',
        ],
        'max_upload_time' => 5,
    ],
    'manifest_path' => null,
    'back_button_cache' => false,
    'render_on_redirect' => false,
];
```

#### Configuration des assets

```bash
# Copier les polices et images
cp -r public/fonts/ storage/app/public/fonts/
cp -r public/images/ storage/app/public/images/

# Créer le lien symbolique
php artisan storage:link
```

#### Configuration des migrations

```php
// database/migrations/2024_01_01_000001_create_cities_table.php
public function up()
{
    Schema::create('cities', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('slug')->unique();
        $table->string('image')->nullable();
        $table->timestamps();
    });
}

// database/migrations/2024_01_01_000002_create_form_submissions_table.php
public function up()
{
    Schema::create('form_submissions', function (Blueprint $table) {
        $table->id();
        $table->string('city');
        $table->string('country');
        $table->string('department')->nullable();
        $table->string('email');
        $table->boolean('consent_newsletter')->default(false);
        $table->boolean('consent_data_processing');
        $table->string('profile');
        $table->json('age_groups');
        $table->json('specific_requests');
        $table->json('general_requests');
        $table->text('other_request')->nullable();
        $table->timestamps();

        $table->index(['city', 'created_at']);
        $table->index('email');
    });
}
```

#### Seeder pour les villes

```php
// database/seeders/CitiesSeeder.php
class CitiesSeeder extends Seeder
{
    public function run()
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
```

### Développement

1. **Migrations** : `php artisan migrate`
2. **Seeders** : `php artisan db:seed`
3. **Serveur** : `php artisan serve`
4. **Assets** : `npm run dev`

### Déploiement

1. **Production build** : `npm run build`
2. **Optimisation** : `php artisan optimize`
3. **Cache** : `php artisan config:cache`

---

## 📝 Notes importantes

### Différences avec l'original

- **Session** : Remplace localStorage pour la persistance
- **Validation** : Côté serveur avec Livewire
- **Base de données** : MySQL/PostgreSQL au lieu de DynamoDB
- **API** : Routes Laravel au lieu de Vercel Functions

### Considérations techniques

- **Performance** : Cache des requêtes, pagination
- **Sécurité** : Validation stricte, protection CSRF
- **Accessibilité** : ARIA labels, navigation clavier
- **SEO** : Meta tags, sitemap

### Maintenance

- **Logs** : Laravel Log pour le debugging
- **Monitoring** : Health checks, métriques
- **Backup** : Stratégie de sauvegarde des données
- **Updates** : Plan de mise à jour régulier

---

## ✅ Checklist de développement

### Setup initial

- [ ] Installation Laravel 12
- [ ] Configuration Livewire 3
- [ ] Setup Tailwind CSS
- [ ] Configuration base de données

### Modèles et données

- [ ] Modèle City avec options spécifiques
- [ ] Modèle FormSubmission avec casts
- [ ] Migrations et seeders
- [ ] Relations et accesseurs

### Composants Livewire

- [ ] FormStep1 avec validation
- [ ] FormStep2 avec sélections multiples
- [ ] FormStep3 avec options dynamiques
- [ ] Statistics avec graphiques

### Interface utilisateur

- [ ] Layout principal responsive
- [ ] Page d'accueil avec cartes
- [ ] Pages de formulaires
- [ ] Page de statistiques
- [ ] Styles et animations

### Tests et déploiement

- [ ] Tests unitaires
- [ ] Tests d'intégration
- [ ] Optimisation performance
- [ ] Configuration production
- [ ] Déploiement

### Exemples de vues Blade/Livewire

#### Layout principal

```blade
{{-- resources/views/layouts/app.blade.php --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Formulaire Touristique' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-gray-100 text-gray-900 font-atkinson">
    <header class="bg-white shadow-sm">
        <div class="container mx-auto px-4 py-2">
            <h1 class="text-xl font-bold">
                <a href="{{ route('home') }}" class="text-primary-600 hover:text-primary-700">
                    Verdon Tourisme
                </a>
            </h1>
        </div>
    </header>

    <main class="min-h-screen">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{ $slot }}
    </main>

    @livewireScripts
</body>
</html>
```

#### Page d'accueil

```blade
{{-- resources/views/pages/home.blade.php --}}
<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-4xl font-extrabold text-center mb-6">
            🗺️ Sélectionnez une destination
        </h1>
        <p class="text-center mb-8 text-lg">
            Choisissez votre bureau d'information pour accéder au formulaire.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 place-items-center w-fit mx-auto">
            @foreach($cities as $city)
                <a href="{{ route('form.step1', $city->slug) }}"
                   class="city-card"
                   style="background-image: url('{{ Storage::url('images/' . $city->image) }}')">

                    <div class="city-card-overlay"></div>
                    <h2 class="city-card-content">{{ $city->name }}</h2>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
```

#### Composant FormStep1

```blade
{{-- resources/views/livewire/form-step1.blade.php --}}
<div class="mx-auto p-6 max-w-2xl bg-white shadow-lg rounded-lg">
    <h1 class="text-3xl font-bold mb-4">Étape 1</h1>

    <form wire:submit.prevent="nextStep">
        <!-- Question 1 : Pays de résidence -->
        <div class="mb-6">
            <p class="text-xl mb-4">Quel est le pays de résidence ?</p>
            <div class="flex flex-wrap justify-start gap-2 md:gap-4 mb-4">
                @foreach($countries as $country)
                    <button type="button"
                            wire:click="$set('country', '{{ $country }}')"
                            class="selection-button {{ $country === $selectedCountry ? 'selected' : '' }}">
                        {{ $country }}
                    </button>
                @endforeach
            </div>

            @if($country === 'Autre')
                <div class="mb-4">
                    <label class="block text-lg">Veuillez préciser le pays :</label>
                    <input type="text"
                           wire:model="otherCountry"
                           class="border p-2 rounded w-full"
                           placeholder="Entrez le pays...">
                </div>
            @endif
        </div>

        <!-- Question 2 : Département (si France) -->
        @if($country === 'France')
            <div class="mb-6">
                <p class="text-xl mb-2">Préciser le département</p>
                <div class="flex flex-wrap gap-2 mb-2">
                    <button type="button"
                            wire:click="$set('departmentUnknown', {{ !$departmentUnknown }})"
                            class="selection-button {{ $departmentUnknown ? 'selected' : '' }}">
                        Inconnu
                    </button>
                </div>
                <input type="text"
                       wire:model="department"
                       list="departmentsList"
                       class="border p-2 rounded w-full mb-4"
                       placeholder="exemple : 75 - Paris"
                       {{ $departmentUnknown ? 'disabled' : '' }}>
                <datalist id="departmentsList">
                    @foreach($departments as $dept)
                        <option value="{{ $dept }}">
                    @endforeach
                </datalist>
            </div>
        @endif

        <!-- Email -->
        <div class="mb-6">
            <label class="block text-lg font-semibold mb-2">Informations utilisateurs</label>
            <input type="email"
                   wire:model="email"
                   class="border p-3 rounded w-full text-gray-700 focus:ring focus:ring-blue-200 focus:outline-none"
                   placeholder="contact@verdontourisme.com" required>
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Consentements -->
        <div class="bg-gray-100 p-4 rounded-lg mb-4">
            <label class="flex items-start space-x-3">
                <input type="checkbox" wire:model="consentNewsletter" class="w-6 h-6 text-blue-500 border-gray-300 rounded">
                <span class="text-sm text-gray-700 leading-tight">
                    La personne souhaite recevoir la <span class="font-extrabold">newsletter</span> et des informations sur les événements.
                </span>
            </label>
        </div>

        <div class="bg-gray-100 p-4 rounded-lg mb-6">
            <label class="flex items-start space-x-3">
                <input type="checkbox" wire:model="consentDataProcessing" class="w-6 h-6 text-blue-500 border-gray-300 rounded">
                <span class="text-sm text-gray-700 leading-tight">
                    J'accepte que mes données soient traitées conformément à la
                    <a href="/politique-confidentialite" class="text-blue-500 hover:underline">politique de confidentialité RGPD</a>.
                </span>
            </label>
            @error('consentDataProcessing') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Bouton de validation -->
        <button type="submit"
                class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg text-lg cursor-pointer transition-colors">
            Suivant
        </button>
    </form>
</div>
```

#### Page de statistiques avec Chart.js

```blade
{{-- resources/views/livewire/statistics.blade.php --}}
<div class="container mx-auto p-6 max-w-4xl bg-white shadow-lg rounded-lg text-center">
    <h1 class="text-3xl font-bold mb-4">Statistiques détaillées</h1>
    <p class="text-xl">Nombre total de formulaires envoyés :
        <span class="text-4xl font-bold text-blue-600">{{ $totalCount }}</span>
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <div class="bg-gray-100 p-4 rounded-lg">
            <h2 class="text-lg font-semibold mb-2">Répartition par ville</h2>
            <canvas id="cityChart"></canvas>
        </div>
        <div class="bg-gray-100 p-4 rounded-lg">
            <h2 class="text-lg font-semibold mb-2">Répartition par profil</h2>
            <canvas id="profileChart"></canvas>
        </div>
        <div class="bg-gray-100 p-4 rounded-lg">
            <h2 class="text-lg font-semibold mb-2">Répartition par département</h2>
            <canvas id="departmentChart"></canvas>
        </div>
        <div class="bg-gray-100 p-4 rounded-lg">
            <h2 class="text-lg font-semibold mb-2">Répartition par tranche d'âge</h2>
            <canvas id="ageGroupChart"></canvas>
        </div>
        <div class="bg-gray-100 p-4 rounded-lg">
            <h2 class="text-lg font-semibold mb-2">Demandes spécifiques</h2>
            <canvas id="specificRequestsChart"></canvas>
        </div>
        <div class="bg-gray-100 p-4 rounded-lg">
            <h2 class="text-lg font-semibold mb-2">Demandes générales</h2>
            <canvas id="generalRequestsChart"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('livewire:load', function () {
            // Données des graphiques
            const chartData = @json([
                'cities' => $citiesData,
                'profiles' => $profilesData,
                'departments' => $departmentsData,
                'ageGroups' => $ageGroupsData,
                'specificRequests' => $specificRequestsData,
                'generalRequests' => $generalRequestsData
            ]);

            // Configuration des graphiques
            const chartConfigs = {
                cityChart: {
                    type: 'bar',
                    data: {
                        labels: Object.keys(chartData.cities),
                        datasets: [{
                            data: Object.values(chartData.cities),
                            backgroundColor: '#189187'
                        }]
                    }
                },
                profileChart: {
                    type: 'pie',
                    data: {
                        labels: Object.keys(chartData.profiles),
                        datasets: [{
                            data: Object.values(chartData.profiles),
                            backgroundColor: ['#0F5F5C', '#2CA08B', '#74C69D']
                        }]
                    }
                },
                // ... autres configurations
            };

            // Initialiser les graphiques
            Object.keys(chartConfigs).forEach(chartId => {
                const canvas = document.getElementById(chartId);
                if (canvas) {
                    new Chart(canvas.getContext('2d'), chartConfigs[chartId]);
                }
            });
        });
    </script>
</div>
```

---

## 🎯 Objectifs de la migration

1. **Reproduire fidèlement** toutes les fonctionnalités existantes
2. **Améliorer la sécurité** avec validation côté serveur
3. **Optimiser les performances** avec cache et requêtes optimisées
4. **Faciliter la maintenance** avec architecture MVC claire
5. **Préparer l'évolutivité** pour de futures fonctionnalités

Cette documentation fournit toutes les informations nécessaires pour reproduire l'application Astro avec Laravel 12 et Livewire, en conservant le même design et les mêmes fonctionnalités tout en bénéficiant des avantages d'une architecture MVC robuste.
