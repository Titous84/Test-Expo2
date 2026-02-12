# Documentation Backend Expo-SAT

## Présentation

Ce document présente la structure, le fonctionnement et les bonnes pratiques du backend de l’application Expo-SAT.  
Le backend est développé en **PHP** (architecture MVC, framework Slim) et expose une API RESTful pour la gestion des équipes, juges, catégories, administrateurs, etc.

---

## Technologies utilisées

- [PHP](https://www.php.net/docs.php) - Langage principal du backend
- [Slim Framework](https://www.slimframework.com/docs/v4/) - Micro-framework PHP pour l’API REST
- [Composer](https://getcomposer.org/) - Gestionnaire de dépendances PHP
- [MySQL](https://www.mysql.com/) - Système de gestion de base de données
- [PHPUnit](https://phpunit.de/) - Outil de tests unitaires

---

## Structure du projet

Le code source du backend se trouve dans le dossier `backend/api/` à la racine du projet.  
Voici un aperçu de l’organisation des principaux dossiers :

```
backend/
├── api/
│   ├── src/
│   │   ├── Controllers/      # Contrôleurs des routes API
│   │   ├── Models/           # Modèles de données
│   │   ├── Services/         # Logique métier
│   │   ├── Validators/       # Validation des données
│   │   ├── Repositories/     # Accès à la base de données
│   │   └── ...
│   ├── public/               # Point d’entrée de l’API
│   └── ...
└── ...
```

---

## Installation & Lancement

Veuillez suivre les instructions détaillées dans le fichier [INSTALLATION.md](./INSTALLATION.md) pour :
- Installer les dépendances PHP (Composer)
- Configurer la base de données
- Lancer le serveur local (AMPPS/XAMPP)
- Configurer les variables d’environnement

---

## Endpoints principaux

### Gestion des équipes & des membres

### 1. Obtenir la liste de tous les membres de toutes les équipes

- **Méthode** : `GET`
- **Route** : `/api/gestion-equipes`
- **Body** : _(aucun)_

---

### 2. Obtenir la liste de toutes les équipes

- **Méthode** : `GET`
- **Route** : `/api/gestion-equipes/teams-infos`
- **Body** : _(aucun)_

---

### 3. Obtenir les informations d'une équipe par son ID

- **Méthode** : `GET`
- **Route** : `/api/gestion-equipes/team-info/{id}`
- **Body** : _(aucun)_
- **Remplacer** `{id}` par l'identifiant de l'équipe (exemple : `/api/gestion-equipes/team-info/1`)

---

### 4. Obtenir la liste des catégories

- **Méthode** : `GET`
- **Route** : `/api/gestion-equipes/categories`
- **Body** : _(aucun)_

---

### 5. Ajouter un membre à une équipe

- **Méthode** : `POST`
- **Route** : `/api/gestion-equipes/teams-members`
- **Body (JSON)** :
    ```json
    {
      "member": {
          "first_name": "Prénom",
          "last_name": "Nom",
          "numero_da": "1234567",
          "picture_consent": 1,
          "team_id": 1
      }
    }
    ```
- **Dans Postman** : Onglet "Body" -> "raw" -> "JSON"

---

### 6. Mettre à jour une équipe

- **Méthode** : `PATCH`
- **Route** : `/api/gestion-equipes/teams-infos`
- **Body (JSON)** :
    ```json
    {
      "team": {
        "team_id": 1,
        "team_number": "SN-S1",
        "title": "Nouveau titre",
        "description": "Nouvelle description",
        "category": "Sciences de la vie",
        "year": "1re année",
        "survey": "TemplateSNS",
        "teams_activated": 1,
        "contact_person_name": "Prof DA",
        "contact_person_email": "prof@cegepvicto.ca",
        "contactPerson": [
          {
            "fullName": "Prof DA",
            "email": "prof@cegepvicto.ca"
          }
        ],
        "members": [
          {
            "firstName": "Prénom",
            "lastName": "Nom",
            "numero_da": "1234567"
          }
        ],
        "type": "team"
      }
    }
    ```

---

### 7. Mettre à jour un ou plusieurs membres

- **Méthode** : `PATCH`
- **Route** : `/api/gestion-equipes/teams-members`
- **Body (JSON)** :
    ```json
    {
      "member": {
          "id": 1,
          "first_name": "Prénom",
          "last_name": "Nom de famille",
          "numero_da": "1234567",
          "team_id": 1,
          "picture_consent": 1,
          "users_activated": 1,
          "email": "prenom.nom@email.com"
      }
    }
    ```
- **Remarque** :  
  Le champ `email` est optionnel. S'il est présent, il doit être complété et contenir une adresse courriel valide.

---

### 8. Supprimer un ou plusieurs membres

- **Méthode** : `DELETE`
- **Route** : `/api/gestion-equipes/teams-members`
- **Body (JSON)** :
    ```json
    {
      "team": [
          1
      ]
    }
    ```

---

### 9. Supprimer une ou plusieurs équipes

- **Méthode** : `DELETE`
- **Route** : `/api/gestion-equipes/teams-infos`
- **Body (JSON)** :
    ```json
    {
      "team": [
          1
      ]
    }
    ```

## Gestion de l'horaire des juges et des plages horaires

### 1. Obtenir toutes les plages horaires (time-slots)

- **Méthode** : `GET`
- **Route** : `/api/juge-stand/get-time-slots`
- **Body** : _(aucun)_

---

### 2. Créer une évaluation

- **Méthode** : `POST`
- **Route** : `/api/juge_stand/evaluation`
- **Body (JSON)** :
    ```json
    {
      "evaluation": {
          "id": 1,
          "team_id": 1,
          "judge_id": 12,
          "survey_id": 1,
          "hour": 1
      }
    }
    ```

---

### 3. Ajouter une plage horaire

- **Méthode** : `POST`
- **Route** : `/api/juge_stand/add-time-slot`
- **Body (JSON)** :
    ```json
    {
      "TimeSlots": {
          "id": 1,
          "time": 2025-04-21T18:25:43
      }
    }
    ```
    
---

### 4. Supprimer une plage horaire
- **Méthode** : `DELETE`
- **Route** : `/api/juge_stand/delete-time-slot`
- **Body (JSON)** : _(aucun)_

---

### 5. Mettre à jour les plages horaire
- **Méthode** : `PUT`
- **Route** : `/api/juge_stand/update-time-slot`
- **Body (JSON)** :
    ```json
    {
      "TimeSlots": {
          "id": 1,
          "time": 2025-04-21T18:25:43
      }
    }
    ```

---

### Gestion des administrateurs

### 1. Obtenir la liste de tous les administrateurs

- **Méthode** : `GET`
- **Route** : `/api/administrators/all`
- **Body** : _(aucun)_

---

### 2. Créer un nouvel administrateur

- **Méthode** : `POST`
- **Route** : `/api/administrators`
- **Body (JSON)** :
    ```json
    {
        "email": "courriel@admin.com",
        "password": "^Wi?Uw72bi4,"
    }
    ```
- **Dans Postman** : Onglet "Body" -> "raw" -> "JSON"

---

### 3. Supprimer une liste d'administrateurs par leurs ids

- **Méthode** : `DELETE`
- **Route** : `/api/administrators`
- **Body (JSON)** :
    ```json
    {
        "ids": [
            86,87,88
        ]
    }
    ```
- **Dans Postman** : Onglet "Body" -> "raw" -> "JSON"
- **Note** :
Retourne un code HTTP 200 si AU MOINS UN des ids d'administrateurs existe dans la BD.\
Sinon, si TOUS les ids d'administrateurs n'existent pas dans la BD, un code HTTP 404 est retourné.

---

**Conseils Postman** :  
- Pour les requêtes `PATCH`, `POST` et `DELETE`, veuillez choisir "Body" -> "raw" -> "JSON" et coller le body correspondant.
- Pour les requêtes `GET`, le body doit rester vide.

---

## Bonnes pratiques

- Veuillez respecter l’architecture MVC (Controllers, Services, Models, Repositories).
- Validez toutes les données entrantes dans les Validators.
- Utilisez des codes HTTP appropriés pour les réponses.
- Consignez les erreurs critiques dans les fichiers de logs.

Pour les problèmes courants ou erreurs fréquentes, veuillez consulter le fichier [TROUBLESHOOTING.md](./TROUBLESHOOTING.md).

---

## Tests

- Les tests unitaires sont à placer dans un dossier `tests/` (si applicable).
- Utilisez PHPUnit ou un autre framework de test PHP.

---

## Contribution

1. Clonez le dépôt et créez une branche pour votre fonctionnalité.
2. Respectez la structure MVC et les conventions du projet.
3. Ouvrez une Pull Request pour relecture.

---

## Important : prise en compte des modifications

Après toute modification effectuée dans le backend, il est impératif d’exécuter le script `build.ps1` situé à la racine du projet.  
Ce script permet de recompiler et de regrouper l’API ainsi que le frontend dans le dossier `build/`, afin que vos changements soient bien pris en compte lors du déploiement ou des tests.

---

## Ressources complémentaires

- [README.md](../../readme.md) - Page d’accueil de la documentation du projet (à lire en premier)
- [INSTALLATION.md](./INSTALLATION.md) - Guide d’installation complet
- [TROUBLESHOOTING.md](./TROUBLESHOOTING.md) - Dépannage | Erreurs fréquentes
- [Documentation PHP](https://www.php.net/docs.php)
- [Documentation Slim Framework](https://www.slimframework.com/docs/v4/)
- [Documentation Composer](https://getcomposer.org/doc/)
- [Documentation PHPUnit](https://phpunit.de/documentation.html)
- [Documentation MySQL](https://dev.mysql.com/doc/)