# 📅 TimeMaster

## 🌟 À propos du projet

Cette plateforme est conçue pour automatiser la création d'horaires dans les établissements scolaires et faciliter le suivi des absences et des retards. Les élèves et les professeurs peuvent accéder à leurs horaires et déclarer leurs indisponibilités via des comptes administrés par les responsables de leur établissement.

Réalisé à des fins éducatives dans le cadre d'un cours d'informatique, ce projet possède des fonctionnalités spécifiquement adaptées à un usage précis et n'est pas destiné à être adaptable à tous types d'établissements scolaires. Son développement a un but purement démonstratif.

## 🔧 Installation

### Prérequis

- [PHP](https://www.php.net/downloads.php) (v8.3.6 ou supérieur)
- [MySQL](https://dev.mysql.com/downloads/mysql/) (v8.2.0 ou supérieur)
- [Composer](https://getcomposer.org/download/)
- [Font Awesome Kit](https://docs.fontawesome.com/web/setup/use-kit) (v6)

### Préparation

1. Exécutez le fichier SQL `database.sql` sur votre serveur MySQL pour créer la base de données et les tables nécessaires au fonctionnement du projet.

2. Clonez ce projet sur votre machine :

    ```bash
    git clone https://github.com/Aymeric-210297/TimeMaster.git
    ```

3. Accédez au répertoire du projet :

    ```bash
    cd TimeMaster
    ```

4. Configurez les variables d'environnement :
   - Copiez le fichier `.env.example` en `.env` :

     ```bash
     cp .env.example .env
     ```

   - Ouvrez le fichier `.env` et complétez les valeurs nécessaires en vous basant sur les données d'exemple.

5. Installez les dépendances du projet :

    ```bash
    composer install
    ```

**Note :** Si nécessaire, activez l'extension `pdo_mysql` dans votre configuration PHP.

### Données de test (facultatif)

Pour tester le projet, vous pouvez utiliser le script de génération de données fourni :

```bash
php scripts/Faker.php
```

Le script crée un compte de test avec les informations suivantes :

- Email : `test@example.com`
- Mot de passe : `test`

**Note :** Ce script simule des données pour l'application et ne doit être exécuté que dans un environnement de test.

### Lancement

Configurez votre serveur web pour servir le projet dans le dossier `public/` ou alors lancez un serveur de développement PHP :

```bash
php -S localhost:3000 -t public
```

## ⚠️ Avertissement

Ce projet est réalisé à des fins éducatives dans le cadre d'un cours d'informatique. **Ne l'utilisez pas en production**, car il peut présenter des vulnérabilités de sécurité, des problèmes de performance et ne suit pas nécessairement les meilleures pratiques de développement.

Les auteurs de ce projet ne sont pas responsables des dommages, directs ou indirects, résultant de l'utilisation de ce projet. L'utilisation de ce projet se fait aux risques et périls de l'utilisateur.
