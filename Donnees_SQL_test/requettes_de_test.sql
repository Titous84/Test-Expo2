SELECT
  categories.name AS "categorie",
  teams.name AS "teams_name",
  users.first_name AS "first_name_user",
  users.last_name AS "last_name_user",
  evaluation.comments AS "comments",
  ROUND(AVG(criteria_evaluation.score) * 10, 2) AS "global_score"
FROM evaluation
INNER JOIN teams ON evaluation.teams_id = teams.id
INNER JOIN categories ON teams.categories_id = categories.id
INNER JOIN judge ON evaluation.judge_id = judge.id
INNER JOIN users ON judge.users_id = users.id
INNER JOIN criteria_evaluation ON evaluation.id = criteria_evaluation.evaluation_id
INNER JOIN criteria ON criteria_evaluation.criteria_id = criteria.id
GROUP BY categories.name, teams.name, users.first_name, users.last_name, evaluation.comments, evaluation.id
ORDER BY categories.name, "global_score" DESC;


SELECT id FROM evaluation WHERE teams_id = (SELECT id FROM teams WHERE name = 'Équipe Alpha');

SELECT id FROM criteria WHERE criteria = 'Clarté de l\'explication' AND rating_section_id = (SELECT id FROM rating_section WHERE survey_id = (SELECT id FROM survey WHERE name = 'Template_physique')) LIMIT 1

SELECT id FROM criteria WHERE criteria = 'Contenu approfondi' AND rating_section_id = (SELECT id FROM rating_section WHERE survey_id = (SELECT id FROM survey WHERE name = 'Template_physique')) LIMIT 1;

-- Vérifier tous les sondages disponibles
SELECT * FROM survey;

-- Vérifier toutes les sections d'évaluation disponibles
SELECT * FROM rating_section;

-- Vérifier tous les critères disponibles
SELECT * FROM criteria;

-- Trouver l'ID d'évaluation pour Robert De Niro et l'Équipe Alpha
SELECT id FROM evaluation 
WHERE judge_id = (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'robert@example.com')) 
AND teams_id = (SELECT id FROM teams WHERE name = 'Équipe Alpha');

-- Trouver l'ID d'évaluation pour Morgan Freeman et l'Équipe Alpha
SELECT id FROM evaluation 
WHERE judge_id = (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'morgan@example.com')) 
AND teams_id = (SELECT id FROM teams WHERE name = 'Équipe Alpha');

-- Trouver l'ID d'évaluation pour Brad Pitt et l'Équipe Delta
SELECT id FROM evaluation 
WHERE judge_id = (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'brad@example.com')) 
AND teams_id = (SELECT id FROM teams WHERE name = 'Équipe Alpha');

-- ************************************************************************************

-- Trouver l'ID d'évaluation pour Robert De Niro et l'Équipe Delta
SELECT id FROM evaluation 
WHERE judge_id = (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'robert@example.com')) 
AND teams_id = (SELECT id FROM teams WHERE name = 'Équipe Delta');

-- Trouver l'ID d'évaluation pour Morgan Freeman et l'Équipe Delta
SELECT id FROM evaluation 
WHERE judge_id = (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'morgan@example.com')) 
AND teams_id = (SELECT id FROM teams WHERE name = 'Équipe Delta');

-- Trouver l'ID d'évaluation pour Brad Pitt et l'Équipe Zeta
SELECT id FROM evaluation 
WHERE judge_id = (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'brad@example.com')) 
AND teams_id = (SELECT id FROM teams WHERE name = 'Équipe Delta');

-- ************************************************************************************

-- Trouver l'ID d'évaluation pour Robert De Niro et l'Équipe Delta
SELECT id FROM evaluation 
WHERE judge_id = (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'robert@example.com')) 
AND teams_id = (SELECT id FROM teams WHERE name = 'Équipe Zeta');

-- Trouver l'ID d'évaluation pour Morgan Freeman et l'Équipe Zeta
SELECT id FROM evaluation 
WHERE judge_id = (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'morgan@example.com')) 
AND teams_id = (SELECT id FROM teams WHERE name = 'Équipe Zeta');

-- Trouver l'ID d'évaluation pour Brad Pitt et l'Équipe Zeta
SELECT id FROM evaluation 
WHERE judge_id = (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'brad@example.com')) 
AND teams_id = (SELECT id FROM teams WHERE name = 'Équipe Zeta');

-- ************************************************************************************

-- Trouver l'ID d'évaluation pour Al Pacino et l'Équipe Bêta
SELECT id FROM evaluation 
WHERE judge_id = (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE first_name = 'Al' AND last_name = 'Pacino')) 
AND teams_id = (SELECT id FROM teams WHERE name = 'Équipe Bêta');

-- Trouver l'ID d'évaluation pour Tom Hanks et l'Équipe Bêta
SELECT id FROM evaluation 
WHERE judge_id = (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE first_name = 'Tom' AND last_name = 'Hanks')) 
AND teams_id = (SELECT id FROM teams WHERE name = 'Équipe Bêta');

-- Trouver l'ID d'évaluation pour Johnny Depp et l'Équipe Bêta
SELECT id FROM evaluation 
WHERE judge_id = (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE first_name = 'Johnny' AND last_name = 'Depp')) 
AND teams_id = (SELECT id FROM teams WHERE name = 'Équipe Bêta');

-- ************************************************************************************

-- Trouver l'ID d'évaluation pour Al Pacino et l'Équipe Epsilon
SELECT id FROM evaluation 
WHERE judge_id = (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE first_name = 'Al' AND last_name = 'Pacino')) 
AND teams_id = (SELECT id FROM teams WHERE name = 'Équipe Epsilon');

-- Trouver l'ID d'évaluation pour Tom Hanks et l'Équipe Epsilon
SELECT id FROM evaluation 
WHERE judge_id = (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE first_name = 'Tom' AND last_name = 'Hanks')) 
AND teams_id = (SELECT id FROM teams WHERE name = 'Équipe Epsilon');

-- Trouver l'ID d'évaluation pour Johnny Depp et l'Équipe Epsilon
SELECT id FROM evaluation 
WHERE judge_id = (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE first_name = 'Johnny' AND last_name = 'Depp')) 
AND teams_id = (SELECT id FROM teams WHERE name = 'Équipe Epsilon');

-- ************************************************************************************

-- Trouver l'ID d'évaluation pour Al Pacino et l'Équipe Gamma
SELECT id FROM evaluation 
WHERE judge_id = (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE first_name = 'Al' AND last_name = 'Pacino')) 
AND teams_id = (SELECT id FROM teams WHERE name = 'Équipe Gamma');

-- Trouver l'ID d'évaluation pour Tom Hanks et l'Équipe Gamma
SELECT id FROM evaluation 
WHERE judge_id = (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE first_name = 'Tom' AND last_name = 'Hanks')) 
AND teams_id = (SELECT id FROM teams WHERE name = 'Équipe Gamma');

-- Trouver l'ID d'évaluation pour Johnny Depp et l'Équipe Gamma
SELECT id FROM evaluation 
WHERE judge_id = (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE first_name = 'Johnny' AND last_name = 'Depp')) 
AND teams_id = (SELECT id FROM teams WHERE name = 'Équipe Gamma');

-- ************************************************************************************


-- Trouver les noms des juges de l'équipe Bêta
SELECT u.first_name, u.last_name 
FROM users u
JOIN judge j ON u.id = j.users_id
JOIN evaluation e ON j.id = e.judge_id
JOIN teams t ON e.teams_id = t.id
WHERE t.name = 'Équipe Gamma';

SELECT categories.name AS 'categorie',
        teams.name AS 'teams_name',
        users.first_name AS 'first_name_user',
        users.last_name AS 'last_name_user',
        evaluation.comments AS 'comments',
        ROUND(AVG(criteria_evaluation.score * criteria.max_value) / SUM(criteria.max_value) * 10) AS 'global_score',
        ROUND(AVG(ROUND(SUM(criteria_evaluation.score * criteria.max_value) / SUM(criteria.max_value) * 10))) AS 'final_score'
    FROM evaluation
    INNER JOIN teams ON evaluation.teams_id = teams.id
    INNER JOIN categories ON teams.categories_id = categories.id
    INNER JOIN judge ON evaluation.judge_id = judge.id
    INNER JOIN users ON judge.users_id = users.id
    INNER JOIN criteria_evaluation ON evaluation.id = criteria_evaluation.evaluation_id
    INNER JOIN criteria ON criteria_evaluation.criteria_id = criteria.id
    GROUP BY categories.name, teams.name, users.first_name, users.last_name, evaluation.comments, evaluation.id
    ORDER BY categories.name, 'final_score' DESC;
   
   
   SELECT
            categories.name AS 'categorie',
            teams.name AS 'teams_name',
            users.first_name AS 'first_name_user',
            users.last_name AS 'last_name_user',
            judge.id AS 'judge_id',
            evaluation.comments AS 'comments',
            ROUND(SUM(criteria_evaluation.score * criteria.max_value) / SUM(criteria.max_value) * 10) AS 'global_score'        
        FROM evaluation
        INNER JOIN teams ON evaluation.teams_id = teams.id
        INNER JOIN categories ON teams.categories_id = categories.id
        INNER JOIN judge ON evaluation.judge_id = judge.id
        INNER JOIN users ON judge.users_id = users.id
        INNER JOIN criteria_evaluation ON evaluation.id = criteria_evaluation.evaluation_id
        INNER JOIN criteria ON criteria_evaluation.criteria_id = criteria.id
        GROUP BY evaluation.id
        ORDER BY categories.name, 'global_score' DESC;
