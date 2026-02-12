# :arrow_forward: :arrow_forward: Instructions d'installation :arrow_backward: :arrow_backward:

> [!IMPORTANT]
> Utilisez spécifiquement les versions précisées dans ces instructions pour que le projet fonctionne correctement. Vous pourrez mettre à jour les composantes du projet après avoir réussi à faire fonctionner le site et après l'avoir exploré.

## Table des matières
1. [Installer AMPPS](#installer-ampps)
1. [Cloner le projet](#cloner-le-projet)
1. [Configurer AMPPS](#configurer-ampps)
1. [Installer Node.js](#installer-nodejs)
1. [Installer Composer](#installer-composer)
1. [Configurer le serveur de courriels (pour l'environnement de développement)](#Configurer-le-serveur-de-courriels-pour-lenvironnement-en-développement)
1. [Importer la base de données](#importer-la-base-de-données)
1. [Configurer les variables d'environnement](#configurer-les-variables-denvironnement)
1. [Télécharger les librairies du frontend](#télécharger-les-librairies-du-frontend)
1. [Télécharger les librairies du backend](#télécharger-les-librairies-du-backend)
1. [Comment démarrer le projet en local?](#comment-démarrer-le-projet-en-local)\
    A. [Instructions pour build le site et y accéder](#a-instructions-pour-build-le-site-et-y-accéder)\
    B. [Instructions pour démarrer le React et le PHP en mode live](#b-instructions-pour-démarrer-le-react-et-le-php-en-mode-live)\
    C. [Instructions pour utiliser l'API seul (avec Postman)](#c-instructions-pour-utiliser-lapi-seul-avec-postman)
1. [Comment se connecter sur le site?](#comment-se-connecter-sur-le-site)
1. [Installer les extensions VSCode](#installer-les-extensions-vscode)
1. [Troubleshooting / Dépannage](TROUBLESHOOTING.md)
<br><br>

## Installer AMPPS

<details>
<summary>Détails</summary>
Avec AMPPS, il n'est pas possible de choisir la version PHP. Vous êtes obligés d'utiliser la dernière version disponible au moment de l'installation du logiciel. Sinon, il faut payer pour télécharger une version antérieure. (Par contre, si vous aviez déjà installé une version antérieure de PHP auparavant et que vous n'avez pas désinstallé )
</details>

1. Allez sur le site de AMPPS: (https://ampps.com/downloads/).
1. Cliquez sur le bouton `Download`.
1. Exécutez l'installateur.
1. Pendant l'installation, il va y avoir une fenêtre d'installation de « Microsoft Visual C++ 2015-2019 Redistribuable (x64) » qui va apparaitre. S'il y a un message d'erreur qui dit « Échec de l'installation. Une autre version de ce produit est déjà installée. », vous pouvez cliquer sur le bouton `Fermer`. Sinon, installez-le.
![Image fenêtre installation Microsoft Visual C++ Redistribuable](.images_documentation/ampps-vs1.png)
1. Lancez AMPPS. Une fenêtre d'installation des composants apparaitra. Cliquez sur `Continuer`.
![Image fenêtre installation composants AMPPS](.images_documentation/ampps2.png)
<br><br>

## Cloner le projet
1. Allez sur la page GitHub du projet: (https://github.com/Cours-Alexandre-Ouellet/Expo-SAT).
2. Cliquez sur le bouton `Code`, puis sur `HTTPS`, puis sur l'icône pour copier :page_facing_up:.
3. Ouvrez l'explorateur de fichiers Windows.
4. Naviguez à l'intérieur du dossier `C:\Program Files\Ampps\www`.
5. Clic droit sur un endroit vide, puis cliquez sur `Open Git Bash here`.
6. Tapez la commande `git clone` suivit de l'adresse URL que vous avez copiée à l'étape 1 de cette section. Ça devrait ressembler à ça:
```
git clone https://github.com/Cours-Alexandre-Ouellet/Expo-SAT.git
```
7. Le dossier doit s'appeler `Expo-SAT` et se situer dans `C:\Program Files\Ampps\www`.
<br><br>

## Configurer AMPPS

> [!NOTE]
> Bon. Pour la configuration de AMPPS, il y a plusieurs étapes où ça pourrait fucker. Vérifiez TOUT ce que AMPPS fait parce que souvent, il ne le fait pas. Par exemple, vous ajoutez un virtualhost, vous allez voir dans les fichiers s'il l'a bien ajouté, il ne l'a pas ajouté. Faq, vous le faites à la main finalement. Il fait presque toujours la tâche à moitié. Si je lui dis de créer deux virtualhosts, il va en créer un sur deux. Des fois, il modifie un des fichiers, mais pas l'autre. Attendez-vous à désinstaller AMPPS une couple de fois dans la session (2-3x).
>
> Des fois, AMPPS décide, du jour au lendemain, d'arrêter de fonctionner complètement. Pour aucune raison. Désinstallez-le et ça va re-marcher parfaitement après.

1. Vous devez changer les permissions d'un fichier pour que AMPPS puisse le modifier.
    1. Naviguez vers le dossier `C:\Windows\System32\drivers\etc`.
    1. Clic droit sur le fichier `hosts`. Cliquez sur Propriétés.
    1. Cliquez sur l'onglet `Sécurité`.
    1. Cliquez sur le bouton `Avancé`.
    1. Cliquez sur le bouton `Modifier` en haut à droite, près de `Propriétaire`.
    1. Cliquez sur le bouton `Avancé`.
    1. Cliquez sur le bouton `Rechercher`.
    1. Cliquez votre utilisateur ex: « Antoine (email@email.com) ».
    1. Cliquez sur `OK`.
    1. Cliquez sur `OK` encore.
    1. Cliquez sur `Appliquer`.
    1. Cliquez sur `OK`.
    1. Cliquez sur `OK` encore.
    1. Cliquez sur `OK` encore.
    1. Clic droit sur le fichier `hosts`. Cliquez sur `Propriétés`.
    1. Cliquez sur l'onglet `Sécurité`.
    1. Cliquez sur votre utilisateur.
    1. Cliquez sur `Modifier`.
    1. Cliquez sur la case à cocher `Autoriser` dans la rangée `Contrôle total`.
    1. Cliquez sur `Oui`.
    1. Cliquez sur `OK`.
    1. Cliquez sur `OK` encore.

1. Allez sur le panneau de configuration de AMPPS: (http://localhost/ampps/index.php).
1. Cliquez sur `Add domain`.
1. Faites attention de mettre les barres obliques dans ce sens-là `\` dans les prochaines étapes.\
Ne faites pas un copier-coller depuis l'explorateur de fichiers Windows.\
Vous allez ajouter deux URL: un pour le site web complet compilé (quand l'API et le React sont compilés et rassemblés dans le dossier `build`) et un pour le dossier backend\api (qui pointe vers le dossier du projet de l'API pour utiliser l'API seul sans avoir à build le site).
L'API et le React sont des projets séparés, mais ils sont dans le même repository GitHub.

1. Créez un URL pour la version compilée (build) du site:
    1. Domain: `build-exposat.com`
    1. Domain Type: `Addon`
    1. Domain Path: `C:\Program Files\Ampps\www\Expo-SAT\build`
    1. Add an SSL entry?: ne cochez pas
    1. Add an entry to Host File?: COCHEZ CETTE CASE.
    1. Cliquez sur `Add Domain`.

1. Retourner sur (http://localhost/ampps/index.php) ou cliquez sur le logo AMPPS.
1. Cliquez sur `Add domain`.
1. Créez un URL pour la version dev du site:
    1. Domain: `exposat.com`
    1. Domain Type: `Addon`
    1. Domain Path: `C:\Program Files\Ampps\www\Expo-SAT`
    1. Add an SSL entry?: ne cochez pas
    1. Add an entry to Host File?: COCHEZ CETTE CASE.
    1. Cliquez sur `Add Domain`.

1. Vous pouvez aussi vous créer un URL pour votre version personnelle du site web d'Expo-SAT si vous voulez tester des choses de votre côté sans modifier le projet. (Ce n'est pas obligatoire.)\
Clonez le projet AILLEURS, renommez-le pour `Perso-Expo-SAT`, déplacez le dossier dans `C:\Program Files\Ampps\www`, puis créez cet URL:
    1. Domain: `perso-exposat.com`
    1. Domain Type: `Addon`
    1. Domain Path: `C:\Program Files\Ampps\www\Perso-Expo-SAT\build`
    1. Add an SSL entry?: ne cochez pas
    1. Add an entry to Host File?: COCHEZ CETTE CASE.
    1. Cliquez sur `Add Domain`.
    
1. Maintenant, vous allez devoir vérifier que AMPPS a fait ça comme il faut parce que ça arrive souvent qu'il se plante.
1. Ouvrez l'explorateur de fichiers Windows.
1. Allez dans le dossier `C:\Windows\System32\drivers\etc\`.
1. Clic droit sur le fichier `hosts`.
1. Cliquez sur `Ouvrir avec`.
1. Cliquez sur `Bloc-notes`.
1. Cliquez sur `OK`.
1. Vérifiez que ces deux lignes ont été ajoutées après les commentaires:
```
127.0.0.1	build-exposat.com
127.0.0.1	exposat.com
```
18. Si elles ne sont pas toutes là, rajoutez-les (en faisant un copier-coller d'ici).

1. Ouvrez la fenêtre de l'application AMPPS:
    1. Cliquez sur la petite flèche dans votre barre de tâche Windows (« Afficher les icônes cachées »).
    1. Clic droit sur l'icône d'AMPPS.
    1. Cliquez sur `Restore`. (L'option sera grisée si la fenêtre est déjà affichée sur votre bureau.)
1. Cliquez sur l'icône de paramètres (l'engrenage) à droite de `Apache`.
1. Cliquez sur l'icône de clé à molette et tournevis (« Configuration »).
1. Cliquez sur `vHost.conf`.
1. Vérifiez que les virtualhosts ont été ajoutés correctement (build-exposat.com et exposat.com):
```
#DO NOT EDIT, CREATED BY AMPPS

			
#### localhost VirtualHost ####
			

<VirtualHost localhost:80>
<Directory "C:/Program Files/Ampps/www">
Options FollowSymLinks Indexes
AllowOverride All
# Apache 2.2
<IfModule !mod_authz_core.c>
	Order deny,allow
	allow from All
</IfModule>
# Apache 2.4
<IfModule mod_authz_core.c>
	Require all granted
</IfModule>
</Directory>
ServerName localhost
ServerAlias localhost
ScriptAlias /cgi-bin/ "C:/Program Files/Ampps/www/cgi-bin/"
DocumentRoot "C:/Program Files/Ampps/www"
ErrorLog "C:/Program Files/Ampps/apache/logs/localhost.err"
CustomLog "C:/Program Files/Ampps/apache/logs/localhost.log" combined
</VirtualHost>

####################################
			
#### build-exposat.com VirtualHost ####
			

<VirtualHost 127.0.0.1:80>
<Directory "C:/Program Files/Ampps/www/Expo-SAT/build">
Options FollowSymLinks Indexes
AllowOverride All
# Apache 2.2
<IfModule !mod_authz_core.c>
	Order deny,allow
	allow from All
</IfModule>
# Apache 2.4
<IfModule mod_authz_core.c>
	Require all granted
</IfModule>
</Directory>
ServerName build-exposat.com
ServerAlias build-exposat.com
ScriptAlias /cgi-bin/ "C:/Program Files/Ampps/www/Expo-SAT/build/cgi-bin/"
DocumentRoot "C:/Program Files/Ampps/www/Expo-SAT/build"
ErrorLog "C:/Program Files/Ampps/apache/logs/build-exposat.com.err"
CustomLog "C:/Program Files/Ampps/apache/logs/build-exposat.com.log" combined
</VirtualHost>

####################################
			
#### exposat.com VirtualHost ####
			

<VirtualHost 127.0.0.1:80>
<Directory "C:/Program Files/Ampps/www/Expo-SAT/backend/api">
Options FollowSymLinks Indexes
AllowOverride All
# Apache 2.2
<IfModule !mod_authz_core.c>
	Order deny,allow
	allow from All
</IfModule>
# Apache 2.4
<IfModule mod_authz_core.c>
	Require all granted
</IfModule>
</Directory>
ServerName exposat.com
ServerAlias exposat.com
ScriptAlias /cgi-bin/ "C:/Program Files/Ampps/www/Expo-SAT/backend/api/cgi-bin/"
DocumentRoot "C:/Program Files/Ampps/www/Expo-SAT/backend/api"
ErrorLog "C:/Program Files/Ampps/apache/logs/exposat.com.err"
CustomLog "C:/Program Files/Ampps/apache/logs/exposat.com.log" combined
</VirtualHost>

####################################
```
24. S'ils ne sont pas là, retournez sur le site `http://localhost/ampps/index.php`, supprimez les virtualhosts et recommencez. Si ça ne marche pas, modifiez le fichier à la main. Si ça ne marche pas, désinstallez AMPPS. Si ça ne marche pas, essayez MAMP ou PHP et MySQL installés manuellement.
<br><br>

## Installer Composer

<details>
<summary>Détails</summary>
Composer est un logiciel comme npm, mais pour PHP. C'est pour installer des librairies. Composer a BESOIN de 7zip pour fonctionner. Vous devrez installer 7zip avant (ou après) Composer.
</details>

1. Allez sur le site de 7zip: (https://www.7-zip.org/download.html).
1. Cliquez sur le bouton `Download` à côté de « 64-bit Windows x64 »:
![7zip Download Button](.images_documentation/7zip-website-download-button.png)
1. Double-cliquez l'installeur. Cliquez sur `Install`. Cliquez sur `Close`.
1. Allez sur le site de Composer: (https://getcomposer.org/download/).
1. Cliquez sur le bouton vraiment pas mis en évidence `Composer-Setup.exe`:
![Composer Download Button](.images_documentation/composer-website-download-button.png)
<br><br>

## Installer Node.js
1. Allez sur le site de Node.js: (https://nodejs.org/en/download).
1. Sélectionnez la version 22 LTS (Long Term Support).
1. Cliquez sur le gros bouton vert en dessous des commandes qui s'appelle: `Windows Installer (.msi)`.
![Node.js Download Button](.images_documentation/node-js-website-download-button.png)
1. Exécutez l'installeur.
1. À la page qui demande si vous voulez installer les dépendances nécessaires manuellement ou automatiquement, cliquez sur la case à cocher pour les installer automatiquement.
![Node.js installer automatic dependencies](.images_documentation/node-js-installer-dependencies.png)
1. Il va y avoir plusieurs fenêtres de commandes qui vont s'exécuter. Ce sera long. Très long. (Dépendamment si les serveurs de Microsoft sont occupés)
<br><br>

## Configurer le serveur de courriels (pour l'environnement en développement)
L'organisateur de Expo-SAT peut ajouter des personnes en tant que juge. Elle demande leur adresse courriel et ajoute le juge dans le système. Ensuite, l'organisateur peut envoyer par courriel un lien aux juges pour qu'ils puissent remplir des formulaires pour juger les équipes. Il nous faut donc un compte de courriel qui sera utilisé pour envoyer des courriels.<br>
En production, le CÉGEP a un compte de courriel qui est `exposat@cegepvicto.ca`. Le site web peut utiliser ce compte de courriel pour envoyer des courriels aux juges seulement en production. Les élèves n'ont pas d'accès administrateur sur les serveurs de l'école.<br>
En développement, on ne peut pas utiliser ce compte de courriel. Vous allez être obligé d'utiliser un de vos comptes de courriel personnel/d'école.

1. Cette exemple est pour Gmail. Allez dans les paramètres de votre compte Google (https://myaccount.google.com/).
1. Dans la barre de recherche, tapez « application » et cliquez sur « Mots de passe des applications ».
1. Dans le nom de l'application, tapez `Expo-SAT-developpement`.
1. Cliquez sur `Créer`.
1. Copiez-collez ce mot de passe de 16 lettres dans un fichier sur votre ordinateur. Si vous perdez ce mot de passe, vous allez devoir l'effacer dans la même interface et le recréer. Si vous utilisez autre chose que Gmail ce ne sera pas un mot de passe de 16 lettres. Ce sera des lettres et des chiffres.
1. Cliquez sur `Terminé`.
1. Vous entrerez ce mot de passe de 16 lettres SANS LES ESPACES dans les variables d'environnement plus tard.
<br><br>

## Importer la base de données

1. Allez sur le site (http://localhost/phpmyadmin/) (le site pour gérer les BD sur AMPPS).
1. À l'écran de connexion, le nom d'utilisateur est `root` et le mot de passe est `mysql` (pour AMPPS).
1. Cliquez sur `Nouvelle base de données` dans la barre latérale à gauche.
1. Le nom de la base de données doit être: `exposat`.
![phpMyAdmin Create Database](.images_documentation/phpmyadmin-create-database.png)
1. Cliquez sur l'onglet `Importer`.
1. Cliquez sur le bouton `Choisir un fichier`.
1. Sélectionnez le fichier `exposat.sql` qui est dans le dossier `Expo-SAT`.
1. Cliquez sur le bouton `Importer` qui est en bas de la page.
![phpMyAdmin Import Tables](.images_documentation/phpmyadmin-import-tables.png)
Résultat:\
![phpMyAdmin Result Tables](.images_documentation/phpmyadmin-tables.png)
<br><br>

## Configurer les variables d'environnement

> [!IMPORTANT]
> Les fichiers de variables d'environnement ne sont pas présents. Vous devez les créer vous-mêmes.

### `front\.env` (frontend)
1. Ouvrez le fichier `.env.example` qui se trouve dans le dossier `front`.
1. Lisez les explications qui s'y trouvent.
1. Créer un nouveau fichier qui s'appelle `.env` dans le dossier `front`. Il doit absolument s'appeler `.env`.
1. Coller les variables d'environnement de développement.
1. `VITE_BASE_URL` c'est l'URL qui correspond à celui que vous avez mis dans AMPPS. Donc ça devrait être `http://build-exposat.com/`.

> [!IMPORTANT]
> VOUS DEVEZ ABSOLUMENT METTRE LE MÊME LIEN QUE DANS LES VARIABLES D'ENVIRONNEMENT DU BACKEND SINON VOUS NE SEREZ PAS CAPABLE D'ACCÉDER À L'API (CORS).

La ligne devrait ressembler à ça:
```
VITE_BASE_URL=http://build-exposat.com/
```
<br>

### `backend\api\.env.prod` (frontend)
1. Ouvrez le fichier `.env.example` qui se trouve dans le dossier `backend/api`.
1. Lisez les explications qui s'y trouvent.
1. Créer un nouveau fichier qui s'appelle `.env.prod` dans le dossier `backend/api`. Il doit absolument s'appeler `.env.prod`.
1. `user_mail_server` c'est l'adresse courriel qui va ENVOYER les courriels aux juges. Changez l'adresse courriel de `user_mail_server` pour votre adresse courriel personnelle que vous avez utilisée pour les étapes du SMTP.
1. `test_email_receiver` c'est l'adresse courriel qui va RECEVOIR les courriels quand vous exécutez les tests. Lorsque vous allez créer un juge fictif, vous entrerez votre adresse courriel pour recevoir des grilles d'évaluations.
1. `base_url` c'est l'URL qui correspond à celui que vous avez mis dans AMPPS. Donc ça devrait être `http://build-exposat.com/`.

> [!IMPORTANT]
> VOUS DEVEZ ABSOLUMENT METTRE LE MÊME LIEN QUE DANS LES VARIABLES D'ENVIRONNEMENT DU FRONTEND SINON VOUS NE SEREZ PAS CAPABLE D'ACCÉDER À L'API.

La ligne devrait ressembler à ça:
```
VITE_BASE_URL=http://build-exposat.com/
```
1. Coller les variables d'environnement de développement.
1. `dbname` c'est le nom de la bd. Mettez `exposat` quand vous êtes sur votre ordinateur et changez-le quand vous vous apprêtez à mettre le site en ligne.
1. `dbhost` c'est l'URL pour accéder à la BD. Normalement, c'est sensé être `localhost`.
<br><br>

## Télécharger les librairies du frontend

<details>
<summary>Informations</summary>
Le frontend du site web est en React et TypeScript.
L'outil utilisé pour gérer les librairies du frontend est npm.
Il est important de seulement faire `npm install` et pas `npm update` pour utiliser les mêmes versions des librairies que nous au départ. `npm update` va installer les versions les plus récentes des librairies et ça va tout briser le projet. Vous pourrez mettre à jour les librairies plus tard. Quand on fait des mises à jour, il se peut qu'il y ait des parties du code qui ne fonctionnent plus avec la nouvelle version. Il faudra peut-être utiliser les nouvelles méthodes des librairies, changer les paramètres des méthodes ou même devoir changer complètement le code.

Si une librairie n'est plus supportée ou possède des vulnérabilités de sécurité, il faudra surement la remplacer par une autre librairie équivalente (et ajuster le code avec les méthodes de la nouvelle librairie).
</details>

1. Ouvrez le terminal intégré dans VSCode en cliquant dans la barre de menus sur « ... », puis sur « Terminal », puis sur « Nouveau terminal » (ou <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>`</kbd> avec un clavier anglais).
1. Tapez la commande `cd .\front`, puis appuyez sur la touche <kbd>Enter</kbd>.
1. Tapez la commande `npm install`, puis appuyez sur la touche <kbd>Enter</kbd>.
<br><br>

## Télécharger les librairies du backend

<details>
<summary>Informations</summary>
Le backend du site web est codé en PHP.
L'outil utilisé pour gérer les librairies du backend est composer. Composer c'est un peu comme npm, mais pour PHP.
Il est important de seulement faire `composer install` et pas `composer update` pour utiliser les mêmes versions des librairies que nous au départ. `composer update` va installer les versions les plus récentes des librairies et ça va tout briser le projet. Vous pourrez mettre à jour les librairies plus tard quand vous aurez réussi à faire fonctionner le site.
</details>

1. Ouvrez le terminal intégré dans VSCode en cliquant dans la barre de menus sur « ... », puis sur « Terminal », puis sur « Nouveau terminal » (<kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>`</kbd>).
1. Tapez la commande `cd .\backend\api`, puis appuyez sur la touche <kbd>Enter</kbd>.
1. Tapez la commande `composer install`, puis appuyez sur la touche <kbd>Enter</kbd>.
<br><br>

## Comment démarrer le projet en local?

Le site web peut être sous différentes formes.

Le projet de l’API en PHP peut être exécuté de façon indépendante.\
Le projet du front end en React peut être exécuté de façon indépendante.

Ce sont deux projets différents, mais ils sont regroupés dans le même dossier Expo-SAT.

Il est possible d’utiliser l’API sans le React avec Postman. Cependant, vous aurez besoin d’une clé d’API pour pouvoir faire des requêtes avec Postman. Cette clé d’API est utilisée automatiquement par le front end. Vous trouverez plus loin les instructions d’utilisation de l’API avec Postman.

De son côté, le front end React peut être démarré de façon indépendante, mais vous ne pourrez pas faire grand-chose. Vous verrez la page d’accueil et vous pourrez naviguer vers la page de connexion ou la page d’inscription des participants, mais dès que vous allez faire une action qui nécessite de communiquer avec l’API, vous aurez des messages d’erreur vu que l’API n’est pas accessible.

Bref, il y a trois formes du site web :

### • Le site web compilé

Le site web compilé est quand vous compilez le site en un dossier appelé `build` qui combine le PHP et le React. Le TypeScript est converti en JavaScript et est bundled dans le dossier `build`.\
**Le site web buildé est la version qui fonctionne le mieux.** Si vous avez des problèmes à faire fonctionner le site avec la méthode où les changements sont live (npm run start), essayez de build le site et ça devrait fonctionner. Le dossier `build`, c’est aussi comme ça qu’on utilise le site en production. On met le contenu du dossier `build` sur l’hébergeur. Pour compiler le back end et le front end dans le dossier `build`, il y a un script qui s’occupe de combiner les deux projets ensemble. Vous n’avez qu’à exécuter le script PowerShell `.\build.ps1` et le dossier build sera créé.

> [!CAUTION]
> TRÈS IMPORTANT! Vous devez faire .\build.ps1 À CHAQUE FOIS QUE VOUS MODIFIEZ LE CODE. Sinon, les modifications ne seront pas prises en compte et vous ne comprendrez pas pourquoi ça ne fonctionne pas. Ça arrive souvent qu’on oublie de faire .\build.ps1.

> [!TIP]
> Cette méthode est la plus fiable!!! Si `npm run start` ne fonctionne pas ou que vous avez des erreurs incompréhensibles, utilisez cette méthode. On a été obligé toute la session de faire .\build.ps1 à chaque fois qu’on modifiait le code. À – Chaque – Fois – qu’on modifiait le code, il fallait rebuild le site.

> [!WARNING]
> Quand vous compilez le site web, le code devient obfuscated. C’est-à-dire que les noms des méthodes sont changés pour des caractères aléatoires pour rendre le code plus difficile à comprendre pour les hackeurs qui essaient de reverse-engineer votre site. Même chose pour les noms de variables, etc. Donc, quand vous essayez de déboguer cette version du site dans le navigateur web, vous allez remarquer que les messages d’erreurs dans la console sont difficiles à comprendre parce qu’ils disent par exemple : « An error has been thrown on line 317 of the method xblfwq(). »

### • Le site web en mode live

Le serveur vite est quand vous lancez le React seulement avec npm run start. Si vous regardez dans le fichier package.json, la commande npm run start exécute la commande vite qui démarre un serveur vite qui met le site à jour dans votre navigateur web à chaque fois que vous modifiez le code. Donc vous pouvez tester vos composants React sans avoir à build le site.

Ça n’a pas l’air très fiable comme méthode. Donc, si vous avez des problèmes ou des erreurs incompréhensibles, faites juste utiliser la méthode de build le site.

### • L'API seul

L’API peut être exécuté seul sur AMPPS. Vous n’avez qu’à accéder à l’URL (que vous avez créé dans les virtualhosts de AMPPS) qui pointe vers le dossier `backend\api`. Vu que l’API est en PHP et que AMPPS interprète le PHP, le code de l’API peut être testé sans avoir à compiler. Le serveur AMPPS run toujours. Vous n’avez pas besoin de faire de commande pour démarrer l’API. (À condition que vous avez démarré AMPPS bien sûr) Quand AMPPS fonctionne, tous les virtualhosts sont accessibles.
<br><br>

### A. Instructions pour build le site et y accéder

> [!CAUTION]
> À chaque fois que vous changez de branches ou que vous faites git pull, n’oubliez pas de faire npm install dans le dossier front et composer install dans le dossier backend\api pour installer exactement les versions des librairies spécifiées dans package.json et composer.json. Si sur votre branche vous utilisez par exemple MUI v7.1 et que votre collègue utilise MUI v7.5, vous allez avoir les fichiers de la version 7.1 dans votre dossier node_modules, mais le code va s’attendre à ce que vous ayez les fichiers de la version 7.5, alors vous aurez des erreurs incompréhensibles.
>
> Faites :\
> `cd front`\
> `npm install`\
> `cd ..`\
> `cd backend\api`\
> `composer install`\
> `cd ..`\
> `cd ..`

1. Assurez-vous que AMPPS est démarré.
1. Changez les URLs dans les deux fichiers de variables d'environnement. Il y a une barre oblique à la fin pour le front end, mais pas pour le backend:
```
VITE_BASE_URL=http://build-exposat.com/
CYPRESS_BASE_URL=http://build-exposat.com/
```
```
base_url=http://build-exposat.com
```
3. Ouvrez le terminal intégré dans VSCode en cliquant dans la barre de menus sur `...`, puis sur `Terminal`, puis sur `Nouveau terminal`\
(ou <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>\`</kbd>\
ou <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>é</kbd>).\
C’est beaucoup plus vite que d’ouvrir une fenêtre de terminal, car vous pouvez la cacher avec le `X`.
1. Assurez-vous d’être dans le dossier `Expo-SAT`, pas `front` ou `backend\api` ou `C:\Windows\System32`. Faites `cd ..` si vous êtes dans le dossier `front`. Faites `cd..` <kbd>Enter</kbd> et encore `cd..` <kbd>Enter</kbd> si vous êtes dans le dossier `backend\api`.
1. Exécutez le script PowerShell `build.ps1` en tapant la commande : `.\build.ps1`.
1. Allez sur votre navigateur web et tapez l’URL qui pointe vers le dossier build: http://build-exposat.com.

<details>
<summary>Explications</summary>
Ce script exécute 2 commandes: `tsc` et `vite build`.<br>

`tsc`:<br>
tsc, c'est un peu comme le compilateur de TypeScript. Il transforme le code TypeScript en code JavaScript parce qu'on ne peut pas exécuter un fichier `.ts` directement, il faut toujours le convertir en `.js` avant.

`vite build`:<br>
Voici un parallèle: Quand vous codez en C#, vous ne pouvez pas exécuter un fichier `.cs` directement. Il faut compiler le projet avant. En compilant, on transforme tous les fichiers `.cs` en un fichier `.exe` qui est exécutable.
C'est un peu le même principe avec `vite build`. `vite build` prend tous les fichiers javascript, css, html, etc. et crée un package qui peut être exécuté sur un serveur ou chez un hébergeur. Il arrange les fichiers dans une façon qui est comprise par le navigateur web et crée les fichiers et les dossiers requis pour le navigateur web (comme index.html, .htaccess). Si on regarde la signification de la commande `vite build`, le build veut dire qu'on build le site web ou en d'autres mots, qu'on crée le projet pour l'environement de production. Le résultat de `vite build` est qu'il crée le site web dans le dossier build à la racine du projet.
Avec le script PowerShell `build.ps1`, le frontend ET le backend sont build dans le dossier `build`. Le contenu de ce dossier peut tout simplement être envoyé sur l'hébergeur du site en ligne.
</details>
<br>

### B. Instructions pour démarrer le React et le PHP en mode live

1. Assurez-vous que AMPPS est démarré.
1. Changez les URLs dans les deux fichiers de variables d'environnement. Il y a une barre oblique à la fin pour le front end, mais pas pour le backend:
```
VITE_BASE_URL=http://exposat.com/
CYPRESS_BASE_URL=http://exposat.com/
```
```
base_url=http://exposat.com
```
3. Ouvrez le terminal intégré dans VSCode en cliquant dans la barre de menus sur `...`, puis sur `Terminal`, puis sur `Nouveau terminal`\
(ou <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>\`</kbd>\
ou <kbd>Ctrl</kbd> + <kbd>Shift</kbd> + <kbd>é</kbd>).
1. Changez vers le dossier `front`, pas `Expo-SAT` ou `backend\api` ou `C:\Windows\System32`. Faites `cd front` si vous êtes dans le dossier `Expo-SAT`. Faites `cd..` <kbd>Enter</kbd> et encore `cd..` <kbd>Enter</kbd> et `cd front` si vous êtes dans le dossier `backend\api.
1. Démarrez le serveur Vite avec la commande `npm run start`.
1. Allez sur votre navigateur web et tapez l’URL http://exposat.com:5173.

<details>
<summary>Explications</summary>
AMPPS, c’est PHP, donc on pointe vers le dossier de l’API (en PHP) avec le virtualhost exposat.com. Le virtualhost exposat.com pointe vers le dossier `backend\api`. Donc, quand on accède à http://exposat.com, ça pointe vers l’API.

Vite est capable de démarrer un serveur web pour le React avec l’URL de notre choix. Donc, on peut dire à Vite d’utiliser l’URL http://exposat.com:5173 pour le React. Vous pouvez voir dans le fichier `package.json` que quand on tape la commande `npm run start`, ça exécute la commande `vite --host exposat.com`. Si vous faites `npm run start` vous allez voir dans la console que Vite a effectivement démarrer un serveur web à l’URL http://exposat.com, mais il le démarre toujours sur le port 5173. Donc, c’est un port différent du PHP (80).

Donc, ce que ça donne, c’est que l’URL du backend et l’URL du frontend sont le même (à l’exception du port), donc ça fait en sorte que CORS ne bloque pas les requêtes!
</details>
<br>

### C. Instructions pour utiliser l'API seul (avec Postman)

> [!WARNING]
> Pour accéder à la plupart des routes de l'API, il faut être administrateur du système. Donc, vous aurez besoin de fournir un token dans Postman pour prouver que vous êtes authentifié. N’oubliez pas!

1. Assurez-vous que AMPPS est démarré.
1. Ouvrez Postman.
1. Cliquez sur `Collection`.
1. Cliquez sur le `+` pour créer une collection de requêtes.
1. Cliquez sur `Blank Collection`.
1. Tapez son nom. Appelez-la : `build-exposat.com (prod)`.

> [!NOTE]
> Pourquoi des collections? C'est pour sauvegarder les requêtes que vous allez utiliser souvent. Vous pourrez aussi créer une collection « exposat.com (dev) » pour faire des requêtes API au PHP (dossier `backend\api`) sans avoir à build le site. Suivez les mêmes étapes (1-40) que pour créer la collection `build-exposat.com (prod)`, mais remplacez le nom de la collection par `exposat.com (dev)` et tous les URL devront commencer par `http://exposat.com/api/`.

7. Cliquez sur `Authorization` (c'est ici que vous ajouterez plus tard le token qui sera utilisé pour toutes les requêtes que vous allez créer dans cette collection).
1. Dans la liste déroulante `Auth type`, sélectionnez `Bearer Token`.
1. Assurez-vous d'avoir build le site avant d'essayer d'y accéder (`.\build.ps1`).
1. Ouvrez votre navigateur web et allez sur le site build d’Expo-SAT http://build-exposat.com.
1. Testez que la connexion en tant qu'administrateur fonctionne et qu'il n'y a pas de message d'erreur comme: « La communication avec le serveur a échoué. »
1. Dans Postman, cliquez sur `Add a request` en dessous de votre collection `build-exposat.com (prod)`.\
(Vous devez la dérouler si elle n'est pas déjà déroulée. Sinon, vous pouvez sélectionner la collection, puis survoler la collection avec votre souris et cliquer sur les `...` et finalement sur `Add request`.)
1. Dans l'URL, tapez `http://build-exposat.com/api/token`.
1. Cliquez sur le `GET` et changez-le pour `POST`.
1. Cliquez sur l'onglet `Authorization`.
1. Sélectionnez `No Auth` seulement pour cette requête.
1. Cliquez sur l'onglet `Body`.
1. Cliquez sur l'option `raw`.
1. À droite des options, cliquez sur le menu déroulant et sélectionnez `JSON`.
1. Dans la zône de texte pour le body en dessous, fournissez les informations de connexion d'un administrateur présent dans la BD:
```
{
    "email": "test@letesteur.test",
    "password": "123elite"
}
```
21. Cliquez sur le bouton `Send`.
1. Au-dessus de la réponse, cliquez sur la liste déroulante `HTML` et changez-le pour `JSON`.
1. La réponse devrait ressembler à ça:
```
{
    "message": [
        "Nous avons trouvé votre token !"
    ],
    "content": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9leHBvc2F0LmNvbSIsImF1ZCI6Imh0dHA6XC9cL2V4cG9zYXQuY29tIiwianRpIjoiMSIsImlhdCI6MTc0ODIwNjMyMSwicm9sZV9pZCI6MCwibmJmIjoxNzQ4MjA2MzgxLCJleHAiOjE3NDgyMDk5MjF9.BR83_bDvKmc9ACBD0YChH1UVNuU8zoDOdLQJJusYT3E"
}
```
24. Copiez TOUT le token qui se trouve entre les guillemets du `content`. Il est trop long donc il dépasse sur les lignes d'en dessous.
1. Ouvrez le panneau latéral `Collections`.
1. Cliquez sur le titre de la collection `exposat.com (dev)`.
1. Cliquez sur l'onglet `Variables`.
1. Dans la colonne `Variable`, donnez un nom à la variable comme `TokenAdministrateur`.
1. Dans la colonne `Current value`, collez le token de l'administrateur que vous aviez copié.
1. Cliquez sur l'onglet `Authorization`.
1. Cliquez dans le champ texte `Token`.
1. Tapez `{{TokenAdministrateur}}`.
1. Faites <kbd>Ctrl</kbd> + <kbd>S</kbd> souvent pour sauvegarder vos modifications dans Postman. Quand il y a un point orange sur un onglet, c'est qu'il n'a pas été sauvegardé. Un point vert veut dire qu'il y a du contenu dans cet onglet.

> [!CAUTION]
> Si vous cliquez sur le `+` dans la barre des onglets tout en haut, vous allez créer un onglet qui n'est pas dans votre collection. Donc, vous n'aurez pas le token dans vos requêtes. Vous pouvez cliquer sur le `+` dans la barre des onglets tout en haut, mais tout de suite après, vous devrez faire <kbd>Ctrl</kbd> + <kbd>S</kbd> et sélectionner la collection.

34. Ouvrez le panneau latéral `Collections`, faites clic droit sur le titre de la collection `exposat.com (dev)` et cliquez sur `Add request`

> [!CAUTION]
> N'oubliez pas le `/api/` dans votre URL. Sinon, vous allez pointer sur le React.

35. Dans l'URL, tapez `http://build-exposat.com/api/administrators/all`.
1. Dans l'onglet `Authorization`, assurez-vous que le `Auth Type` est `Inherit auth from parent` pour récupérer le `TokenAdministrateur`. La valeur dans le champ texte `Token` devrait être `{{TokenAdministrateur}}`.
1. Cliquez sur le bouton `Send`.
1. Au-dessus de la réponse, cliquez sur la liste déroulante `HTML` et changez-le pour `JSON`.
1. La réponse devrait ressembler à ça:
```
{
    "message": [
        "Les administrateurs ont été trouvés avec succès."
    ],
    "content": [
        {
            "id": 1,
            "email": "test@letesteur.test"
        }
    ]
}
```
40. Faites <kbd>Ctrl</kbd> + <kbd>S</kbd> pour sauvegarder la requête.
<br><br>

## Comment se connecter sur le site?
1. Cliquez sur le bouton `connexion` sur le site web.
1. Voici les informations de connexion en local:<br>
```
Nom d'utilisateur: test@letesteur.test
Mot de passe: 123elite
```

> [!CAUTION]
> Faites attention de ne pas exporter une BD vers le site en production dans laquelle il reste des utilisateurs avec des mots de passe faibles !!!<br>
> C'est une opportunité pour les hackers. Enlevez les utilisateurs de test avant d'envoyer en production.
<br><br>

## Installer les extensions VSCode

<details>
<summary>Explications</summary>
Lorsque vous allez ouvrir VSCode, vous allez voir une notification, puisqu'il y a un fichier `extension.json` dans le dossier `.vscode` pour lister les extensions sugérées. Ce sont des extensions suggérées par une des équipes précédentes. Honnêtement, nous n'avons pas vraiment touché à ça. Il y en a qui font la même chose, alors ne les installez pas toutes. Au début de la session, j'ai installé « PHP » par DEVSENSE, mais j'ai trouvé vraiment gossant qu'il y ait plein de fonctionnalités Premium.
</details>

1. Lorsque vous allez ouvrir VSCode, vous allez voir une notification. Si la notification n'est plus là, regardez si elle est dans la zône des notifications en cliquant sur la cloche en bas à droite, comme vous pouvez le voir dans l'image suivante.
![VSCode Extensions Notification](.images_documentation/VSCode-extensions-notification.png)
1. Cliquez sur le bouton `Afficher les recommandations`.
1. Installez « PHP » par DEVSENSE ou une alternative.
1. Installez d'autres extensions si vous voulez.
1. Fermez VSCode.
1. Re-ouvrez VSCode.
1. La maudite notification est encore là! :angry:
1. Cliquez sur l'icône de paramètres (la roue dentée) dans la notification.
![VSCode Disable VERY Annoying Notification](.images_documentation/VSCode-VERY-annoying-notification.png)
1. Cliquez sur `Ne plus afficher pour ce référentiel`.
<br><br>

# Mettez à jour cette documentation s'il y a des changements, si les versions changent ou si les logiciels changent.