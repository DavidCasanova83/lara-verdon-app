#!/bin/bash

echo "🚀 Configuration Git pour lara-verdon-app"
echo "=========================================="

# Initialiser Git si pas déjà fait
if [ ! -d ".git" ]; then
    echo "📦 Initialisation du repository Git..."
    git init
else
    echo "✅ Repository Git déjà initialisé"
fi

# Créer/mettre à jour .gitignore
echo "📝 Création du .gitignore..."
cat > .gitignore << 'EOF'
# Laravel
/node_modules
/public/build
/public/hot
/public/storage
/storage/*.key
/vendor
.env
.env.backup
.env.production
.phpunit.result.cache
Homestead.json
Homestead.yaml
auth.json
npm-debug.log
yarn-error.log
/.fleet
/.idea
/.vscode

# Database
*.sqlite
*.sqlite-journal

# OS
.DS_Store
Thumbs.db

# Logs
storage/logs/*.log

# Cache
bootstrap/cache/*.php
storage/framework/cache/*
storage/framework/sessions/*
storage/framework/testing/*
storage/framework/views/*

# Temporary files
*.tmp
*.temp
test_*.php
EOF

echo "📁 Ajout des fichiers au staging..."
git add .

echo "💬 Premier commit..."
git commit -m "🎉 Initial commit: Application formulaire touristique Verdon Tourisme

✨ Fonctionnalités:
- Formulaire 3 étapes avec Livewire
- 5 villes touristiques (La Palud, Saint-André, Colmars, Entrevaux, Annot)
- Validation RGPD et consentements
- Statistiques avec Chart.js
- Design responsive Tailwind CSS

🛠️ Stack technique:
- Laravel 12 + Livewire 3
- Tailwind CSS 4.0
- SQLite + Chart.js
- Police Atkinson

🤖 Generated with Claude Code (claude.ai/code)"

echo ""
echo "🔗 Pour connecter à GitHub, exécutez:"
echo "git remote add origin https://github.com/VOTRE_USERNAME/lara-verdon-app.git"
echo "git branch -M main"
echo "git push -u origin main"
echo ""
echo "✅ Git configuré avec succès!"
EOF

chmod +x setup_git.sh