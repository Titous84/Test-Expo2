<h1>Mot de l'auteur</h1>

<font size = 4>Bonjour chèr(e)s collègues d'expoSAT, mon nom est Déreck Lachance, j'ai travaillé sur ce projet en H23. J'ai détruit et reconstruit la page judge-stand à plusieurs reprise, mais j'ai aussi passé sur une bonne partie du projet. Ce projet en a des vertes et des pas mûres. Il est normal de passer une bonne partie de votre temps à découvrir quel genre de sorcellerie l'équipe de H22 à utilisé, mais vous réussirez j'en suis certain. L'équipe A22 à bien expliquez comment lancé le projet dans le github, suivez le readme du projet à la lettre et vous pourrez explorer le projet en moins de 2. Nous avons ajouté plusieurs readme dans les parties que nous avons modifié en esperant que cela vous aidera dans votre compréhension. Aussi, plusieurs noms de fonction, classes et autres ne font pas vraiment de sens, cela est dû à des changements du fonctionnement de ExpoSAT ou à des "skills issues". Plusieurs fichiés sont aussi inutiles ou juste vide ?, mais flemme suivez les imports et vous devriez trouver </font>

---

<h1>README JudgesSchedulesPage</h1>

<h2>Voici le readme de la page "tableau d'assignation des évaluations", aussi connu sur le nom de "JudgesSchedulesPage" dans le code.</h2>
 
---
<h2>Fonctionnement de la page</h2>

Pour accèder à cette page vous devez vous rendre dans la partie Administration, puis gestion et "Horaire des juges". Pour bien voir cette page assurez-vous d'avoir :
- Des usagers et au moins une équipe dans la table "Teams".
- Deux juges avec un qui à comme catégorie "Projet TES"

Donc une fois que vous entrez dans la page vous verrez des lignes se créer avec de gauche à droite --> le nom du juge puis une liste déroulante pour chaque enregistrement dans la table time-slots. Lorsque vous choisissez un numéro d'équipe à l'aide d'une desslistes déroulantes, un appel "POST" à l'url "/evaluation" sera fait instantanément pour insérer l'évaluation. Si on rafraîchi la page les évaluations s'afficheront dans leur bon bloc également. <br></br>

La partie sur le changement d'heure fonctionne est fonctionnel, mais il demeure des bugs dans le backend à l'origine qui me sont inconnu.

La vérification de la concordance de la catégorie du juge avec celle de l'équipe est effectuée. Si les catégories ne correspondent pas, vous verrez l'option en jaune.




---
<h2>Base de donnée</h2>

- Cette partie du projet utilise les tables
    - evaluation
    - teams
        - categories
    - survey
        - survey_id
    - judge
        - categories
    - time_slots

 1. **Evaluation** 
    - La table evaluation est utile car c'est dans celle-ci que les heures des rencontres sont entrées. Elle contient l'id du juge, l'id de l'équipe, le pointage que les juges ont données (au début c'est 0, mais cette partie sera UPDATE par la partie des "survey"), les commentaires (ceux des juges lorsque qu'ils rempliront les grilles d'évaluations), l'id des questionnaires (lorsqu'on envoit les questionnaires aux juges, cette id permettra d'envoyer le bon liens aux juges concerné), l'id du bloc d'heure (de la table "time_slots") et finalement "est_actif" qui est, de ce que j'ai compris, un bool qui est TRUE lorsque le email d'évaluation à été envoyé au juge.
2. **Teams**
    -  La table teams elle, sert à avoir les informations des équipes. Elle nous permet de voir les options des numéros d'équipes dans le <select><option>1</option></select> des équipes. Lorsqu'on insère une evaluation, on trouve l'id de l'équipe en fonction de leur numéro d'équipe. La partie categories ici sert à connaître la catégories de l'équipe. Donc exemple:
        - Une équipe est de catégories_id = 6, donc elle appartient à la catégorie "science de la vie". On prend ensuite cette id (6) et on regarde dans "categories" quel "survey_id" est correspondant. Dans ce cas, ça serait le "0". Une fois que nous avons cette id (0) nous pourrons avoir l'id de la grille (à partir de la table "survey"). Donc dans ce cas, la grille "TemplateSAT" correspond au survey_id = 0.
    **Note: en date de 2025, le fonctionnement pour lié au survey est le même, cependant on n'insérait qu'une seul survey pour toutes les équipes et pour toute l'année.
3. **Survey** 
    - La table survey sert à relié la bonne grille à l'évaluation inserée. La grille est trouvé à l'aide de la table "survey_id" comme démontré dans l'exemple si-dessus.
4. **Judge**
    - La table juge elle, sert à affiché les juges dans la page. Donc à chaque juge créer une ligne d'assignation d'équipe à une certaine heure de rencontre. Celle-ci nous permet également de vérifier si la catégorie du juge correspond à celle des équipes.
5. **Time_slots** 
    - Cette table sert à voir les heures de passage des juges. Cette table permet de plus facilement changé les heures et permet de gérer plus facilement les conditions.

---
<h2> À faire selon mes connaissances </h2>

- Changement d'heure fonctionnel, mais problème rencontré lors de l'ajout ou de la suppression de plage horaire.
- Vérification de si une équipe à 2 rencontre dans une même plage horaire. Donc 2 fois le même numéro d'équipe (# de stand) dans la même colonne. L'option doit apparaître en mode "disabled" dans les autres listes déroulante si elle à été mit une fois déjà.
- S'il n'y a pas de juge, afficher un message pour informer l'utilisateur.
- S'il n'y a pas d'équipe, afficher un message pour informer l'utilisateur.

