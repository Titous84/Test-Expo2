# Documentation Frontend Expo-SAT

## Présentation

Ce document présente la structure, le fonctionnement et les bonnes pratiques du frontend de l’application Expo-SAT.  
Le frontend est développé en **React** (TypeScript) et utilise **Material UI** pour l’interface utilisateur.

---

## Technologies utilisées

- [React](https://react.dev/) - Bibliothèque JavaScript pour la création d’interfaces utilisateur
- [TypeScript](https://www.typescriptlang.org/) - Sur-ensemble typé de JavaScript
- [Material UI (MUI)](https://mui.com/material-ui/getting-started/) - Composants d’interface utilisateur pour React
- [Jest](https://jestjs.io/) et [React Testing Library](https://testing-library.com/docs/react-testing-library/intro/) - Outils de tests unitaires

---

## Structure du projet

Le code source du frontend se trouve dans le dossier `front/` à la racine du projet.  
Voici un aperçu de l’organisation des principaux dossiers :

```
front/
├── src/
│   ├── api/                # Appels aux services backend
│   ├── components/         # Composants réutilisables
│   ├── pages/              # Pages principales de l’application
│   ├── types/              # Types TypeScript partagés
│   ├── utils/              # Fonctions utilitaires
│   └── ...
├── public/                 # Fichiers statiques
└── ...
```

---

## Installation & Lancement

Veuillez suivre les instructions détaillées dans le fichier [INSTALLATION.md](./INSTALLATION.md) pour :
- Installer les dépendances nécessaires (npm)
- Configurer l’environnement de développement
- Lancer le serveur de développement

---

## Scripts utiles

- `npm start` : Démarre le serveur de développement.
- `npm run build` : Génère la version de production.
- `npm test` : Lance les tests unitaires.

---

## Bonnes pratiques

- Veuillez utiliser TypeScript pour tous les fichiers (`.tsx`/`.ts`).
- Organisez les composants par fonctionnalité.
- Utilisez les hooks React pour la gestion d’état.
- Centralisez les appels API dans le dossier `api/`.
- Documentez les composants et fonctions complexes avec des commentaires JSDoc.

Pour les problèmes courants ou erreurs fréquentes, veuillez consulter le fichier [TROUBLESHOOTING.md](./TROUBLESHOOTING.md).

---

## Tests

- Les tests sont écrits avec **Jest** et **React Testing Library**.
- Les fichiers de test se trouvent à côté des composants, avec l’extension `.test.tsx`.

---

## Contribution

1. Clonez le dépôt et créez une branche pour votre fonctionnalité.
2. Effectuez vos modifications et ajoutez des tests si nécessaire.
3. Ouvrez une Pull Request pour relecture.

---

## Important : prise en compte des modifications

Après toute modification effectuée dans le frontend, il est impératif d’exécuter le script `build.ps1` situé à la racine du projet afin que vos changements soient bien pris en compte lors du déploiement ou des tests.

---

## Ressources complémentaires

- [README.md](../../readme.md) - Page d’accueil de la documentation du projet (à lire en premier)
- [INSTALLATION.md](./INSTALLATION.md) - Guide d’installation complet
- [TROUBLESHOOTING.md](./TROUBLESHOOTING.md) - Dépannage | Erreurs fréquentes
- [Documentation React](https://react.dev/)
- [Documentation Material UI (MUI)](https://mui.com/material-ui/getting-started/)
- [Documentation TypeScript](https://www.typescriptlang.org/docs/)