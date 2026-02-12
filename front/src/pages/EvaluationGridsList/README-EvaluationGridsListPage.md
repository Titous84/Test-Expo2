<h2>Informations</h2>
La section gestion grille évaluation (evaluationGrid) remplace l'ancienne section modification des questionnaires (FormRating)

Les deux sections sont très similaires, mais la nouvelle section est plus simple et plus intuitive à utiliser.

L'ancienne section était trop encombré par des popups et ne permettait pas de modifier les questionnaires de manière simple et intuitive.

Cette nouvelle section n'a malheureusment pas pu être terminé complètement, mais une bonne base est déjà fait.

#

<h2>Fonctionnement</h2>
La section EvaluationGrid, c'est un gestion des modèles de grilles d'évaluation.

Les administrateurs peuvent créer et modifier des modèles de grilles d'évaluation dans cette section.

La première page (evaluationGrid.tsx), c'est une simple liste des grilles avec des boutons pour créer, modifier, supprimer.

La deuxième page (evaluationGridForm.tsx) permet de soit modifier ou créer un modèle de grille selon si l'id à été passé ou nom dans les paramètres.
Le formulaire doit permettre d'ajouter dynamiquement des sections et des critères au besoins. Certaines fonctionnalités ne sont pas terminés et il manque quelques champs. Je vous invite à voir les cas d'utilisations de notre document de conception hiver 2023 si vous y avez accès. Je me suis inspiré du formulaire d'inscription des équipes (signup.tsx) qui était déjà présent.

Les modèles de grilles d'évaluation sont utilisés pour créer des formulaire d'évaluation pour les juges lors d'exposat.

Les tables utilisés par cette section sont :
- <b>survey</b> : Les modèles de grilles d'évaluations sont sauvegarder dans cette table, et sont utilisés pour faire des "survey" pour que les juges puissent évaluer. 
- <b>rating_section</b> : Les sections des grilles d'évaluation (liés avec survey)
- <b>criteria</b> : Les critères des grilles d'évaluations (liés avec rating_section)
- <b>survey</b> : les concours qui sont associés aux grilles d'évaluations (les coucours peuvent avoir plusieurs modèles de grilles d'évaluations *à vérifier)

#

<h2>À faire</h2>
Voici une liste des choses qui sont à faire, vous pouvez bien sûr faire à votre guise et modifier certaines choses :

- Faire en sorte que la suppression d'une section ou un critère se fasse par le id et non par la position.
- Mettre la pondération en pourcentage pour le modèle complet calculer pour que le tout arrive a 100%.
- Mettre le formattage comme la page team (margins, texte etc)