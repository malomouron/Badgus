# Badg'us - Gestion des Badges et des Plannings

Badg'us est une application web permettant la gestion des badges des employés et la gestion des plannings avec un calcul des shifts sur les mois. Ce projet facilite la planification des horaires et le suivi des temps de travail des employés.

## Prérequis

- **PHP** version 7.4 ou plus
- **MySQL** ou **MariaDB** pour la base de données
- **Apache** ou tout autre serveur web compatible avec PHP
- Composer (facultatif, selon la gestion des dépendances)

## Installation

### 1. Cloner le dépôt

```bash
git clone https://github.com/malomouron/Badgus.git
cd badgus
```

### 2. Configuration de la base de données

1. Importez le modèle de base de données fourni [ici](https://github.com/malomouron/Badgus/blob/main/Badgus.sql) :

   - Utilisez un client MySQL ou phpMyAdmin pour importer le fichier SQL du modèle :
   
     ```bash
     mysql -u votre_utilisateur -p votre_base_de_donnees < chemin/vers/le/modele_de_base.sql
     ```

2. Configurez le fichier `config.inc.php` en remplissant les informations de connexion à la base de données :

   ```php
   <?php
      // config.inc.php
   
   	$servername = "localhost";
   	$username = "root";
   	$password = "";
   	$dbname = "";
   	$domaine = 'localhost';
   	$expediteur   = 'email@domain.com';
   	$site_key = ''; //G-capcha key
   	$os = "windos"; //[windos|linux]
   	$myprivatekey = "";
   ?>
	
   ```


### 3. Démarrage de l'application

- Assurez-vous que votre serveur web est configuré pour exécuter des scripts PHP.
- Accédez à votre projet via l'URL locale (ex. : `http://localhost/badgus/`).

### 4. Connexion

Utilisez les identifiants créés au préalable dans la base de données pour vous connecter et gérer les badges et les plannings.

## Fonctionnalités

- Gestion des badges des employés
- Planification des shifts mensuels
- Calcul des heures travaillées par mois
- Suivi des employés et gestion des plannings

## Support

Pour toute question ou problème, merci de contacter l'équipe de développement ou de créer une issue sur le dépôt GitHub.

## Contributions

Les contributions sont les bienvenues ! Si vous souhaitez apporter des améliorations ou ajouter des fonctionnalités, n'hésitez pas à ouvrir une issue ou à soumettre une pull request.
