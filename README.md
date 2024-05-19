# projet-horaires

## Installation

### Prérequis

- [PHP](https://www.php.net/downloads.php) (8.3.6)
- [MySQL](https://dev.mysql.com/downloads/mysql/) (8.2.0)
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

4. Copiez le fichier `.env.example` et renommez le nouveau fichier `.env`.

5. Modifiez le fichier `.env` pour configurer la connexion à votre base de données.

6. Installez les dépendances du projet :

    ```bash
    composer install
    ```

7. Vous pourriez avoir besoin d'activer l'extension `pdo_mysql` sur votre PHP si ce n'est pas déjà fait.

### Lancement

Configurez votre serveur web pour servir le projet dans le dossier `public/` ou alors lancez un serveur de développement PHP :

```bash
php -S localhost:3000 -t public
```
