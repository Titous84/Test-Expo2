<h1>Inscriptions des juges</h1>
Les juges sont inscrit par les administrateur à partir de l'url "/inscription-juge"

---
<h2>Base de données <h2>

- users
    - un utilisateur est créer avec le role juge
- judge
    - un juge est créer relié à l'id de l'utilisateur créer juste avant, un uuid et une categories
- categories
    1. voir les catégories dans la liste déroulante
    2. trouver la catégories du juge et la relié à la table "judge"

--- 
<h2>Fonctionnement</h2>

Remplir les champs du formulaire puis appuyer sur le bouton "Soumettre" pour insérer un nouveau juge. 
p.s.: l'insertion ce fait, mais la page ne se rafraîchit pas et il n'y a pas de message de confirmation. (Bon travail)
