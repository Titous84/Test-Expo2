# Site web d'Expo-SAT

## Table des matières

1. [Comment débuter?](#comment-débuter)
1. [Instructions d'installation](DOCUMENTATION/DocumentationDeveloppeurs/INSTALLATION.md)
1. [Troubleshooting (FAQ)](DOCUMENTATION/DocumentationDeveloppeurs/TROUBLESHOOTING.md)
1. [Manuel d'utilisation du site](DOCUMENTATION/DocumentationUtilisateurs/Manuel_utilisation_2025.docx)
1. [Explication du code du front end](DOCUMENTATION/DocumentationDeveloppeurs/FRONTEND.md)
1. [Explication du code du back end](DOCUMENTATION/DocumentationDeveloppeurs/BACKEND.md)
1. [Liste de tâches à faire](#ce-quil-reste-à-faire-liste-de-tâches)
1. [Contributions](#contributions)

> [!CAUTION]
> LISEZ BIEN TOUTES LES DOCUMENTATIONS QUE NOUS VOUS AVONS FOURNIES. Vous pourriez manquer des détails importants.

## Comment débuter?
Ce projet est très gros. Alors, nous vous conseillons très fortement de suivre cet ordre avant d'essayer de commencer à coder:

#### 1. Installez le projet
Commencez par lire les instructions d'installation pour configurer le projet sur votre ordinateur. Nous avons créé un document TROUBLESHOOTING.md où nous avons documenté toutes les erreurs que nous avons rencontrées et des explications pour les résoudre. Ce fichier ne documente pas seulement les erreurs par rapport à l'installation. Il comporte aussi les autres erreurs en général.

#### 2. Lisez le manuel d'utilisation
Le manuel d'utilisation est un document qui explique comment utiliser le site à un utilisateur. Cela vous donnera un aperçu global des fonctionnalités du site.

#### 3. Explorez le site
Naviguez dans le site pour voir à quoi les pages ressemblent et essayez les fonctionnalités comme les grilles d'évaluation pour les juges.

#### 4. Choisissez des fonctionnalités sur lesquelles vous allez vous concentrer
Un conseil de la part de notre équipe qui a l'expérience d'avoir travaillé sur le projet: n'essayez pas de comprendre comment TOUT le code fonctionne. C'est ce qu'on a essayé de faire. On a perdu plusieurs mois à faire ça, ce qui n'était pas productif. Lisez la liste des tâches qu'il reste à faire sur le site et choisissez une page ou une fonctionnalité qui sera en lien avec un de vos cas d'utilisation dans le document de conception. Faites la même chose pour une autre fonctionnalité ou plus.

#### 5. Comprenez les fichiers liés à vos fonctionnalités
Commencez par lire nos documentations d'explications du code du front end et du backend pour comprendre la structure et le fonctionnement.

Vous pouvez ouvrir le code et faire le chemin de quel fichier appelle quelle méthode pour trouver quels fichiers sont touchés par vos fonctionnalités. Par exemple, commencez par aller dans les routes du front end et cherchez les pages.

Donnez-vous beaucoup de temps pour vous mettre à l'aise avec le code du projet. Vous aurez peut-être l'impression de perdre du temps ou vous aurez envie de commencer à coder le plus vite possible, mais vous allez voir, ce sera bénéfique. Vous allez coder beaucoup plus vite si comprenez comment le projet fonctionne et vous n'aurez pas à recommencer souvent du début parce que vous vous êtes trompés.

#### 6. Codez
Les commentaires dans votre code SONT EXTRÊMEMENT IMPORTANTS. On ne comprenait rien quand on a reçu le projet.

## Ce qu'il reste à faire (liste de tâches)
Voici ce qu'il reste à faire qui a été relevé par notre équipe et par la cliente. Commencez par vous trouver deux cas d'utilisation sur lesquels vous allez déployer la majorité de vos efforts, car ils sont nécessaires pour votre évaluation dans le cours.

* Base de données
    * Refonte des tables utilisateurs
        * Il est fortement recommandé de réorganiser la structure de la base de données, notamment en décomposant la table "users" en plusieurs tables spécifiques : "admins", "juges", "participants" etc.
        --> But : cela améliorerait la lisibilité, la maintenabilité du schéma et limiterait les conditions spécifiques au rôle dans les requêtes.
    * Mise à jour des fichiers Repository
        * Après la refonte des tables, il sera nécessaire d'adapter tous les fichiers Repository impactés par la nouvelle structure relationnelle.
    * Division des responsabilités dans les Service et Repository
        * Actuellement, certaines classes (fichiers de type Service et Repository) regroupent des fonctionnalités liées à plusieurs rôles utilisateurs (participants, juges, admins, etc.) ce qui rend les fichiers longs et difficilement lisibles.
        --> Il est recommandé de séparer ces responsabilités dans différents fichiers, selon le rôle, afin d'améliorer la clarté, la maintenabilité et la réutilisabilité du code.
    * Renommer la table « survey » en « evaluationgrids »
        * Il est nécessaire de renommer la table « survey » pour « evaluationgrids » dans la base de données et de mettre à jour toutes les requêtes SQL et parties du code qui utilisent ce nom.
    * Réinitialisation de fin d'évènement
        * Ajouter dans la page de gestion des administrateurs, un bouton permettant de réinitialiser les données (résultats, juges, inscriptions, etc.) après l'évènement.
        --> But : Nettoyer la base de données pour préparer l'édition de l'année suivante.
    * Correction des bogues liés aux clés étrangères
        * Certains éléments ne se suppriment pas correctement à cause de contraintes de clés étrangères mal gérées. Il est important de revoir la conception des tables et d'améliorer la gestion des erreurs côté site pour afficher un message explicite à l'utilisateur en cas d'échec de suppression.

* Gestion des juges
    * Limiter l'envoi de liens aux juges actifs
        * Vérifier qu'aucun lien de formulaire n'est envoyé à un juge inactif ou non assigné à l'édition en cours d'Expo-SAT.
    * Indicateur d'attribution
        * Dans le tableau de gestion des juges, ajouter un champ permettant de savoir si au moins une équipe a été attribuée à chaque juge.
        --> Cela permet à la cliente de visualiser rapidement l'état d'attribution.
    * Présence à l'édition en cours
        * Ajouter un champ ou repenser l'usage du champ "Activé" afin d'indiquer si le juge participe réellement à l'édition actuelle.
        --> Cela évite la confusion entre un juge actif dans la base de données et un juge réellement impliqué cette année.

* Inscriptions
    * Inscription de participants anonymes
        * Ajouter la possibilité de masquer certaines données personnelles d'un participant (prénom, nom, DA, etc.) pour des raisons de confidentialité.
    * Consentement à la prise de photos
        * Prévoir plusieurs clauses de consentement dans le formulaire d'inscription pour couvrir différents types d'usages (publication, usage interne, refus total, etc.).

* Améliorations et correctifs sur l'interface d'administration
    * Enregistrement de l'onglet actif dans l'URL
        * Améliorer la navigation sur la page d'administration afin que le changement d'onglet soit reflété dans les paramètres de l'URL (ex: `?onglet=juges`). Cela permettra de revenir au bon onglet lors de l'utilisation du bouton "retour" du navigateur.
    * Renommer l'onglet « Administrateurs » en « Paramètres généraux »
        * Sur la page d'administration, renommer l'onglet « Administrateurs » en « Paramètres généraux » et ajouter, sous le tableau des administrateurs, le bouton de réinitialisation annuelle des données.
    * Uniformisation des pages et du design
        * Harmoniser l'apparence des pages telles que « modèles de grilles d'évaluation », « création d'un juge », « inscription des participants », « Liste », etc. Certaines utilisent encore l'ancien design avec de grands entêtes noirs et beaucoup d'espaces vides. Supprimer le composant Layout.tsx et adapter les entêtes pour une cohérence visuelle avec le reste du site.
    * Uniformisation des notifications
        * Finaliser la migration des notifications vers les `<Snackbar>` de MUI à la place de react-toastify, pour une meilleure intégration visuelle et une expérience utilisateur plus professionnelle.

* Fonctionnalités et correctifs divers
    * Tester et fiabiliser les logs
        * Les logs ne fonctionnent pas toujours de manière fiable, notamment entre l'environnement de production et de développement. Vérifier et corriger le fonctionnement des logs, en particulier dans le dossier `backend/log`.
    * Débogueur PHP et TypeScript
        * Trouver une solution pour faire fonctionner le débogueur PHP (Xdebug) et TypeScript. AMPPS pose problème avec Xdebug ; il serait pertinent de tester une installation manuelle de PHP/MySQL pour faciliter le débogage.
    * Page des informations de l'événement
        * La page pour gérer les informations de l'événement (dates, heures, etc.) n'a pas été finalisée. Vérifier auprès de la cliente si cette fonctionnalité est toujours souhaitée avant de poursuivre.
    * Centrage vertical de la barre de menus
        * La barre de menus supérieure n'est pas centrée verticalement. À corriger pour une meilleure présentation.
    * Vérification des fichiers d'environnement dans le script de build
        * Modifier le script `.\build.ps1` pour vérifier la présence des fichiers `.env` et `.env.prod` avant de lancer la compilation. Afficher un message d'erreur explicite si l'un des fichiers est manquant afin d'éviter des problèmes difficiles à diagnostiquer.
    * Remplacement des librairies obsolètes
        * Identifier et remplacer les librairies qui ne sont plus maintenues. Par exemple, le composant `<Grid>` de MUI utilisé dans `front\src\components\layout\layout.tsx` est déprécié.
    * Correction des routes et liens brisés
        * Le bouton « Modifier » dans la page de modification d'un modèle de grille d'évaluation mène vers une page inexistante (404). Vérifier la configuration des routes et l'existence de la page cible.
    * Amélioration des messages d'erreur
        * Sur la page d'assignation des juges aux équipes, clarifier les messages d'erreur (ex : indiquer si un juge est déjà assigné à une équipe à une autre heure). Éviter d'afficher des erreurs lorsqu'il n'y a pas encore d'équipes ou de juges dans le système : afficher un message informatif à la place.
    * Gestion des cas sans données
        * Sur la page de gestion des équipes, lorsqu'aucune équipe n'est inscrite, afficher simplement « Aucune équipe pour l'instant » centré dans le tableau, sans message d'erreur.

## Contributions
Voici les contributions au projet qui ont été apportées par l'équipe 2025.

- Faire fonctionner le projet qui était dans un état non fonctionnel quand nous l'avons reçu (tous)
- Réécrire les parties du code qui avaient été générées par ChatGPT et qui ne fonctionnaient pas
- Mise à jour des librairies, entre autres MUI v6 et mise à jour vers PHP 8 (Tommy Garneau et Antoine Ouellette)
- Migration du code pour les adapter aux nouvelles versions de PHP et des librairies (tous)
- Retrait des fonctionnalités qui n'étaient plus voulues par la responsable d'Expo-SAT
- Changement complet de la mise en page (structure) de la page d'administration (Antoine Ouellette)
- Changement de la mise en page de la page de gestion des équipes (Carlos Cordeiro)
- Changement de la mise en page de la page de gestion des juges (Étienne Nadeau)
- Ajout de la fonctionnalité d'ajout des plages horaires personnalisées (Alexis Boivin)
- Ajout de l'affichage des juges dans le tableau des résultats (Tommy Garneau)
- Ajout de la page de gestion des administrateurs (Antoine Ouellette)
- Ajout d'une rétroaction lors de l'inscription d'un juge (Étienne Nadeau)
- Ajout de la route de récupération de tous les administrateurs (Antoine Ouellette)
- Ajout de la route de création d'un nouvel administrateur (Antoine Ouellette)
- Ajout de la route de suppression d'une liste d'administrateurs par leurs IDs (Antoine Ouellette)
- Mise à jour du script SQL de création (Alexis Boivin)
- Renommage de plusieurs fichiers pour améliorer la clarté du projet (Antoine Ouellette et Carlos Cordeiro)
- Ajout d'instructions dans les deux fichiers de variables d'environnement (Antoine Ouellette)
- Ajout de l'affichage des points totaux dans les formulaires d'évaluation utilisés par les juges (Tommy Garneau)
- Ajout d'une rétroaction lors de la création d'un juge (Étienne Nadeau)
- Ajout de l'affichage de la moyenne des résultats pour une équipe (Tommy Garneau)
- Ajout de la fonctionnalité de suppression d'un juge (Étienne Nadeau)
- Ajout d'une fonctionnalité pour envoyer par courriel les grilles d'évaluation aux juges sélectionnés. Auparavant, il était seulement possible d'envoyer des courriels à tous les juges. (Tommy Garneau)
- Remplacement de la librairie vulnérable et obsolète xlsx par le composant DataGrid de MUI X v7
- Changement de l'image d'accueil et du logo (Étienne Nadeau)
- Remplacement de l'adresse courriel par le DA pour l'inscription des participants (Carlos Cordeiro)
- Remplacement des numéros d'équipe incrémentaux par des codes significatifs (ex: SN-S1 ou INFO2) (Tommy Garneau)
- Readme.md (Antoine Ouellette et Carlos Cordeiro)
- Installation.md (Antoine Ouellette)
- Troubleshooting.md (Carlos Cordeiro et Antoine Ouellette)
- Frontend.md et Backend.md (Carlos Cordeiro)