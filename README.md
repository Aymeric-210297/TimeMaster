# 📅 Projet horaires

## 🌟 À propos du projet

Cette plateforme est conçue pour automatiser la création d'horaires dans les établissements scolaires et faciliter le suivi des absences et des retards des élèves et des professeurs. Les utilisateurs, qu'ils soient élèves ou professeurs, peuvent accéder à leurs horaires via des comptes administrés par les responsables de l'établissement.

Réalisé à des fins éducatives dans le cadre d'un cours d'informatique, ce projet possède des fonctionnalités spécifiquement adaptées à un usage défini et n'est pas destiné à être universellement adaptable à tous types d'établissements ou d'environnements scolaires. Son développement a un but purement démonstratif.

## 🔧 Installation

### Prérequis

- [PHP](https://www.php.net/downloads.php) (v8.3.6 ou supérieur)
- [MySQL](https://dev.mysql.com/downloads/mysql/) (v8.2.0 ou supérieur)
- [Composer](https://getcomposer.org/download/)

### Préparation

1. Lancez le fichier SQL `database.sql` sur votre serveur MySQL pour créer la base de données et les tables nécessaires au fonctionnement du projet.

2. Clonez ce répertoire sur votre machine locale :

    ```bash
    git clone https://github.com/Aymeric-210297/projet-horaires.git
    ```

3. Accédez au répertoire du projet :

    ```bash
    cd projet-horaires
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

### Lancement

Configurez votre serveur web pour servir le projet dans le dossier `public/` ou alors lancez un serveur de développement PHP :

```bash
php -S localhost:3000 -t public
```

## ⚠ Avertissement

Ce projet est réalisé à des fins éducatives dans le cadre d'un cours d'informatique. **Ne l'utilisez pas en production**, car il peut présenter des vulnérabilités de sécurité, des problèmes de performance et ne suit pas nécessairement les meilleures pratiques de développement.

Les auteurs de ce projet ne sont pas responsables des dommages, directs ou indirects, résultant de l'utilisation de ce projet. Utilisez-le à vos risques et périls.
