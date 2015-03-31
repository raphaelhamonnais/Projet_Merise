

INSERT INTO PROMOTION (ID_Promotion, Nom_Promotion, Annee_Promotion) VALUES (1,'DUT Informatique 1ère Année', 2014);
INSERT INTO PROMOTION (ID_Promotion, Nom_Promotion, Annee_Promotion) VALUES (2,'DUT Informatique 2ème Année', 2014);
INSERT INTO PROMOTION (ID_Promotion, Nom_Promotion, Annee_Promotion) VALUES (3,'DUT Informatique Année Spéciale', 2014);
INSERT INTO PROMOTION (ID_Promotion, Nom_Promotion, Annee_Promotion) VALUES (4,'License SRSI', 2014);







INSERT INTO GROUPE (ID_Groupe, ID_Promotion, Nom_Groupe) VALUES (1, 1,'TP1');
INSERT INTO GROUPE (ID_Groupe, ID_Promotion, Nom_Groupe) VALUES (2, 1,'TP2');
INSERT INTO GROUPE (ID_Groupe, ID_Promotion, Nom_Groupe) VALUES (3, 2,'TP1');
INSERT INTO GROUPE (ID_Groupe, ID_Promotion, Nom_Groupe) VALUES (4, 2,'TP2');
INSERT INTO GROUPE (ID_Groupe, ID_Promotion, Nom_Groupe) VALUES (5, 3,'TP1');
INSERT INTO GROUPE (ID_Groupe, ID_Promotion, Nom_Groupe) VALUES (6, 3,'TP2');
INSERT INTO GROUPE (ID_Groupe, ID_Promotion, Nom_Groupe) VALUES (7, 4,'TP1');
INSERT INTO GROUPE (ID_Groupe, ID_Promotion, Nom_Groupe) VALUES (8, 4,'TP2');






INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (1, 'loginUser1', 'pass1', 'Illouz','Gabriel','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (2, 'loginUser2', 'pass1', 'Ferey','Nicolas','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (3, 'loginUser3', 'pass1', 'Quinaud','Romain','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (4, 'loginUser4', 'pass1', 'Gandois','Laurence','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (5, 'loginUser5', 'pass1', 'Cottin','Thomas','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (6, 'loginUser6', 'pass1', 'Hamonnais','Raphael','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (7, 'loginUser7', 'pass1', 'Makouf','Yani','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (8, 'loginUser8', 'pass1', 'Galopin','Titouan','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (9, 'loginUser9', 'pass1', 'Cousin','Gaetan','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (10, 'loginUser10', 'pass1', 'Aleixio','Philippe','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (11, 'loginUser11', 'pass1', 'Duplatre','Charles','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (12, 'loginUser12', 'pass1', 'Leymarie','Pierre-Gilles','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (13, 'loginUser13', 'pass1', 'Thomas','Aurore','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (14, 'loginUser14', 'pass1', 'Mensah','Felix','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (15, 'loginUser15', 'pass1', 'Leveque','Adrien','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (16, 'loginUser16', 'pass1', 'Gamain','Jeanne','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (17, 'loginUser17', 'pass1', 'Cousin','Gaetan','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (18, 'loginUser18', 'pass1', 'Bailly','Benjamin','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Password, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (19, 'loginUser19', 'pass1', 'Rosaz','Lucas','mail@exemple.com');



INSERT INTO ELEVE (ID_Utilisateur) (SELECT ID_Utilisateur FROM UTILISATEUR WHERE ID_Utilisateur > 2);
UPDATE ELEVE SET ID_Groupe=1 WHERE ID_Utilisateur BETWEEN 3 AND 4;
UPDATE ELEVE SET ID_Groupe=2 WHERE ID_Utilisateur BETWEEN 5 AND 6;
UPDATE ELEVE SET ID_Groupe=3 WHERE ID_Utilisateur BETWEEN 7 AND 8;
UPDATE ELEVE SET ID_Groupe=4 WHERE ID_Utilisateur BETWEEN 9 AND 10;
UPDATE ELEVE SET ID_Groupe=5 WHERE ID_Utilisateur BETWEEN 11 AND 12;
UPDATE ELEVE SET ID_Groupe=6 WHERE ID_Utilisateur BETWEEN 13 AND 14;
UPDATE ELEVE SET ID_Groupe=7 WHERE ID_Utilisateur BETWEEN 15 AND 16;
UPDATE ELEVE SET ID_Groupe=8 WHERE ID_Utilisateur BETWEEN 17 AND 19;
-- SELECT * FROM ELEVE JOIN UTILISATEUR ON (ID_Utilisateur=ID_Utilisateur)


INSERT INTO PROFESSEUR (ID_Utilisateur) (SELECT ID_Utilisateur FROM UTILISATEUR WHERE ID_Utilisateur <= 2);




INSERT INTO PROF_GERE_GROUPE (ID_Utilisateur, ID_Groupe) VALUES (1, 1);
INSERT INTO PROF_GERE_GROUPE (ID_Utilisateur, ID_Groupe) VALUES (1, 2);
INSERT INTO PROF_GERE_GROUPE (ID_Utilisateur, ID_Groupe) VALUES (1, 3);
INSERT INTO PROF_GERE_GROUPE (ID_Utilisateur, ID_Groupe) VALUES (1, 4);
INSERT INTO PROF_GERE_GROUPE (ID_Utilisateur, ID_Groupe) VALUES (2, 5);
INSERT INTO PROF_GERE_GROUPE (ID_Utilisateur, ID_Groupe) VALUES (2, 6);
INSERT INTO PROF_GERE_GROUPE (ID_Utilisateur, ID_Groupe) VALUES (2, 7);
INSERT INTO PROF_GERE_GROUPE (ID_Utilisateur, ID_Groupe) VALUES (2, 8);






INSERT INTO TYPE_EXERCICE (ID_Type_Exercice, Commentaire_Type_Exercice) VALUES (1,'Dictionnaire de Données');
INSERT INTO TYPE_EXERCICE (ID_Type_Exercice, Commentaire_Type_Exercice) VALUES (2,'Trouver les Types d\'Attributs');
INSERT INTO TYPE_EXERCICE (ID_Type_Exercice, Commentaire_Type_Exercice) VALUES (3,'MEA simple avec DD Donné');
INSERT INTO TYPE_EXERCICE (ID_Type_Exercice, Commentaire_Type_Exercice) VALUES (4,'MEA complexe avec DD Donné');
INSERT INTO TYPE_EXERCICE (ID_Type_Exercice, Commentaire_Type_Exercice) VALUES (5,'MEA et DD simples');
INSERT INTO TYPE_EXERCICE (ID_Type_Exercice, Commentaire_Type_Exercice) VALUES (6,'MEA et DD complexes');
INSERT INTO TYPE_EXERCICE (ID_Type_Exercice, Commentaire_Type_Exercice) VALUES (7,'Associations');










INSERT INTO DD (ID_DD) VALUES (1); -- exercice 1 correction
INSERT INTO DD (ID_DD) VALUES (2); -- exercice 1 fake








INSERT INTO MEA (ID_MEA) VALUES (1); -- exercice 1 correction
INSERT INTO MEA (ID_MEA) VALUES (2); -- exercice 1 fake










INSERT INTO EXERCICE (ID_Exercice, Nom_Exercice, Enonce_Exercice, ID_Type_Exercice, ID_MEA_Correction, ID_MEA_Fake, ID_DD_Correction, ID_DD_Fake)
VALUES (1, 'exercice test 1', 'ennonce1', 5, 1, 2, 1, 2);
INSERT INTO EXERCICE (ID_Exercice, Nom_Exercice, Enonce_Exercice, ID_Type_Exercice, ID_MEA_Correction, ID_MEA_Fake, ID_DD_Correction, ID_DD_Fake)
VALUES (2, 'exercice test 2', 'ennonce2', 3, NULL, NULL, NULL, NULL);
INSERT INTO EXERCICE (ID_Exercice, Nom_Exercice, Enonce_Exercice, ID_Type_Exercice, ID_MEA_Correction, ID_MEA_Fake, ID_DD_Correction, ID_DD_Fake)
VALUES (3, 'exercice test 3', 'ennonce3', 2, NULL, NULL, NULL, NULL);















INSERT INTO EDITION_EXERCICE (ID_Edition_Exercice, ID_Utilisateur, ID_Exercice, Date_Edition_Exercice) VALUES (1, 1, 1,'2014-11-02');
INSERT INTO EDITION_EXERCICE (ID_Edition_Exercice, ID_Utilisateur, ID_Exercice, Date_Edition_Exercice) VALUES (2, 1, 1,'2014-03-02');
INSERT INTO EDITION_EXERCICE (ID_Edition_Exercice, ID_Utilisateur, ID_Exercice, Date_Edition_Exercice) VALUES (3, 2, 2,'2014-10-02');
INSERT INTO EDITION_EXERCICE (ID_Edition_Exercice, ID_Utilisateur, ID_Exercice, Date_Edition_Exercice) VALUES (4, 2, 3,'2014-07-02');














INSERT INTO TYPE_DONNEE (ID_Type_donnee, Libelle_Type_Donnee) VALUES (1, 'INTEGER');
INSERT INTO TYPE_DONNEE (ID_Type_donnee, Libelle_Type_Donnee) VALUES (2, 'CHAR');
INSERT INTO TYPE_DONNEE (ID_Type_donnee, Libelle_Type_Donnee) VALUES (3, 'TEXT');
INSERT INTO TYPE_DONNEE (ID_Type_donnee, Libelle_Type_Donnee) VALUES (4, 'VARCHAR');
INSERT INTO TYPE_DONNEE (ID_Type_donnee, Libelle_Type_Donnee) VALUES (5, 'DATE');
INSERT INTO TYPE_DONNEE (ID_Type_donnee, Libelle_Type_Donnee) VALUES (6, 'BOOL');
INSERT INTO TYPE_DONNEE (ID_Type_donnee, Libelle_Type_Donnee) VALUES (7, 'FLOAT');
















-- Insert des données du fake DD de l'exercice 1 (ID_DD=2)
-- Insertion seulement dans DD (pas besoin d'insérer dans ATTRIBUT, ou CALCULEE ou PARAMETRE)
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (2, 'PRIXSEANCE');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (2, 'PRIXFILM');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (2, 'PRIXPARKING');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (2, 'IDFILM');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (2, 'TITREFILM');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (2, 'IDREAL');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (2, 'NOMREAL');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (2, 'PRENOMREAL');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (2, 'IDCATEGORIE');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (2, 'LIBELLECATEGORIE');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (2, 'COULEURFILM');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (2, 'NUMERODVD');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (2, 'DATEFILM');






-- Insert des données du fake MEA de l'exercice 1 (ID_MEA=2)
INSERT INTO ENTITE (ID_MEA, Nom_Entite) VALUES (2, 'FILM');
INSERT INTO ENTITE (ID_MEA, Nom_Entite) VALUES (2, 'REALISATEUR');
INSERT INTO ENTITE (ID_MEA, Nom_Entite) VALUES (2, 'CATEGORIE');
INSERT INTO ENTITE (ID_MEA, Nom_Entite) VALUES (2, 'DVD');
INSERT INTO ENTITE (ID_MEA, Nom_Entite) VALUES (2, 'COULEUR');
INSERT INTO ENTITE (ID_MEA, Nom_Entite) VALUES (2, 'LANGUE');
INSERT INTO ENTITE (ID_MEA, Nom_Entite) VALUES (2, 'ACTEUR');
INSERT INTO ENTITE (ID_MEA, Nom_Entite) VALUES (2, 'NATIONALITE');











-- Insert des données du MEA de correction de l'exercice 1 (ID_MEA=1)
INSERT INTO ENTITE (ID_MEA, Nom_Entite) VALUES (1, 'FILM');
INSERT INTO ENTITE (ID_MEA, Nom_Entite) VALUES (1, 'REALISATEUR');
INSERT INTO ENTITE (ID_MEA, Nom_Entite) VALUES (1, 'CATEGORIE');


INSERT INTO ASSOCIATION (ID_MEA, Nom_Association) VALUES (1, 'possede_real');
INSERT INTO ASSOCIATION (ID_MEA, Nom_Association) VALUES (1, 'possede_categorie');


INSERT INTO TYPE_CARDINALITE (Libelle_Cardinalite) VALUES ('0,1');
INSERT INTO TYPE_CARDINALITE (Libelle_Cardinalite) VALUES ('1,1');
INSERT INTO TYPE_CARDINALITE (Libelle_Cardinalite) VALUES ('0,n');
INSERT INTO TYPE_CARDINALITE (Libelle_Cardinalite) VALUES ('1,n');


INSERT INTO PATTE (ID_MEA, Nom_Entite, Nom_Association, Libelle_Cardinalite) VALUES (1, 'FILM', 'possede_real', '1,1');
INSERT INTO PATTE (ID_MEA, Nom_Entite, Nom_Association, Libelle_Cardinalite) VALUES (1, 'FILM', 'possede_categorie', '1,1');
INSERT INTO PATTE (ID_MEA, Nom_Entite, Nom_Association, Libelle_Cardinalite) VALUES (1, 'REALISATEUR', 'possede_real', '0,n');
INSERT INTO PATTE (ID_MEA, Nom_Entite, Nom_Association, Libelle_Cardinalite) VALUES (1, 'CATEGORIE', 'possede_categorie', '0,n');





-- Insert des données dans le DD de la correction de l'exercice 1 (ID_DD=1)

INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (1, 'IDFILM');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (1, 'TITREFILM');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (1, 'IDREAL');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (1, 'NOMREAL');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (1, 'PRENOMREAL');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (1, 'IDCATEGORIE');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (1, 'LIBELLECATEGORIE');

INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (1, 'PRIXSEANCE');
INSERT INTO PARAMETRE (ID_DD, Nom_Parametre, Valeur) VALUES (1, 'PRIXSEANCE', '1,50'); -- ID DD=1 donc DD correction exercice 1

INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (1, 'PRIXSEANCECALCULEE');
INSERT INTO CALCULEE (ID_DD, Nom_Calculee, ID_Type_Donnee) VALUES (1, 'PRIXSEANCECALCULEE', 1);
INSERT INTO CALCULEE_A_PARTIR_DE (ID_DD, Nom_Calculee, Nom_Rubrique) VALUES (1, 'PRIXSEANCECALCULEE', 'IDFILM');
INSERT INTO CALCULEE_A_PARTIR_DE (ID_DD, Nom_Calculee, Nom_Rubrique) VALUES (1, 'PRIXSEANCECALCULEE', 'IDREAL');



INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, Nom_Association, ID_Type_Donnee) VALUES (1, 'IDFILM', TRUE, 1, 'FILM', NULL, 1);
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, Nom_Association, ID_Type_Donnee) VALUES (1, 'TITREFILM', FALSE, 1, 'FILM', NULL, 4);
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, Nom_Association, ID_Type_Donnee) VALUES (1, 'IDREAL', TRUE, 1, 'REALISATEUR', NULL, 1);
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, Nom_Association, ID_Type_Donnee) VALUES (1, 'NOMREAL', FALSE, 1, 'REALISATEUR', NULL, 4);
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, Nom_Association, ID_Type_Donnee) VALUES (1, 'PRENOMREAL', FALSE, 1, 'REALISATEUR', NULL, 4);
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, Nom_Association, ID_Type_Donnee) VALUES (1, 'IDCATEGORIE', TRUE, 1, 'CATEGORIE', NULL, 1);
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, Nom_Association, ID_Type_Donnee) VALUES (1, 'LIBELLECATEGORIE', FALSE, 1, 'CATEGORIE', NULL, 4);













-- Insert des données de test pour Copie_Eleve



INSERT INTO DD VALUES (3); -- ID_DD Copie_Eleve = 3
INSERT INTO MEA VALUES (3); -- ID_MEA Copie_Eleve = 3


INSERT INTO COPIE_ELEVE (Nom_Copie_Eleve, ID_Exercice, ID_Utilisateur, ID_MEA, ID_DD)
VALUES ('Copie Test', 1, 4, 3, 3);



INSERT INTO ENTITE (ID_MEA, Nom_Entite) VALUES (3, 'FILM');
INSERT INTO ENTITE (ID_MEA, Nom_Entite) VALUES (3, 'REALISATEUR');
INSERT INTO ENTITE (ID_MEA, Nom_Entite) VALUES (3, 'CATEGORIE');
INSERT INTO ENTITE (ID_MEA, Nom_Entite) VALUES (3, 'COULEUR');




INSERT INTO ASSOCIATION (ID_MEA, Nom_Association) VALUES (3, 'film_a_realisateur');
INSERT INTO ASSOCIATION (ID_MEA, Nom_Association) VALUES (3, 'film_a_catégorie');
INSERT INTO ASSOCIATION (ID_MEA, Nom_Association) VALUES (3, 'film_a_couleur');



INSERT INTO PATTE (ID_MEA, Nom_Entite, Nom_Association, Libelle_Cardinalite) VALUES (3, 'FILM', 'film_a_realisateur', '1,1');
INSERT INTO PATTE (ID_MEA, Nom_Entite, Nom_Association, Libelle_Cardinalite) VALUES (3, 'FILM', 'film_a_catégorie', '1,1');
INSERT INTO PATTE (ID_MEA, Nom_Entite, Nom_Association, Libelle_Cardinalite) VALUES (3, 'REALISATEUR', 'film_a_realisateur', '0,n');
INSERT INTO PATTE (ID_MEA, Nom_Entite, Nom_Association, Libelle_Cardinalite) VALUES (3, 'CATEGORIE', 'film_a_catégorie', '0,n');
-- INSERT INTO PATTE (ID_MEA, Nom_Entite, Nom_Association, Libelle_Cardinalite) VALUES (3, 'COULEUR', 'film_a_couleur', '0,n'); -- permet d'avoir une entité sans patte pour lancer la requête qui vérifie l'intégrité du MEA
INSERT INTO PATTE (ID_MEA, Nom_Entite, Nom_Association, Libelle_Cardinalite) VALUES (3, 'FILM', 'film_a_couleur', '1,1');





INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (3, 'PRIXSEANCE');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (3, 'IDFILM');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (3, 'TITREFILM');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (3, 'IDREAL');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (3, 'NOMREAL');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (3, 'PRENOMREAL');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (3, 'IDCATEGORIE');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (3, 'COULEURFILM');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (3, 'NUMERODVD');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (3, 'DATEFILM');
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (3, 'PRIXSEANCECALCULEE');




INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, Nom_Association, ID_Type_Donnee) VALUES (3, 'PRIXSEANCE', FALSE, 3, 'FILM', NULL, 1);
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, Nom_Association, ID_Type_Donnee) VALUES (3, 'IDFILM', FALSE, 3, 'FILM', NULL, 1);
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, Nom_Association, ID_Type_Donnee) VALUES (3, 'TITREFILM', FALSE, 3, 'FILM', NULL, 1);
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, Nom_Association, ID_Type_Donnee) VALUES (3, 'DATEFILM', FALSE, 3, 'FILM', NULL, 1);
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, Nom_Association, ID_Type_Donnee) VALUES (3, 'NUMERODVD', FALSE, 3, 'FILM', NULL, 1);
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, Nom_Association, ID_Type_Donnee) VALUES (3, 'IDREAL', FALSE, 3, 'REALISATEUR', NULL, 1);
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, Nom_Association, ID_Type_Donnee) VALUES (3, 'NOMREAL', FALSE, 3, 'REALISATEUR', NULL, 1);
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, Nom_Association, ID_Type_Donnee) VALUES (3, 'PRENOMREAL', FALSE, 3, 'REALISATEUR', NULL, 1);
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, Nom_Association, ID_Type_Donnee) VALUES (3, 'COULEURFILM', FALSE, 3, 'COULEUR', NULL, 1);
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, Nom_Association, ID_Type_Donnee) VALUES (3, 'IDCATEGORIE', FALSE, 3, 'CATEGORIE', NULL, 1);


INSERT INTO CALCULEE (ID_DD, Nom_Calculee, ID_Type_Donnee) VALUES (3, 'PRIXSEANCECALCULEE', 1);

INSERT INTO CALCULEE_A_PARTIR_DE (ID_DD, Nom_Calculee, Nom_Rubrique) VALUES (3, 'PRIXSEANCECALCULEE', 'IDFILM');
-- INSERT INTO CALCULEE_A_PARTIR_DE (ID_DD, Nom_Calculee, Nom_Rubrique) VALUES (3, 'PRIXSEANCECALCULEE', 'IDREAL');










