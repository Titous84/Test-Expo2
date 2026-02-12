# Le back-end d'ExpoSAT

Le back-end d'ExpoSAT est réalisé avec [PHP Slim](https://www.slimframework.com).

## Cheat sheet des commandes

###### Installer les dépendances : `composer install`.

###### Mettre à jour les dépendances : `composer update`.

###### Générer la documentation : `php phpDocumentor.phar -d ./src -t ./dossier-destination`.

###### Éxécuter les tests PHPUnit : `./vendor/bin/phpunit ./tests/`.

## Structure des dossiers et fichiers importants

- public
  - index.php -> Contient la configuration de Slim v4. Les routes de l'api sont définies ici.
  - Les images, fichiers css et js seront ajoutés dans ce dossier.
- src
  - Actions -> Contient les entry-point pour les routes de l'API.
  - Enums -> Enumération PHP utilisé dans le code.
  - Handlers -> Gestionnaire des erreurs et du logging.
  - Middlewares -> Gère les requêtes avant qu'elle soit traités par les actions.
  - Models -> Classe représentant un objet de la base de données.
  - Repositories -> Classe PHP créant le pont entre la bdd et le reste du code.
  - Services -> S'occupe de vérifier si la forme de la requête est valide et gère les erreurs des repositoires.
- tests
  - Tous les tests seront créer ici avec PHPUnit.
- vendor
  - Tous les paquets installés par 'composer install' sont installés ici.
- log
  - Les erreurs PHP seront enregistrés ici.
- .env.example
  - Fichier créant des variables d'environnement dans PHP. Vous devez le configurer plus tard dans ce README.md.
- composer.json
  - Contient les informations concernant le projet Composer.
- phpDocumentator.phar
  - Permet de créer la documentation du projet.

## Tester les changements effectués au back-end

Pour tester les changements au back-end il est nécéssaire d'éxécuter le script de compilation du projet, veuillez vous référer à la section `Exécution du script de compilation` dans le fichier `readme.md` situé à la racine du projet pour plus d'information.

## Exécution des tests

Les tests du back-end utilisent la librairie [PHPUnit](https://phpunit.de). Deux options s'offrent à vous pour éxécuter les tests :

###### Le Test Explorer de Visual Studio Code ( Recommendé ) :

Des fichiers de configuration pour certains plugins VSCode sont fournis dans le dossier .vscode à la racine du projet. Ouvrir le dossier racine du projet dans vscode comme espace de travail chargera automatiquement ces configurations. Pour tester le front-end, il sera nécéssaire d'installer les extensions suivantes:

**Vous pouvez rechercher ceux-ci simplement en entrant `@recommended` dans la boite de recherche du menu Extensions ! Toutes les extensions recommendées apparaitront sous l'onglet `Workspace Recommendation`.**

* PHP: `DEVSENSE.phptools-vscode`
* PHPUnit: `emallin.phpunit`

Les tests du back-end apparaitront ensuite automatiquement sous l'onglet `Testing` de VSCode. Ils apparaitront sous la section `PHPUnit Tests`.

###### L'invite de commande :

Il est possible de lancer les tests back-end avec l'invite de commande (`shell`, `cmd`, `powershell`) si vous n'utilisez pas VSCode .

1. Naviguer vers le dossier `backend/api/`
2. Éxécuter la commande `./vendor/bin/phpunit ./tests/`.

Assurez-vous que tous les tests fonctionnent **avant** de faire une pull-request !
