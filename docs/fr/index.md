---
layout: home

hero:
  name: "RestoPos"
  text: "Système de Point de Vente pour Restaurants"
  tagline: "Documentation complète pour vos besoins de gestion de restaurant"
  image:
    src: /logo.svg
    alt: Logo RestoPos
  actions:
    - theme: brand
      text: Commencer
      link: /fr/getting-started/installation
    - theme: alt
      text: Voir sur GitHub
      link: https://github.com/restopos/restopos

features:
  - icon: 🏪
    title: Système POS Complet
    details: Système de point de vente complet conçu spécifiquement pour les restaurants avec gestion des commandes, traitement des paiements et suivi des stocks.
  
  - icon: 📊
    title: Analyses Avancées
    details: Rapports et analyses complets pour vous aider à comprendre les performances de votre entreprise avec des insights en temps réel et des données historiques.
  
  - icon: 👥
    title: Support Multi-Utilisateurs
    details: Contrôle d'accès basé sur les rôles avec support pour plusieurs membres du personnel, managers et administrateurs avec permissions personnalisables.
  
  - icon: 🌐
    title: Multi-Langues
    details: Support intégré pour plusieurs langues incluant l'anglais, le français et l'arabe avec support RTL pour l'interface arabe.
  
  - icon: 🔧
    title: Hautement Personnalisable
    details: Options de personnalisation étendues incluant thèmes, plugins et intégrations API pour s'adapter à vos besoins spécifiques.
  
  - icon: 📱
    title: Design Responsive
    details: Fonctionne parfaitement sur tous les appareils - ordinateur, tablette et mobile avec une interface utilisateur moderne et intuitive.
---

## Démarrage Rapide

Démarrez avec RestoPos en quelques minutes :

::: code-group

```bash [Installation]
# Cloner le dépôt
git clone https://github.com/restopos/restopos.git

# Naviguer vers le répertoire du projet
cd restopos

# Installer les dépendances
composer install
npm install

# Configurer l'environnement
cp .env.example .env
php artisan key:generate
```

```bash [Configuration Base de Données]
# Exécuter les migrations
php artisan migrate

# Insérer des données d'exemple
php artisan db:seed

# Créer le lien de stockage
php artisan storage:link
```

```bash [Développement]
# Démarrer le serveur de développement
php artisan serve

# Dans un autre terminal, démarrer Vite
npm run dev

# Accéder à votre application
# http://localhost:8000
```

:::

## Ce qui est Inclus

### 🏪 **Fonctionnalités Principales**
- **Gestion des Commandes** : Cycle de vie complet des commandes de la création à l'exécution
- **Gestion du Menu** : Création dynamique de menu avec catégories, modificateurs et tarification
- **Traitement des Paiements** : Plusieurs méthodes de paiement incluant espèces, carte et portefeuilles numériques
- **Suivi des Stocks** : Gestion des stocks en temps réel avec alertes de stock faible
- **Gestion des Clients** : Profils clients, historique des commandes et programmes de fidélité

### 📊 **Intelligence d'Affaires**
- **Rapports de Ventes** : Rapports quotidiens, hebdomadaires, mensuels et sur plages de dates personnalisées
- **Analyses de Performance** : Performance du personnel, articles populaires et analyse des heures de pointe
- **Suivi Financier** : Calculs des revenus, dépenses et marges bénéficiaires
- **Capacités d'Export** : Export PDF et Excel pour tous les rapports

### 🔧 **Fonctionnalités Techniques**
- **API RESTful** : API complète pour les intégrations tierces
- **Mises à Jour Temps Réel** : Support WebSocket pour les mises à jour de commandes en direct
- **Multi-tenant** : Support pour plusieurs emplacements de restaurants
- **Sauvegarde & Restauration** : Système de sauvegarde automatisé avec fonctionnalité de restauration facile

## Communauté & Support

- 📖 **Documentation** : Guides complets et référence API
- 💬 **Discord** : Rejoignez notre communauté pour le support et les discussions
- 🐛 **Issues GitHub** : Signaler des bugs et demander des fonctionnalités
- 📧 **Support Email** : Support direct pour les clients entreprise

## Licence

RestoPos est un logiciel open-source sous licence [MIT](https://opensource.org/licenses/MIT).