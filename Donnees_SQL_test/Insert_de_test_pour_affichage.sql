-- Insertions dans 'users' pour les juges
INSERT INTO users (first_name, last_name, email, role_id) VALUES
('Robert', 'De Niro', 'robert@example.com', (SELECT id FROM role WHERE name = 'Juge')),
('Al', 'Pacino', 'al@example.com', (SELECT id FROM role WHERE name = 'Juge')),
('Morgan', 'Freeman', 'morgan@example.com', (SELECT id FROM role WHERE name = 'Juge')),
('Brad', 'Pitt', 'brad@example.com', (SELECT id FROM role WHERE name = 'Juge')),
('Tom', 'Hanks', 'tom@example.com', (SELECT id FROM role WHERE name = 'Juge')),
('Johnny', 'Depp', 'johnny@example.com', (SELECT id FROM role WHERE name = 'Juge'));

-- Insertions dans 'categories'
INSERT INTO categories (name, activated, max_members, survey_id) VALUES 
('Physique', 1, 5, 1), 
('Chimie', 1, 5, 1);

-- Insertions dans 'judge' avec UUID
INSERT INTO judge (uuid, categories_id, users_id) VALUES 
(UUID(), (SELECT id FROM categories WHERE name = 'Physique'), (SELECT id FROM users WHERE email = 'robert@example.com')),
(UUID(), (SELECT id FROM categories WHERE name = 'Chimie'), (SELECT id FROM users WHERE email = 'al@example.com')),
(UUID(), (SELECT id FROM categories WHERE name = 'Physique'), (SELECT id FROM users WHERE email = 'morgan@example.com')),
(UUID(), (SELECT id FROM categories WHERE name = 'Physique'), (SELECT id FROM users WHERE email = 'brad@example.com')),
(UUID(), (SELECT id FROM categories WHERE name = 'Chimie'), (SELECT id FROM users WHERE email = 'tom@example.com')),
(UUID(), (SELECT id FROM categories WHERE name = 'Physique'), (SELECT id FROM users WHERE email = 'johnny@example.com'));

-- Insertions dans 'categories_judge'
INSERT INTO categories_judge (categories_id, judge_id) VALUES 
((SELECT id FROM categories WHERE name = 'Physique'), (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'robert@example.com'))), 
((SELECT id FROM categories WHERE name = 'Chimie'), (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'al@example.com'))),
((SELECT id FROM categories WHERE name = 'Physique'), (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'morgan@example.com'))),
((SELECT id FROM categories WHERE name = 'Physique'), (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'brad@example.com'))), 
((SELECT id FROM categories WHERE name = 'Chimie'), (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'tom@example.com'))),
((SELECT id FROM categories WHERE name = 'Physique'), (SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'johnny@example.com')));

-- Insertions dans 'teams'
INSERT INTO teams (team_number, name, description, categories_id, activated, creation_date, survey_id, judge_assignation, years) VALUES 
(101, 'Équipe Alpha', 'Exploration de la physique quantique', (SELECT id FROM categories WHERE name = 'Physique'), 1, NOW(), 1, 0, '1re année'),
(102, 'Équipe Bêta', 'Application avancée du calcul', (SELECT id FROM categories WHERE name = 'Chimie'), 1, NOW(), 1, 0, '1re année'),
(103, 'Équipe Gamma', 'Étude de la chimie organique', (SELECT id FROM categories WHERE name = 'Chimie'), 1, NOW(), 1, 0, '1re année'),
(104, 'Équipe Delta', 'Étude de la physique des particules', (SELECT id FROM categories WHERE name = 'Physique'), 1, NOW(), 1, 0, '1re année'),
(105, 'Équipe Epsilon', 'Recherche en chimie inorganique', (SELECT id FROM categories WHERE name = 'Chimie'), 1, NOW(), 1, 0, '1re année'),
(106, 'Équipe Zeta', 'Recherche en chimie organique', (SELECT id FROM categories WHERE name = 'Chimie'), 1, NOW(), 1, 0, '1re année');

-- Insertions dans 'survey' pour les formulaires
INSERT INTO survey (name) VALUES 
('Template_physique'), 
('Template_chimie'),
('Template_physique_2'), 
('Template_chimie_2');

-- Insertions des évaluations pour la catégorie 'Physique' par les juges Robert, Morgan, et Brad
INSERT INTO evaluation (judge_id, teams_id, comments, survey_id, heure, est_actif) VALUES 
((SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'robert@example.com')), (SELECT id FROM teams WHERE name = 'Équipe Alpha'), 'Projet très innovant', (SELECT id FROM survey WHERE name = 'Template_physique'), 1, 1),
((SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'morgan@example.com')), (SELECT id FROM teams WHERE name = 'Équipe Alpha'), 'Excellente démarche scientifique', (SELECT id FROM survey WHERE name = 'Template_physique'), 2, 1),
((SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'brad@example.com')), (SELECT id FROM teams WHERE name = 'Équipe Alpha'), 'Approche théorique solide', (SELECT id FROM survey WHERE name = 'Template_physique'), 3, 1),

((SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'robert@example.com')), (SELECT id FROM teams WHERE name = 'Équipe Delta'), 'Bon usage des méthodes expérimentales', (SELECT id FROM survey WHERE name = 'Template_physique'), 1, 1),
((SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'morgan@example.com')), (SELECT id FROM teams WHERE name = 'Équipe Delta'), 'Travail remarquable sur les particules', (SELECT id FROM survey WHERE name = 'Template_physique'), 2, 1),
((SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'brad@example.com')), (SELECT id FROM teams WHERE name = 'Équipe Delta'), 'Explications claires et précises', (SELECT id FROM survey WHERE name = 'Template_physique'), 3, 1),

((SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'robert@example.com')), (SELECT id FROM teams WHERE name = 'Équipe Zeta'), 'Impressionnant travail de recherche', (SELECT id FROM survey WHERE name = 'Template_physique'), 1, 1),
((SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'morgan@example.com')), (SELECT id FROM teams WHERE name = 'Équipe Zeta'), 'Manque un peu de rigueur dans les tests', (SELECT id FROM survey WHERE name = 'Template_physique'), 2, 1),
((SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'brad@example.com')), (SELECT id FROM teams WHERE name = 'Équipe Zeta'), 'Bonne compréhension du sujet', (SELECT id FROM survey WHERE name = 'Template_physique'), 3, 1);

-- Insertions des évaluations pour la catégorie 'Chimie' par les juges Al et Tom et Johnny Depp --

-- Évaluations pour l'Équipe Epsilon
INSERT INTO evaluation (judge_id, teams_id, comments, survey_id, heure, est_actif) VALUES 
((SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'al@example.com')), (SELECT id FROM teams WHERE name = 'Équipe Epsilon'), 'Analyse détaillée très convaincante', (SELECT id FROM survey WHERE name = 'Template_chimie'), 1, 1),
((SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'tom@example.com')), (SELECT id FROM teams WHERE name = 'Équipe Epsilon'), 'Excellente interprétation des données', (SELECT id FROM survey WHERE name = 'Template_chimie'), 2, 1),
((SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'johnny@example.com')), (SELECT id FROM teams WHERE name = 'Équipe Epsilon'), 'Très innovant, manque cependant de précision', (SELECT id FROM survey WHERE name = 'Template_chimie'), 1, 1);

-- Évaluations pour l'Équipe Gamma
INSERT INTO evaluation (judge_id, teams_id, comments, survey_id, heure, est_actif) VALUES 
((SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'al@example.com')), (SELECT id FROM teams WHERE name = 'Équipe Gamma'), 'Résultats prometteurs', (SELECT id FROM survey WHERE name = 'Template_chimie'), 3, 1),
((SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'tom@example.com')), (SELECT id FROM teams WHERE name = 'Équipe Gamma'), 'Bonne rigueur expérimentale', (SELECT id FROM survey WHERE name = 'Template_chimie'), 4, 1),
((SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'johnny@example.com')), (SELECT id FROM teams WHERE name = 'Équipe Gamma'), 'Excellente compréhension théorique', (SELECT id FROM survey WHERE name = 'Template_chimie'), 2, 1);

-- Évaluations pour l'Équipe Bêta
INSERT INTO evaluation (judge_id, teams_id, comments, survey_id, heure, est_actif) VALUES 
((SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'al@example.com')), (SELECT id FROM teams WHERE name = 'Équipe Bêta'), 'Approche originale', (SELECT id FROM survey WHERE name = 'Template_chimie'), 5, 1),
((SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'tom@example.com')), (SELECT id FROM teams WHERE name = 'Équipe Bêta'), 'Très bonne maîtrise technique', (SELECT id FROM survey WHERE name = 'Template_chimie'), 6, 1),
((SELECT id FROM judge WHERE users_id = (SELECT id FROM users WHERE email = 'johnny@example.com')), (SELECT id FROM teams WHERE name = 'Équipe Bêta'), 'Bon travail, mais peut encore être approfondi', (SELECT id FROM survey WHERE name = 'Template_chimie'), 3, 1);

-- Insertions dans 'rating_section'
INSERT INTO rating_section (name, position, survey_id) VALUES 
('Présentation', 1, (SELECT id FROM survey WHERE name = 'Template_physique')),
('Qualité du contenu', 2, (SELECT id FROM survey WHERE name = 'Template_chimie')),
('Présentation', 1, (SELECT id FROM survey WHERE name = 'Template_physique_2')),
('Qualité du contenu', 2, (SELECT id FROM survey WHERE name = 'Template_chimie_2'));

-- Insertions dans 'criteria'
INSERT INTO criteria (rating_section_id, criteria, position, max_value) VALUES 
((SELECT id FROM rating_section WHERE name = 'Présentation' AND survey_id = (SELECT id FROM survey WHERE name = 'Template_physique')), 'Clarté de l\'explication', 1, 10),
((SELECT id FROM rating_section WHERE name = 'Qualité du contenu' AND survey_id = (SELECT id FROM survey WHERE name = 'Template_chimie')), 'Contenu approfondi', 1, 10),
((SELECT id FROM rating_section WHERE name = 'Présentation' AND survey_id = (SELECT id FROM survey WHERE name = 'Template_physique_2')), 'Clarté de l\'explication', 1, 10),
((SELECT id FROM rating_section WHERE name = 'Qualité du contenu' AND survey_id = (SELECT id FROM survey WHERE name = 'Template_chimie_2')), 'Contenu approfondi', 1, 10);


-- Insérer les scores pour le critère 'Clarté de l\'explication' (criteria_id 17) pour l'équipe Alpha
INSERT INTO criteria_evaluation (score, evaluation_id, criteria_id) VALUES 
(6, 16, 17),
(5, 17, 17),
(8, 18, 17);

-- Insérer les scores pour le critère 'Contenu approfondi' (criteria_id 18) pour l'équipe Alpha
INSERT INTO criteria_evaluation (score, evaluation_id, criteria_id) VALUES 
(7, 16, 18),  -- Score pour le premier juge
(6, 17, 18),  -- Score pour le deuxième juge
(9, 18, 18);  -- Score pour le troisième juge

-- Insérer les scores pour le critère 'Clarté de l\'explication' (criteria_id 17) pour l'équipe Delta
INSERT INTO criteria_evaluation (score, evaluation_id, criteria_id) VALUES 
(7, 19, 17),  -- Score pour le premier juge
(6, 20, 17),  -- Score pour le deuxième juge
(8, 21, 17);  -- Score pour le troisième juge

-- Insérer les scores pour le critère 'Contenu approfondi' (criteria_id 18) pour l'équipe Delta
INSERT INTO criteria_evaluation (score, evaluation_id, criteria_id) VALUES 
(9, 19, 18),  -- Score pour le premier juge
(7, 20, 18),  -- Score pour le deuxième juge
(6, 21, 18);  -- Score pour le troisième juge

-- Insérer les scores pour le critère 'Clarté de l\'explication' (criteria_id 17) pour l'équipe Zeta
INSERT INTO criteria_evaluation (score, evaluation_id, criteria_id) VALUES 
(8, 22, 17),  -- Score pour le premier juge
(7, 23, 17),  -- Score pour le deuxième juge
(9, 24, 17);  -- Score pour le troisième juge

-- Insérer les scores pour le critère 'Contenu approfondi' (criteria_id 18) pour l'équipe Zeta
INSERT INTO criteria_evaluation (score, evaluation_id, criteria_id) VALUES 
(7, 22, 18),  -- Score pour le premier juge
(6, 23, 18),  -- Score pour le deuxième juge
(8, 24, 18);  -- Score pour le troisième juge

-- *******************************************************************************

-- Insérer les scores pour le critère 'Clarté de l\'explication' (criteria_id 17) pour l'équipe Epsilon
INSERT INTO criteria_evaluation (score, evaluation_id, criteria_id) VALUES 
(8, 25, 17),  -- Score pour le premier juge
(6, 26, 17),  -- Score pour le deuxième juge
(7, 27, 17);  -- Score pour le troisième juge

-- Insérer les scores pour le critère 'Contenu approfondi' (criteria_id 18) pour l'équipe Epsilon
INSERT INTO criteria_evaluation (score, evaluation_id, criteria_id) VALUES 
(9, 25, 18),  -- Score pour le premier juge
(8, 26, 18),  -- Score pour le deuxième juge
(6, 27, 18);  -- Score pour le troisième juge

-- Insérer les scores pour le critère 'Clarté de l\'explication' (criteria_id 17) pour l'équipe Gamma
INSERT INTO criteria_evaluation (score, evaluation_id, criteria_id) VALUES 
(7, 28, 17),  -- Score pour le premier juge
(8, 29, 17),  -- Score pour le deuxième juge
(6, 30, 17);  -- Score pour le troisième juge

-- Insérer les scores pour le critère 'Contenu approfondi' (criteria_id 18) pour l'équipe Gamma
INSERT INTO criteria_evaluation (score, evaluation_id, criteria_id) VALUES 
(8, 28, 18),  -- Score pour le premier juge
(7, 29, 18),  -- Score pour le deuxième juge
(9, 30, 18);  -- Score pour le troisième juge

-- Insérer les scores pour le critère 'Clarté de l\'explication' (criteria_id 17) pour l'équipe Bêta
INSERT INTO criteria_evaluation (score, evaluation_id, criteria_id) VALUES 
(6, 31, 17),  -- Score de Al Pacino
(7, 32, 17),  -- Score de Tom Hanks
(5, 33, 17);  -- Score de Johnny Depp

-- Insérer les scores pour le critère 'Contenu approfondi' (criteria_id 18) pour l'équipe Bêta
INSERT INTO criteria_evaluation (score, evaluation_id, criteria_id) VALUES 
(8, 31, 18),  -- Score de Al Pacino
(7, 32, 18),  -- Score de Tom Hanks
(9, 33, 18);  -- Score de Johnny Depp

