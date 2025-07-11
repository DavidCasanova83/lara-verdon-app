# Changelog - Verdon Tourisme Application

Toutes les modifications notables de ce projet seront documentées dans ce fichier.

Le format est basé sur [Keep a Changelog](https://keepachangelog.com/fr/1.0.0/).

## [Non publié] - 2025-07-11

### Ajouté
- **Système de formulaires multi-étapes** complet en 3 étapes
  - FormStep1 : Informations géographiques et utilisateur
  - FormStep2 : Sélection des tranches d'âge
  - FormStep3 : Demandes touristiques spécifiques et générales
- **Section optionnelle collapsible** dans FormStep1 avec Alpine.js
  - Informations utilisateur (email, consentements RGPD)
  - Animation d'ouverture/fermeture fluide
  - Icône de déroulant avec rotation
- **Système d'animation confetti** personnalisé
  - Animation JavaScript vanilla (resources/js/confetti.js)
  - Déclenchement automatique sur succès de formulaire
  - Double vague d'animation avec couleurs thématiques
- **Images spécifiques par village** 
  - Remplacement des images aléatoires par des images dédiées
  - Dossier public/images/villages/ avec images optimisées 400x300px
  - Correspondance automatique slug-image
- **Composants réutilisables**
  - `progress-bar.blade.php` : Indicateur de progression 3 étapes
  - `selection-button.blade.php` : Boutons de sélection avec accessibilité
- **Système de couleurs unifié** (#3B9C92 - thème teal)
  - Application cohérente sur tous les formulaires
  - Mise à jour des boutons, liens, et éléments interactifs
- **Messages de succès auto-masqués** après 5 secondes
- **Raccourci clavier global** pour les post-it notes (Ctrl+Shift+N)

### Modifié
- **Refactorisation complète** des 3 étapes du formulaire
  - Amélioration UX avec design moderne
  - Meilleure accessibilité et navigation
  - Validation renforcée et feedback visuel
- **Système de couleurs** migré vers #3B9C92 (teal)
  - Remplacement du thème vert précédent
  - Cohérence visuelle sur toute l'application
- **Gestion des redirections Livewire**
  - `redirect()->route()` → `$this->redirectRoute()`
  - Correction des boucles de redirection
- **Méthodes de manipulation des arrays**
  - Ajout de `toggleAgeGroup()`, `toggleSpecificRequest()`, `toggleGeneralRequest()`
  - Évitement des erreurs de compilation Blade

### Corrigé
- **Bug toggle département "Inconnu"**
  - Problème : Impossible de décocher une fois coché
  - Solution : Remplacement `{{ !$departmentUnknown }}` par `$toggle('departmentUnknown')`
- **Bug sélection "Demandes d'habitants"**
  - Problème : Apostrophe causait erreur JSON
  - Solution : Utilisation correcte de `json_encode()` au lieu de concaténation string
- **Bug chargement infini bouton "Continuer"**
  - Problème : Expressions Blade complexes dans wire:click
  - Solution : Simplification et utilisation de méthodes PHP dédiées
- **Bug compilation Blade**
  - Problème : Timeout sur expressions conditionnelles complexes
  - Solution : Déplacement de la logique vers les composants PHP
- **Bug éléments racine multiples Livewire**
  - Problème : Layouts mal positionnés dans les composants
  - Solution : Déplacement des layouts vers les vues pages

### Supprimé
- **Images aléatoires** (picsum.photos) remplacées par images spécifiques
- **Messages de succès persistants** remplacés par auto-masquage
- **Couleurs vertes** remplacées par thème teal

## [1.0.0] - 2025-07-11

### Version initiale
- **Application Laravel 12** avec Livewire Starter Kit
- **Système d'authentification** complet
- **Gestion des villes touristiques** (5 destinations)
- **Système de post-it notes** flottant
- **Statistiques par ville** et globales
- **Interface responsive** avec Tailwind CSS 4.0
- **Base de données SQLite** pour développement
- **Tests automatisés** avec Pest

### Destinations couvertes
- La Palud-sur-Verdon (Gorges du Verdon)
- Saint-André-les-Alpes (Lac de Castillon)
- Colmars-les-Alpes (Village fortifié)
- Entrevaux (Cité médiévale)
- Annot (Formations gréseuses)

### Stack technique
- **Backend** : Laravel 12 + Livewire 3
- **Frontend** : Tailwind CSS 4.0 + Alpine.js
- **Base de données** : SQLite (dev) / MySQL (production)
- **Build** : Vite
- **Tests** : Pest PHP

---

## Types de modifications

- **Ajouté** : Nouvelles fonctionnalités
- **Modifié** : Changements dans les fonctionnalités existantes
- **Déprécié** : Fonctionnalités qui seront supprimées
- **Supprimé** : Fonctionnalités supprimées
- **Corrigé** : Corrections de bugs
- **Sécurité** : Corrections de vulnérabilités

---

*Ce changelog est maintenu manuellement et suit les principes du versioning sémantique.*