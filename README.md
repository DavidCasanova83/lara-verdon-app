# 🗺️ Application Formulaire Touristique - Verdon Tourisme

Application web de collecte de données touristiques pour les bureaux d'information de Verdon Tourisme. Développée avec Laravel 12 + Livewire 3, elle remplace l'application Astro originale tout en conservant toutes les fonctionnalités.

## 📋 Description

Cette application permet aux visiteurs des 5 bureaux d'information touristique de remplir un questionnaire en 3 étapes pour collecter :
- **Données démographiques** (pays, département, email, profil, âge)
- **Consentements RGPD** (newsletter, traitement des données)
- **Demandes spécifiques** par ville et demandes générales
- **Statistiques** avec visualisations graphiques

### 🏛️ Villes couvertes
- **La Palud-sur-Verdon**
- **Saint-André-les-Alpes** 
- **Colmars-les-Alpes**
- **Entrevaux**
- **Annot**

---

## 🚀 Installation et Configuration

### Prérequis
- PHP 8.2+
- Composer
- Node.js 18+
- SQLite (inclus par défaut)

### Installation rapide

```bash
# 1. Cloner le repository
git clone <repository-url>
cd lara-verdon-app

# 2. Installer les dépendances PHP
composer install

# 3. Installer les dépendances JavaScript
npm install

# 4. Copier le fichier d'environnement
cp .env.example .env

# 5. Générer la clé d'application
php artisan key:generate

# 6. Créer la base de données et les données de base
php artisan migrate --seed

# 7. Compiler les assets
npm run build

# 8. Démarrer l'application
composer dev
```

### Configuration manuelle

Si vous préférez démarrer les services individuellement :

```bash
# Base de données
php artisan migrate --seed

# Assets (en mode développement)
npm run dev

# Serveur Laravel
php artisan serve

# Logs en temps réel
php artisan pail

# Worker de queue (si nécessaire)
php artisan queue:listen
```

---

## 🛠️ Stack Technique

### Backend
- **Laravel 12** - Framework PHP
- **Livewire 3** - Composants interactifs côté serveur
- **SQLite** - Base de données (configurable pour PostgreSQL/MySQL)
- **Pest** - Framework de tests

### Frontend
- **Tailwind CSS 4.0** - Framework CSS utility-first
- **Chart.js** - Graphiques pour les statistiques
- **Police Atkinson** - Typographie officielle
- **Alpine.js** - Interactions JavaScript légères (via Livewire)

### Fonctionnalités
- **Session persistante** entre les étapes du formulaire
- **Validation temps réel** avec Livewire
- **Responsive design** mobile-first
- **Statistiques interactives** avec graphiques
- **RGPD compliant** avec consentements

---

## 📖 Utilisation

### 1. Page d'accueil
- Sélectionnez une ville parmi les 5 destinations
- Cliquez sur la carte pour accéder au formulaire

### 2. Formulaire - Étape 1
- Sélectionnez le pays de résidence
- Si France : précisez le département (ou "Inconnu")
- Saisissez l'email de contact
- Acceptez les consentements RGPD

### 3. Formulaire - Étape 2  
- Choisissez le profil du visiteur
- Sélectionnez les tranches d'âge (sélection multiple)
- Options "Inconnu" disponibles

### 4. Formulaire - Étape 3
- Sélectionnez les demandes spécifiques à la ville
- Choisissez parmi 21 demandes générales
- Ajoutez d'autres demandes en texte libre
- Soumettez le formulaire

### 5. Statistiques
- Accès via le lien en page d'accueil
- Graphiques interactifs par ville, profil, département, etc.
- Données en temps réel

---

## 🏗️ Architecture

```
app/
├── Http/Controllers/
│   ├── CityController.php         # Page d'accueil
│   ├── FormController.php         # Navigation formulaire
│   └── StatisticsController.php   # Page statistiques
├── Livewire/
│   ├── FormStep1.php             # Étape 1: Infos générales
│   ├── FormStep2.php             # Étape 2: Profil visiteur  
│   ├── FormStep3.php             # Étape 3: Demandes
│   └── Statistics.php            # Affichage statistiques
├── Models/
│   ├── City.php                  # Modèle des villes
│   └── FormSubmission.php        # Modèle des soumissions
└── Services/
    └── FormOptionsService.php    # Options centralisées

resources/
├── views/
│   ├── pages/                    # Pages principales
│   └── livewire/                # Templates Livewire
└── css/
    └── app.css                   # Styles Tailwind + customs

database/
├── migrations/                   # Structure base de données
└── seeders/
    └── CitiesSeeder.php         # Données initiales villes
```

---

## 🔧 Développement

### Commandes utiles

```bash
# Développement complet (recommandé)
composer dev

# Tests
composer test
php artisan test

# Formatage du code
./vendor/bin/pint

# Livewire
php artisan livewire:make NouveauComposant

# Assets
npm run dev          # Mode développement
npm run build        # Production
npm run watch        # Watch mode

# Base de données
php artisan migrate:fresh --seed
php artisan db:seed --class=CitiesSeeder
```

### Structure des données

#### FormSubmission
```php
- city: string                    # Ville sélectionnée
- country: string                 # Pays de résidence  
- department: string|null         # Département français
- email: string                   # Email de contact
- consent_newsletter: boolean     # Consentement newsletter
- consent_data_processing: boolean # Consentement RGPD
- profile: string                 # Profil visiteur
- age_groups: array              # Tranches d'âge (JSON)
- specific_requests: array       # Demandes spécifiques (JSON)
- general_requests: array        # Demandes générales (JSON)  
- other_request: text|null       # Autres demandes
```

#### City
```php
- name: string                   # Nom de la ville
- slug: string                   # Identifiant URL
- image: string|null            # Nom fichier image
```

---

## 📊 Statistiques et Analytics

### Graphiques disponibles
- **Répartition par ville** (barres)
- **Répartition par profil** (secteurs) 
- **Répartition par département** (barres)
- **Répartition par tranche d'âge** (secteurs)
- **Demandes spécifiques** (barres)
- **Demandes générales** (barres)

### Accès aux données
Les données sont stockées en base SQLite et accessibles via :
- Interface graphique des statistiques
- Modèles Eloquent pour développement custom
- Possibilité d'export futur (CSV, Excel)

---

## 🔒 Sécurité et RGPD

### Conformité RGPD
- ✅ **Consentement explicite** pour newsletter
- ✅ **Consentement obligatoire** pour traitement des données
- ✅ **Information claire** sur l'utilisation des données
- ✅ **Possibilité de refus** de la newsletter

### Sécurité technique
- ✅ **Protection CSRF** sur tous les formulaires
- ✅ **Validation côté serveur** avec Laravel
- ✅ **Sanitization** automatique des entrées
- ✅ **Session sécurisée** avec Laravel
- ✅ **Headers de sécurité** configurés

---

## 🌐 Déploiement

### Environnement de production

```bash
# 1. Configuration production
cp .env.example .env
# Éditer .env avec les vraies valeurs

# 2. Optimisations Laravel
php artisan config:cache
php artisan route:cache  
php artisan view:cache

# 3. Assets de production
npm run build

# 4. Base de données
php artisan migrate --force
php artisan db:seed --class=CitiesSeeder --force

# 5. Permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Variables d'environnement importantes

```env
APP_NAME="Formulaire Touristique"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-domaine.com

DB_CONNECTION=sqlite
# Ou pour PostgreSQL/MySQL :
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_DATABASE=verdon_forms

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

---

## 🧪 Tests

### Lancer les tests
```bash
# Tous les tests
composer test
php artisan test

# Tests spécifiques
php artisan test --filter=FormSubmissionTest
php artisan test tests/Feature/FormFlowTest.php

# Avec couverture
php artisan test --coverage
```

### Tests disponibles
- **Tests unitaires** des modèles
- **Tests d'intégration** du flux de formulaire
- **Tests Livewire** des composants
- **Tests de validation** des données

---

## 🤝 Contribution

### Workflow de développement
1. Fork le repository
2. Créer une branche feature : `git checkout -b feature/nouvelle-fonctionnalite`
3. Commiter les changements : `git commit -m 'Ajout nouvelle fonctionnalité'`
4. Pousser vers la branche : `git push origin feature/nouvelle-fonctionnalite`
5. Ouvrir une Pull Request

### Standards de code
- **Laravel Pint** pour le formatage PHP
- **PSR-12** pour les standards PHP
- **Conventional Commits** pour les messages de commit
- **Tests** obligatoires pour nouvelles fonctionnalités

---

## 📚 Documentation

### Fichiers de documentation
- **CLAUDE.md** - Guide pour développeurs Claude Code
- **RECAP_DEVELOPPEMENT.md** - Récapitulatif complet et suggestions d'améliorations
- **ANALYSE_APPLICATION_ASTRO.md** - Spécifications originales Astro

### Ressources utiles
- [Documentation Laravel 12](https://laravel.com/docs/12.x)
- [Documentation Livewire 3](https://livewire.laravel.com/docs)
- [Documentation Tailwind CSS 4](https://tailwindcss.com/docs)
- [Documentation Chart.js](https://www.chartjs.org/docs/)

---

## 🐛 Dépannage

### Problèmes courants

#### Erreur "Class FormOptionsService not found"
```bash
composer dump-autoload
```

#### Assets non compilés
```bash
npm run build
php artisan optimize:clear
```

#### Erreurs de permissions
```bash
chmod -R 755 storage bootstrap/cache
```

#### Base de données corrompue
```bash
php artisan migrate:fresh --seed
```

### Logs et debugging
```bash
# Voir les logs en temps réel
php artisan pail

# Nettoyer les caches
php artisan optimize:clear

# Mode debug
# Éditer .env : APP_DEBUG=true
```

---

## 📞 Support

### En cas de problème
1. Vérifier les **logs** : `storage/logs/laravel.log`
2. Consulter la **documentation** technique
3. Vérifier les **issues** GitHub existantes
4. Créer une **nouvelle issue** avec détails

### Informations utiles pour le support
- Version PHP : `php --version`
- Version Composer : `composer --version`  
- Version Node : `node --version`
- Logs d'erreur complets
- Étapes pour reproduire le problème

---

## 📄 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

---

## 🙏 Remerciements

- **Verdon Tourisme** pour les spécifications fonctionnelles
- **Communauté Laravel** pour le framework excellent
- **Équipe Livewire** pour les composants réactifs
- **Tailwind CSS** pour le système de design

---

*Développé avec ❤️ pour Verdon Tourisme*# lara-verdon-app
