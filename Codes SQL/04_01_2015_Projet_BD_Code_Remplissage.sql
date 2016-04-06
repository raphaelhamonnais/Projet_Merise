

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






INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (1, 'raphael.hamonnais', 'Illouz','Gabriel','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (2, 'thomas.cottin', 'Ferey','Nicolas','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (3, 'loginUser3', 'Quinaud','Romain','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (4, 'loginUser4', 'Gandois','Laurence','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (5, 'loginUser5', 'Cottin','Thomas','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (6, 'loginUser6', 'Hamonnais','Raphael','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (7, 'loginUser7', 'Makouf','Yani','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (8, 'loginUser8', 'Galopin','Titouan','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (9, 'loginUser9', 'Cousin','Gaetan','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (10, 'loginUser10', 'Aleixio','Philippe','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (11, 'loginUser11', 'Duplatre','Charles','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (12, 'loginUser12', 'Leymarie','Pierre-Gilles','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (13, 'loginUser13', 'Thomas','Aurore','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (14, 'loginUser14', 'Mensah','Felix','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (15, 'loginUser15', 'Leveque','Adrien','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (16, 'loginUser16', 'Gamain','Jeanne','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (17, 'loginUser17', 'Cousin','Gaetan','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (18, 'loginUser18', 'Bailly','Benjamin','mail@exemple.com');
INSERT INTO UTILISATEUR (ID_Utilisateur, login, Nom_Utilisateur, Prenom_Utilisateur, Mail_Utilisateur) VALUES (19, 'loginUser19', 'Rosaz','Lucas','mail@exemple.com');



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




INSERT INTO TYPE_DONNEE (ID_Type_donnee, Libelle_Type_Donnee) VALUES (1, 'INTEGER');
INSERT INTO TYPE_DONNEE (ID_Type_donnee, Libelle_Type_Donnee) VALUES (2, 'CHAR');
INSERT INTO TYPE_DONNEE (ID_Type_donnee, Libelle_Type_Donnee) VALUES (3, 'TEXT');
INSERT INTO TYPE_DONNEE (ID_Type_donnee, Libelle_Type_Donnee) VALUES (4, 'VARCHAR');
INSERT INTO TYPE_DONNEE (ID_Type_donnee, Libelle_Type_Donnee) VALUES (5, 'DATE');
INSERT INTO TYPE_DONNEE (ID_Type_donnee, Libelle_Type_Donnee) VALUES (6, 'BOOL');
INSERT INTO TYPE_DONNEE (ID_Type_donnee, Libelle_Type_Donnee) VALUES (7, 'FLOAT');






INSERT INTO TYPE_CARDINALITE (Libelle_Cardinalite) VALUES ('0,1');
INSERT INTO TYPE_CARDINALITE (Libelle_Cardinalite) VALUES ('1,1');
INSERT INTO TYPE_CARDINALITE (Libelle_Cardinalite) VALUES ('0,n');
INSERT INTO TYPE_CARDINALITE (Libelle_Cardinalite) VALUES ('1,n');




























--Création de l'exercice
INSERT INTO MEA VALUES(5);
INSERT INTO MEA VALUES(6);
INSERT INTO DD VALUES(5);
INSERT INTO DD VALUES(6);
INSERT INTO EXERCICE(ID_Exercice, Nom_Exercice, Enonce_Exercice, ID_Type_Exercice, ID_MEA_Correction, ID_MEA_Fake, ID_DD_Correction, ID_DD_Fake, Exercice_Pret)
VALUES (2, 'Cinema', '../../../uploads/1_Cinema.pdf', 1, 5, 6, 5, 6, 0);

-- correction
-- entités
INSERT INTO ENTITE(ID_MEA, Nom_Entite) VALUES (5, 'FILM');
INSERT INTO ENTITE(ID_MEA, Nom_Entite) VALUES (5, 'REALISATEUR');
INSERT INTO ENTITE(ID_MEA, Nom_Entite) VALUES (5, 'GENRE');
INSERT INTO ENTITE(ID_MEA, Nom_Entite) VALUES (5, 'CINEMA');
-- associations
INSERT INTO ASSOCIATION(ID_MEA, Nom_Association) VALUES (5, 'FILM_GENRE_');
INSERT INTO PATTE(ID_MEA, Nom_Entite, Nom_Association, Libelle_Cardinalite) VALUES (5, 'FILM', 'FILM_GENRE_', '1,1');
INSERT INTO PATTE(ID_MEA, Nom_Entite, Nom_Association, Libelle_Cardinalite) VALUES (5, 'GENRE', 'FILM_GENRE_', '0,n');
INSERT INTO ASSOCIATION(ID_MEA, Nom_Association) VALUES (5, 'FILM_REALISATEUR_');
INSERT INTO PATTE(ID_MEA, Nom_Entite, Nom_Association, Libelle_Cardinalite) VALUES (5, 'FILM', 'FILM_REALISATEUR_', '1,1');
INSERT INTO PATTE(ID_MEA, Nom_Entite, Nom_Association, Libelle_Cardinalite) VALUES (5, 'REALISATEUR', 'FILM_REALISATEUR_', '0,n');
INSERT INTO ASSOCIATION(ID_MEA, Nom_Association) VALUES (5, 'CINEMA_FILM_');
INSERT INTO PATTE(ID_MEA, Nom_Entite, Nom_Association, Libelle_Cardinalite) VALUES (5, 'CINEMA', 'CINEMA_FILM_', '0,n');
INSERT INTO PATTE(ID_MEA, Nom_Entite, Nom_Association, Libelle_Cardinalite) VALUES (5, 'FILM', 'CINEMA_FILM_', '0,n');



-- paramètres
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (5, 'Prix Realisateur');
INSERT INTO PARAMETRE(ID_DD, Nom_Parametre, Valeur) VALUES (5, 'Prix Realisateur', 'Prix du film selon son réalisteur');
-- attributs
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (5, 'ID_Film');
INSERT INTO ATTRIBUT(ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, ID_Type_Donnee) VALUES (5, 'ID_Film', 1, 5, 'FILM', 1);
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (5, 'Nom_Film');
INSERT INTO ATTRIBUT(ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, ID_Type_Donnee) VALUES (5, 'Nom_Film', 0, 5, 'FILM', 4);
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (5, 'Duree_Film');
INSERT INTO ATTRIBUT(ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, ID_Type_Donnee) VALUES (5, 'Duree_Film', 0, 5, 'FILM', 1);
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (5, 'ID_Genre');
INSERT INTO ATTRIBUT(ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, ID_Type_Donnee) VALUES (5, 'ID_Genre', 1, 5, 'GENRE', 1);
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (5, 'Libelle_Genre');
INSERT INTO ATTRIBUT(ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, ID_Type_Donnee) VALUES (5, 'Libelle_Genre', 0, 5, 'GENRE', 4);
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (5, 'ID_Realisateur');
INSERT INTO ATTRIBUT(ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, ID_Type_Donnee) VALUES (5, 'ID_Realisateur', 1, 5, 'REALISATEUR', 1);
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (5, 'Nom_Realisateur');
INSERT INTO ATTRIBUT(ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, ID_Type_Donnee) VALUES (5, 'Nom_Realisateur', 0, 5, 'REALISATEUR', 4);
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (5, 'Prenom_Realisateur');
INSERT INTO ATTRIBUT(ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, ID_Type_Donnee) VALUES (5, 'Prenom_Realisateur', 0, 5, 'REALISATEUR', 4);
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (5, 'ID_Cinema');
INSERT INTO ATTRIBUT(ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, ID_Type_Donnee) VALUES (5, 'ID_Cinema', 1, 5, 'CINEMA', 1);
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (5, 'Nom_Cinema');
INSERT INTO ATTRIBUT(ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, ID_Type_Donnee) VALUES (5, 'Nom_Cinema', 0, 5, 'CINEMA', 4);
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (5, 'Adresse Cinema');
INSERT INTO ATTRIBUT(ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, ID_Type_Donnee) VALUES (5, 'Adresse Cinema', 0, 5, 'CINEMA', 4);



INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (5, 'Prix_Seance');
INSERT INTO CALCULEE(ID_DD, Nom_Calculee, ID_Type_Donnee) VALUES (5, 'Prix_Seance', 7);
INSERT INTO CALCULEE_A_PARTIR_DE(ID_DD, Nom_Calculee, Nom_Rubrique) VALUES (5, 'Prix_Seance', 'ID_Cinema');
INSERT INTO CALCULEE_A_PARTIR_DE(ID_DD, Nom_Calculee, Nom_Rubrique) VALUES (5, 'Prix_Seance', 'ID_Realisateur');
INSERT INTO CALCULEE_A_PARTIR_DE(ID_DD, Nom_Calculee, Nom_Rubrique) VALUES (5, 'Prix_Seance', 'Prix Realisateur');




-- Correction prête et construction fake

UPDATE EXERCICE SET Correction_Prete=1 WHERE ID_Exercice=2;
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (6, 'Adresse Cinema');
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (6, 'Duree_Film');
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (6, 'ID_Cinema');
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (6, 'ID_Film');
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (6, 'ID_Genre');
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (6, 'ID_Realisateur');
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (6, 'Libelle_Genre');
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (6, 'Nom_Cinema');
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (6, 'Nom_Film');
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (6, 'Nom_Realisateur');
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (6, 'Prenom_Realisateur');
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (6, 'Prix Realisateur');
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (6, 'Prix_Seance');
INSERT INTO ENTITE(ID_MEA, Nom_Entite) VALUES (6, 'CINEMA');
INSERT INTO ENTITE(ID_MEA, Nom_Entite) VALUES (6, 'FILM');
INSERT INTO ENTITE(ID_MEA, Nom_Entite) VALUES (6, 'GENRE');
INSERT INTO ENTITE(ID_MEA, Nom_Entite) VALUES (6, 'REALISATEUR');


INSERT INTO ENTITE(ID_MEA, Nom_Entite) VALUES (6, 'SALLE');
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (6, 'Mail_Film');
INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES (6, 'Mail_Genre');

UPDATE EXERCICE SET Fake_Pret=1 WHERE ID_Exercice=2;
UPDATE EXERCICE SET Exercice_Pret=1 WHERE ID_Exercice=2;



































