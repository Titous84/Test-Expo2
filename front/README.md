# Le front-end d'ExpoSAT

Le front-end d'exposat est réalisé en [React (TypeScript)](https://react.dev) avec [Vite](https://vitejs.dev). Les tests sont éxécutés à l'aide de [Vitest](https://vitest.dev) et [npm](https://www.npmjs.com) est utilisé comme gestionnaire de paquets. La documentation des composants utilise [Storybook](https://storybook.js.org).

## Cheat sheet des commandes

###### Installation des dépendances : `npm install`.

###### Compiler le front-end: `npm run build`.

###### Lancer le front-end en mode développement : `npm run start`.

###### Tester le front-end avec Vitest: `npm run test`.

###### Generer la documentation Texte: `npx typedoc --entryPointStrategy expand .\src\`.

###### Generer la documentation visuelle des componentes : `npm run storybook`.

###### Voir l'apperçu du site compilé: `npm run preview`.

## Structure des dossiers

- `.storybook` -> Page de setup pour la documentation visuelle des components.
- `cypress` -> Dossiers/Fichiers en lien avec Cypress.
- `dev` -> Outils pour le développeur (snippets).
- `dist` -> Version compilée de l'application.
- `docs` -> Documentation texte compilée.
- `node_modules` -> Dossier d'installation des dépendances.
- `dist` -> Version compilée de l'application.
- `public` -> icônes et images.
- `src` -> Code source de l'application.

  - `api` -> Code pour la communication avec l'API (voir backend).
  - `components` -> Composants React de l'application
  - `lang` -> Fichiers de définition de traductions.
  - `pages` -> Pages de l'application.
  - `router` -> Gestion des pages de l'application.
  - `stories` -> Composents nécéssaires au stories Storybook.
  - `types` -> Types et interfaces utilisées dans le code.
  - `utils` -> Fonctions utilitaires.
- `tests` -> Scripts nécéssaires à l'éxécution des tests.

## S'informer sur les composants existants

S'informer sur les composants UI existant est une étape importe afin d'éviter la répétition de code et la perte de temps. Pour cela le projet utilise [Storybook](https://storybook.js.org), il vous permettra de visualiser et de modifier en temps réel les paramêtres des composants disponibles. Pour lancer Storybook, il suffit d'éxécuter la commande `npm run storybook`. Cela lancera un serveur web où vous pourrez naviguer aux travers des composants disponibles.

Il est aussi possible de compiler le site généré par Storybook, pour ce faire vous pouvez vous référer à cette page de documentation officielle: [https://storybook.js.org/docs/react/sharing/publish-storybook](https://storybook.js.org/docs/react/sharing/publish-storybook).

## Lancer le front-end en mode développement

Pour lancer le front-end en mode développement il suffit d'entrer la commande `npm run start` dans le dossier `front/`. Cela éxécutera un serveur de développement à l'adresse `http://localhost:5173/` . Le serveur observera les changements effectués au dossiers du front-end et refraichira la page automatiquement. Pour en savoir plus, se référer au [Guide de Vite](https://vitejs.dev/guide/).

## Compiler le front-end

**Remarque: Il n'est pas nécéssaire de compiler le front-end manuellement puisque cela est éffectué automatiquement par le script de build du projet (voir:`readme.md` à la racine du projet).**

Pour compiler le front-end il suffit d'entrer la commande `npm run build` dans le dossier front/. Celle-ci compilera le front-end et l'insérera dans le dossier `front/dist/` **SUPPRIMANT toute version compilée précédemment!**

## Exécution des tests

Les tests du front-end utilisent la librairie [Vitest](https://vitest.dev). Deux options s'offrent à vous pour éxécuter les tests :

###### Le Test Explorer de Visual Studio Code ( Recommendé ) :

Des fichiers de configuration pour certains plugins VSCode sont fournis dans le dossier .vscode à la racine du projet. Ouvrir le dossier racine du projet dans vscode comme espace de travail chargera automatiquement ces configurations. Pour tester le front-end, il sera nécéssaire d'installer les extensions suivantes:

**Vous pouvez rechercher ceux-ci simplement en entrant `@recommended` dans la boite de recherche du menu Extensions ! Toutes les extensions recommendées apparaitront sous l'onglet `Workspace Recommendation`.**

* Jest Test Explorer: `kavod-io.vscode-jest-test-adapter`
* Vitest: `zixuanchen.vitest-explorer`

Les tests du front-end apparaitront ensuite automatiquement sous l'onglet `Testing` de VSCode. Si vous avez aussi installé les extensions recommendées pour le back-end, les tests front-ends apparaitront sous la catégorie `Vitest`.

###### L'invite de commande :

Il est possible de lancer les tests front-end avec l'invite de commande (`shell`, `cmd`, `powershell`) si vous n'utilisez pas VSCode .

1. Naviguer vers le dossier `front/`
2. Éxécuter la commande `npm run test`

Assurez-vous que tous les tests fonctionnent **avant** de faire une pull-request !


# Erreurs Connue en React

1. validateDOMNesting(...): <hr> cannot appear as a child of <tbody>.

L'erreur arrive lorsqu'une balise est mal placée, principalement dans les tableaux de MUI.
Un exemple de solution est dans \front\src\pages\judge-stand dans la fonction GenerateRows
Le <h5> et <Divider> sont dans les balises <tr> <td>, ce qui enlève l'erreur.

2. Each child in a list should have a unique "key" prop.

L'erreur survient lors d'un forEach ou .map() ou un component n'a pas de clé unique.
Il suffit simplement d'ajouter un index unique au component. 
Par exemple : .map((assignation, index) => { <TableCell key={index}> }

3. GET http://localhost:5173/CEGEPV_QUADRICHROMIE.png 404 (Not Found)

L'erreur survient car la page n'est pas capable de trouver l'image
Aucune solution n'a été trouver pour l'instant, m