-- Liste des professeurs
SELECT * FROM PROFESSEUR NATURAL JOIN UTILISATEUR
ORDER BY Nom_Utilisateur ASC;

-- Liste des Elèves
SELECT * FROM ELEVE NATURAL JOIN UTILISATEUR
ORDER BY Nom_Utilisateur ASC;

-- Liste des Elèves de Nicolas Ferey
SELECT * 
FROM ELEVE NATURAL JOIN UTILISATEUR 
WHERE ID_Groupe IN (
	SELECT ID_Groupe FROM PROF_GERE_GROUPE NATURAL JOIN PROFESSEUR NATURAL JOIN UTILISATEUR
	WHERE upper(Nom_Utilisateur)='FEREY'
	);

-- Liste des Elèves par groupe
SELECT * 
FROM GROUPE NATURAL JOIN ELEVE NATURAL JOIN UTILISATEUR
GROUP BY ID_Groupe, ID_Utilisateur
ORDER BY ID_Groupe ASC;


-- Liste des Elèves par promotion
SELECT *
FROM PROMOTION NATURAL JOIN GROUPE NATURAL JOIN ELEVE NATURAL JOIN UTILISATEUR
GROUP BY ID_Promotion, ID_Utilisateur
ORDER BY Annee_Promotion ASC;


-- Liste des éditions d'exercices avec les profs ayant édité et la date d'édition
SELECT ID_Utilisateur, Nom_Utilisateur, ID_Exercice, Nom_Exercice, Date_Edition_Exercice
FROM UTILISATEUR NATURAL JOIN PROFESSEUR NATURAL JOIN EDITION_EXERCICE NATURAL JOIN EXERCICE
ORDER BY Nom_Utilisateur ASC;



-- Date de la dernière édition d'un exercice
SELECT ID_Exercice, Nom_Exercice, Date_Edition_Exercice AS "Date Derniere Edition"
FROM EXERCICE NATURAL JOIN EDITION_EXERCICE
WHERE ID_Exercice=1
HAVING Date_Edition_Exercice >= ALL (
	SELECT Date_Edition_Exercice
	FROM EXERCICE NATURAL JOIN EDITION_EXERCICE
	WHERE ID_Exercice=1
);



-- Historique des éditions d'un exercice donné
SELECT ID_Exercice, Nom_Exercice, Date_Edition_Exercice
FROM EXERCICE NATURAL JOIN EDITION_EXERCICE
WHERE ID_Exercice=1
ORDER BY Date_Edition_Exercice ASC;



-- Liste des exercices classés par leur type
SELECT ID_Type_Exercice, Commentaire_Type_Exercice, ID_Exercice, Nom_Exercice
FROM EXERCICE NATURAL JOIN TYPE_EXERCICE
ORDER BY ID_Type_Exercice;

-- Liste des exercice de type 5
SELECT ID_Type_Exercice, Commentaire_Type_Exercice, ID_Exercice, Nom_Exercice
FROM EXERCICE NATURAL JOIN TYPE_EXERCICE
WHERE ID_Type_Exercice=5;



-- Liste des élèves ayant effectué des exercices
SELECT ID_Utilisateur, Nom_Utilisateur
FROM ELEVE NATURAL JOIN UTILISATEUR
WHERE ID_Utilisateur IN (
	SELECT DISTINCT ID_Utilisateur
	FROM COPIE_ELEVE
	);


-- Compter le nombre d'exercices effectués par les élèves
SELECT DISTINCT ID_Utilisateur, Nom_Utilisateur, COUNT(DISTINCT ID_Exercice) AS NOMBRE_EXERCICE_EFFECTUES
FROM ELEVE NATURAL JOIN UTILISATEUR NATURAL JOIN COPIE_ELEVE
GROUP BY ID_Utilisateur, Nom_Utilisateur
ORDER BY COUNT(DISTINCT ID_Exercice) DESC;


-- Compter le nombre de copies effectuées par exercice et par élève
SELECT DISTINCT ID_Utilisateur, Nom_Utilisateur, ID_Exercice, Nom_Exercice, COUNT(ID_Copie_Eleve) AS NOMBRE_COPIES
FROM ELEVE NATURAL JOIN UTILISATEUR NATURAL JOIN COPIE_ELEVE NATURAL JOIN EXERCICE
GROUP BY ID_Utilisateur, Nom_Utilisateur, ID_Exercice
ORDER BY COUNT(ID_Copie_Eleve) DESC;

-- Compter le nombre de copies terminées par exercice et par élève
SELECT DISTINCT ID_Utilisateur, Nom_Utilisateur, ID_Exercice, Nom_Exercice, COUNT(ID_Copie_Eleve) AS NOMBRE_COPIES
FROM ELEVE NATURAL JOIN UTILISATEUR NATURAL JOIN COPIE_ELEVE NATURAL JOIN EXERCICE
WHERE Date_Envoi_Copie_Eleve IS NOT NULL
GROUP BY ID_Utilisateur, Nom_Utilisateur, ID_Exercice
ORDER BY COUNT(ID_Copie_Eleve) DESC;







-- 							NOTE PRELIMINAIRE 						--
-- On utilise dans les requêtes de test MAX(ID_DD) et MAX(ID_MEA) WHERE [condition nécessaire] par soucis de test sans environnement d'application
-- Mais lors d'une utilisation réelle, on utilisera des variables pour stocker les informations dont on a besoin afin de faire les modifications sur les bon objets
-- Voici en résumé les déroulements possibles pour un élève lorsqu'il effectue un exercice

-- l'élève clique sur effectuer un  nouvel exercice :
	-- une copie_élève correspondant à l'ID de l'élève et l'ID de l'exercice est crée dans la table COPIE_ELEVE. Cette copie possède sa propre ID
	-- l'élève est maintenant en train d'effectuer l'exercice et l'ID de la copie a été sauvegardé dans une variable
-- l'élève clique sur modifier un exercice existant :
	-- affichage d'une liste des copies non terminées (Date_Envoi_Copie_Eleve NULL) en cours triées par exercice puis, si plusieurs copies par exercice, par date de dernière modification
	-- lorsque l'élève clique sur la copie l'intéressant, l'ID de la copie est sauvegardé dans une variable et l'élève est maintenant en train de modifier sa copie

-- Lorsque l'élève veut arrêter de travailler sur une copie :
	-- s'il pense avoir terminé et qu'il veut la correction, il clique sur "envoyer copie"
		-- enregistrement de la date de dernière modification et de la date d'envoie de copie
	-- s'il veut seulement enregistrer sa copie et la reprendre plus tard
		-- enregistrement de la date de dernière modification


-- Lorsque l'élève travaille sur sa copie
	-- chaque modification est directement enregistrée (inserts, updates dans les DD et MEA correspondants à la copie_eleve) 
		-- dans la copie correspondante donc pas de retour en arrière possible
	-- Pour modifier la bonne copie, il faudra récupérer l'ID de la copie depuis la bonne variable





--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------






-- Creation d'un nouvel exercice :
-- Click sur "Créer un nouvel exercice" :
-- déclenche de façon automatique les requêtes suivantes :
INSERT INTO MEA;
-- VariableID_MEACorrection <— SELECT MAX(ID_MEA) FROM MEA
INSERT INTO MEA;
-- VariableID_MEAFake <— SELECT MAX(ID_MEA) FROM MEA
INSERT INTO DD;
-- VariableID_DDCorrection <— SELECT MAX(ID_DD) FROM DD
INSERT INTO DD;
-- VariableID_DDFake <— SELECT MAX(ID_DD) FROM DD
INSERT INTO EXERCICE (Nom_Exercice, Enonce_Exercice, ID_Type_Exercice, ID_MEA_Correction, ID_MEA_Fake, ID_DD_Correction, ID_DD_Fake)
SELECT ('Nom rentré par prof', 'chemin du fichier choisi', ID_Type_Exercice, VariableID_MEACorrection, VariableID_MEAFake, VariableID_DDCorrection, VariableID_DDFake)
FROM TYPE_EXERCICE
WHERE Commentaire_Type_Exercice='Selection liste déroulante';





-- Suite création de l'exercice :
-- Création des DD et des MEA de correction :
-- Remarque : toute insertion dans la table MEA ou DD correspondant à une correction est automatiquement copiée dans la même table mais avec l'ID_DD ou ID_MEA "fake"
-- La réciproque n'est pas vrai
-- Création des Rubriques (les insertions dans les tables ATTRIBUTS, PARAMETRES et CALCULEES se feront par la suite)

INSERT INTO DD (ID_DD, Nom_Rubrique) VALUES (VariableID_DDCorrection, 'nom tapé par l utilisateur');
-- de facon automatique, si une insertion est faite dans le DD de correction, la même est faire dans le DD fake
INSERT INTO DD (ID_DD, Nom_Rubrique) VALUES (VariableID_DDFake, 'nom tapé par l utilisateur');
-- la réciproque n'est pas vrai


UPDATE RUBRIQUE
SET Commentaire_Rubrique='Commentaire_Tapé_Par_Utilisateur'
WHERE ID_DD=VariableID_DDCorrection AND Nom_Rubrique='Nom_Attribut_Sélectionné';


-- Création des Entites
INSERT INTO ENTITE (ID_MEA, Nom_Entite) VALUES (VariableID_MEACorrection, 'nom tapé par l utilisateur');
-- de facon automatique, si une insertion est faite dans le MEA de correction, la même est faire dans le MEA fake
INSERT INTO ENTITE (ID_MEA, Nom_Entite) VALUES (VariableID_MEAFake, 'nom tapé par l utilisateur');
-- la réciproque n'est pas vrai




-- Création des Associations
INSERT INTO ASSOCIATION (ID_MEA, Nom_Association) VALUES (VariableID_MEACorrection, 'nom tapé par l utilisateur');
-- pas d'associations pour le MEA fake, l'élève créé lui même ses associations


-- Création des pattes pour lier les entités et les associations du MEA de correction
INSERT INTO PATTE (Nom_Entite, Nom_Association, ID_MEA, ID_Cardinalite)
SELECT 'Entite_Selectionnee_Dans_Liste', 'Association_Selectionnee_Dans_Liste', VariableID_MEACorrection, ID_Cardinalite
FROM TYPE_CARDINALITE
WHERE Libelle_Cardinalite='libelle_selectionne_dans_liste';







-- Spécialisation des Rubriques en Attributs, Calculées ou Paramètres :


-- Attributs
-- Sélection d'une entité puis clic sur "Affecter des attributs à l'Entité"
-- Liste des rubriques correspondantes à l'ID_DD de correction apparaissent
SELECT * FROM RUBRIQUE WHERE ID_DD=VariableID_DDCorrection;
-- L'utilisateur clique sur les rubriques qu'il veut insérer dans l'entité précédement sélectionnée
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, ID_MEA, Nom_Entite, Nom_Association)
VALUES (VariableID_DDCorrection, 'Nom_Rubrique_Sélectionnée_Dans_Liste', VariableID_MEACorrection, 'Entite_Selectionnee_Dans_Liste', NULL);

-- Meme manière de fonctionner pour une insertion dans une association
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, ID_MEA, Nom_Entite, Nom_Association)
VALUES (VariableID_DDCorrection, 'Nom_Rubrique_Sélectionnée_Dans_Liste', VariableID_MEACorrection, NULL, 'Association_Selectionnee_Dans_Liste');



-- Update des Attributs pour leur donner ou non : un caractère "Clé Primaire", un type de donnée (int, varchar, ...)
UPDATE ATTRIBUT
SET Cle_Primaire = TRUE -- (FALSE par défaut)
WHERE ID_DD=VariableID_DDCorrection AND Nom_Attribut='Nom_Attribut_Sélectionné';


UPDATE ATTRIBUT
SET ID_Type_Donnee = (
	SELECT ID_Type_Donnee
	FROM TYPE_DONNEE
	WHERE Libelle_Type_Donnee='libelle_selectionne_dans_liste')
WHERE ID_DD=VariableID_DDCorrection AND Nom_Attribut='Nom_Attribut_Sélectionné';









-- Paramètres
-- Click sur "définir comme paramètre"
-- Il est alors demandé une valeur (obligatoire)
INSERT INTO PARAMETRE (ID_DD, Nom_Parametre, Valeur)
VALUES (VariableID_DDCorrection, 'Nom_Rubrique_Sélectionnée', 'ValeurRentréeParUser');


-- Calculée
-- Click sur "définir comme calculée"
-- Il est alors demandé un type de donnée (obligatoire)
INSERT INTO CALCULEE (ID_DD, Nom_Calculee, ID_Type_Donnee)
SELECT VariableID_DDCorrection, 'Nom_Rubrique_Sélectionnée', ID_Type_Donnee
FROM TYPE_DONNEE
WHERE Libelle_Type_Donnee='libelle_selectionne_dans_liste';

-- CALCULEE_A_PARTIR_DE
-- Il est également demandé à partir de quelles rubriques est calculée la Rubrique Calculée (obligatoire)
-- click sur sélectionner la ou les rubriques à partir desquelles on calcule
-- Selection depuis la liste des rubriques
SELECT * FROM RUBRIQUE WHERE ID_DD=VariableID_DDCorrection;
-- Puis insertion dans la table CALCULEE_A_PARTIR_DE
INSERT INTO CALCULEE_A_PARTIR_DE (ID_DD, Nom_Calculee, Nom_Rubrique)
VALUES (VariableID_DDCorrection, 'Nom_Calculée_qui_est_en_train_d_etre_modifiée', 'Nom_Rubrique_Sélectionnée_Dans_Liste');






-- Finalisation de la création du DD et du MEA fake 

INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique) VALUES (VariableID_DDFake, 'nom tapé par l utilisateur');
-- autant de fois que l'utilisateur veut

INSERT INTO ENTITE (ID_MEA, Nom_Entite) VALUES (VariableID_MEAFake, 'nom tapé par l utilisateur');
-- autant de fois que l'utilisateur veut

-- possibilité de rentrer des données depuis un fichier de type tableur ??









-- Sauvegarde de l'exercice
-- Lorsque l'utilisateur clique sur sauvegarder, une entrée est faite dans la table edition exercice
INSERT INTO EDITION_EXERCICE (ID_Utilisateur, ID_Exercice, Date_Edition_Exercice)
VALUES ('ID_Utilisateur_Courant', 'ID_Exercice_Courant', sysdate());





-- L'édition d'un exercice se fait exactement de la même manière, l'utilisateur accede à l'exercice et le modifie comme décrit ci-dessus
-- (création d'entité, d'associations, de rubriques, sélection des attributs à mettre dans les entités ou associations, des paramètres, des calculées, mise en place des pattes entre associations et entités)






-- Mise en ligne d'un exercice de façon à ce qu'il soit visible par les élèves
-- Après avoir sauvegardé un exercice, il est possible pour l'utilisateur de le "mettre en ligne"
-- A ce moment là, une vérification sera faite sur l'intégrité du MEA et du DD de correction
	-- pas d'associations avec moins de 2 pattes
	-- pas d'entités non reliées à au moins une association
	-- pas de rubriques non "spécialisées" en attribut, calculée ou paramètre
	-- pas d'attributs non associés à des entités
	-- pas de calculées dont on ne spécifie pas à partir de quoi on la calcule





--					REQUETES DE VERIFICATIONS D' INTEGRITE DU DD ET DU MEA 			--
-- 					(réutilisées plusieurs fois dans plusieurs cas différents)		--



-- Requêtes de vérification du MEA


-- Trouver et Compter les Entités qui n'ont aucune pattes les reliant à des associations
-- Si la requête ne donne aucun résultat alors le MEA est correct pour les entités
-- Sinon les entités causant les erreurs apparaissent
SELECT Nom_Entite, COUNT(DISTINCT Nom_Entite, Nom_Association) AS NOMBRE_PATTE
FROM ENTITE NATURAL LEFT JOIN PATTE
WHERE ID_MEA=VariableID_MEACorrection
GROUP BY Nom_Entite
HAVING COUNT(DISTINCT Nom_Entite, Nom_Association) = 0;





-- Trouver et Compter les Associations qui ont moins de deux pattes (incorrectes)
-- Si la requête ne donne aucun résultat alors le MEA est correct pour les associations
-- Sinon les associations causant les erreurs apparaissent
SELECT Nom_Association, COUNT(DISTINCT Nom_Entite, Nom_Association) AS NOMBRE_PATTE
FROM ASSOCIATION NATURAL LEFT JOIN PATTE
WHERE ID_MEA=VariableID_MEACorrection
GROUP BY Nom_Association
HAVING COUNT(DISTINCT Nom_Entite, Nom_Association) < 2;


-- Vérifier que tous les attributs sont bien dans une entité ou une association










-- Requêtes de vérification du DD


-- Vérifier que toutes les rubriques sont "spécialisées" en attributs, calculées, ou paramètres
SELECT Nom_Rubrique
FROM RUBRIQUE
WHERE ID_DD=VariableID_DDCorrection AND Nom_Rubrique NOT IN (
	SELECT Nom_Attribut
	FROM ATTRIBUT
	WHERE ID_DD=VariableID_DDCorrection
	UNION
	SELECT Nom_Calculee
	FROM CALCULEE
	WHERE ID_DD=VariableID_DDCorrection
	UNION
	SELECT Nom_Parametre
	FROM PARAMETRE
	WHERE ID_DD=VariableID_DDCorrection
	);
-- Si la requête ne donne aucun résultat alors le DD est correct dans sa "spécialisation des rubriques"
-- Sinon les rubriques en tort apparaissent


-- Vérifier que tous les attributs possèdent bien un type de donnée
SELECT Nom_Attribut
FROM ATTRIBUT
WHERE ID_DD=VariableID_DDCorrection AND ID_Type_Donnee IS NULL;

-- Vérifier que tous les attributs sont bien soit dans une entité, soit dans une association
-- L'initialisation des colonnes Nom_Entite et Nom_Association ne se fait que lorsqu'on insère des attributs dans une entité ou une association
	-- donc si les deux sont nuls, l'attribut n'est pas correct
SELECT Nom_Attribut
FROM ATTRIBUT
WHERE ID_DD=VariableID_DDCorrection AND Nom_Entite IS NULL AND Nom_Association IS NULL;
-- Si la requête ne donne aucun résultat alors le DD est correct dans son aspect "intégration des attributs"
-- Sinon les attributs en tort apparaissent



-- Vérifier que toutes les calculées sont calulées à partir de quelque chose
SELECT Nom_Calculee
FROM CALCULEE
WHERE ID_DD=VariableID_DDCorrection AND Nom_Calculee NOT IN (
	SELECT DISTINCT Nom_Calculee
	FROM CALCULEE_A_PARTIR_DE
	WHERE ID_DD=VariableID_DDCorrection
	);
-- Si la requête ne donne aucun résultat alors le DD est correct au niveau des calculées
-- Sinon les calculées incorrectes apparaissent


-- Lors de la spécialisation d'une rubrique en Paramètre, la valeurs est obligatoire sinon l'insertion ne se fait pas donc tous les paramètres sont considérés comme correts


-- Il est bien évident qu'une rubrique ne PEUT PAS être à la fois un attribut et un paramètre ou une calculées








-- Passage de l'exercice en mode terminé et visible pour les élèves :
UPDATE EXERCICE
SET Exercice_Pret=TRUE
WHERE ID_Exercice='ID_Exercice_Courant';









--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------








-- Création de la copie eleve
INSERT INTO MEA VALUES();
-- variableID_MEACopie_Eleve = SELECT MAX(ID_MEA) FROM MEA
INSERT INTO DD VALUES();
-- variableID_DDCopie_Eleve = SELECT MAX(ID_DD) FROM DD
INSERT INTO COPIE_ELEVE (Nom_Copie_Eleve, ID_Exercice, ID_Utilisateur, ID_MEA, ID_DD, Date_Derniere_Modif_Copie_Eleve, Commentaire_Copie_Eleve)
SELECT 'copie_eleve_1', 1, 4, MAX(ID_MEA), MAX(ID_DD), SYSDATE(), 'commentaire_copie_1'
FROM DD, MEA;
-- Il faudrait aussi faire un Select ID_Exercice de manière à avoir l'ID de l'exercice que l'utilisateur est en train de faire
-- Il faudrait aussi faire un Select ID_Utilisateur de manière à avoir l'ID de l'utilisateur connecté







-- 						INITIALISATION DE LA COPIE_ELEVE AVEC LE DD_FAKE ET MEA_FAKE DE L'EXERCICE 			--

-- l'élève clique sur effectuer l'exercice
-- sauvegarde de l'ID_Exercice dans la variable "variableID_Exercice_Courant"




-- 											RUBRIQUES 										--


--					Affichage du DD Fake afin que l'élève puisse choisir ses rubriques 				--


SELECT Nom_Rubrique
FROM RUBRIQUE
WHERE ID_DD = (SELECT ID_DD_Fake FROM EXERCICE WHERE ID_Exercice=variableID_Exercice_Courant);



-- Insertion dans la table RUBRIQUE pour l'ID_DD de la Copie_Eleve
-- il sélectionne une rubrique dans la liste et clique sur insérer (ou double clique) par exemple
INSERT INTO RUBRIQUE (ID_DD, Nom_Rubrique)
VALUES (variableID_DDCopie_Eleve, 'Nom_Rubrique_Sélectionnée_Dans_Liste');

-- Supression de la table RUBRIQUE pour l'ID_DD de la Copie_Eleve lorsqu'il veut supprimer une rubrique
-- il selectionne une rubrique de son DD et clique sur supprimer par exemple
DELETE FROM RUBRIQUE
WHERE ID_DD=variableID_DDCopie_Eleve AND Nom_Rubrique='Nom_Rubrique_Sélectionnée_Dans_Liste';


UPDATE RUBRIQUE
SET Commentaire_Rubrique='Commentaire_Tapé_Par_Utilisateur'
WHERE ID_DD=variableID_DDCopie_Eleve AND Nom_Rubrique='Nom_Attribut_Sélectionné';




-- 											ENTITES 										--


--					Affichage du MEA Fake afin que l'élève puisse choisir ses rubriques 				--

SELECT Nom_Entite
FROM ENTITE
WHERE ID_MEA = (SELECT ID_MEA_Fake FROM EXERCICE WHERE ID_Exercice=variableID_Exercice_Courant);



-- Insertion dans la table ENTITE pour l'ID_MEA de la Copie_Eleve
-- il sélectionne une entité dans la liste et clique sur insérer (ou double clique) par exemple
INSERT INTO ENTITE (ID_MEA, Nom_Entite)
VALUES (variableID_MEACopie_Eleve, 'Nom_Entite_Selectionnée_Dans_Liste');

-- Supression de la table ENTITE pour l'ID_MEA de la Copie_Eleve lorsqu'il veut supprimer une entité
-- il selectionne une entité de son MEA et clique sur supprimer par exemple
DELETE FROM ENTITE
WHERE ID_MEA=variableID_MEACopie_Eleve AND Nom_Entite='Nom_Entite_Selectionnée_Dans_Liste';





-- Création des Associations
INSERT INTO ASSOCIATION (ID_MEA, Nom_Association) VALUES (variableID_MEACopie_Eleve, 'nom tapé par l utilisateur');
-- pas d'associations pour le MEA fake, l'élève créé lui même ses associations


-- Création des pattes pour lier les entités et les associations du MEA de correction
INSERT INTO PATTE (ID_MEA, Nom_Entite, Nom_Association, Libelle_Cardinalite)
SELECT 'Entite_Selectionnee_Dans_Liste', 'Association_Selectionnee_Dans_Liste', variableID_MEACopie_Eleve, Libelle_Cardinalite
FROM TYPE_CARDINALITE
WHERE Libelle_Cardinalite='libelle_selectionne_dans_liste';






--					 Spécialisation des Rubriques en Attributs, Calculées ou Paramètres 				--


-- Attributs
-- Sélection d'une entité puis clic sur "Affecter des attributs à l'Entité"
-- Liste des rubriques correspondantes à l'ID_DD de correction apparaissent
SELECT * FROM RUBRIQUE WHERE ID_DD=variableID_DDCopie_Eleve;
-- L'utilisateur clique sur les rubriques qu'il veut insérer dans l'entité précédement sélectionnée
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, ID_MEA, Nom_Entite, Nom_Association)
VALUES (variableID_DDCopie_Eleve, 'Nom_Rubrique_Sélectionnée_Dans_Liste', variableID_MEACopie_Eleve, 'Entite_Selectionnee_Dans_Liste', NULL);

-- Meme manière de fonctionner pour une insertion dans une association
INSERT INTO ATTRIBUT (ID_DD, Nom_Attribut, ID_MEA, Nom_Entite, Nom_Association)
VALUES (variableID_DDCopie_Eleve, 'Nom_Rubrique_Sélectionnée_Dans_Liste', variableID_MEACopie_Eleve, NULL, 'Association_Selectionnee_Dans_Liste');



-- Update des Attributs pour leur donner ou non : un caractère "Clé Primaire", un type de donnée (int, varchar, ...)
UPDATE ATTRIBUT
SET Cle_Primaire = TRUE -- (FALSE par défaut)
WHERE ID_DD=variableID_DDCopie_Eleve AND Nom_Attribut='Nom_Attribut_Sélectionné';


UPDATE ATTRIBUT
SET ID_Type_Donnee = (
	SELECT ID_Type_Donnee
	FROM TYPE_DONNEE
	WHERE Libelle_Type_Donnee='libelle_selectionne_dans_liste')
WHERE ID_DD=variableID_DDCopie_Eleve AND Nom_Attribut='Nom_Attribut_Sélectionné';









-- Paramètres
-- Click sur "définir comme paramètre"
-- Il est alors demandé une valeur (obligatoire)
INSERT INTO PARAMETRE (ID_DD, Nom_Parametre, Valeur)
VALUES (variableID_DDCopie_Eleve, 'Nom_Rubrique_Sélectionnée', 'ValeurRentréeParUser');


-- Calculée
-- Click sur "définir comme calculée"
-- Il est alors demandé un type de donnée (obligatoire)
SELECT variableID_DDCopie_Eleve, 'Nom_Rubrique_Sélectionnée', ID_Type_Donnee
FROM TYPE_DONNEE
WHERE Libelle_Type_Donnee='libelle_selectionne_dans_liste';

-- Il est également demandé à partir de quelles rubriques est calculée la Rubrique Calculée (obligatoire)
-- CALCULEE_A_PARTIR_DE
-- click sur sélectionner la ou les rubriques à partir desquelles on calcule
-- Selection depuis la liste des rubriques
SELECT * FROM RUBRIQUE WHERE ID_DD=variableID_DDCopie_Eleve;
-- Puis insertion dans la table CALCULEE_A_PARTIR_DE
INSERT INTO CALCULEE_A_PARTIR_DE (ID_DD, Nom_Calculee, Nom_Rubrique)
VALUES (variableID_DDCopie_Eleve, 'Nom_Calculée_qui_est_en_train_d_etre_modifiée', 'Nom_Rubrique_Sélectionnée_Dans_Liste');







--				REQUETES DE MODIFICATION ET SAUVEGARDE DES COPIES ELEVES 				--




-- affichage d'une liste des copies non terminées (booléen copie_terminee FALSE) en cours triées par exercice
	-- puis, si plusieurs copies par exercice, par date de dernière modification
SELECT ID_Exercice, Nom_Exercice, ID_Copie_Eleve, Nom_Copie_Eleve, Date_Derniere_Modif_Copie_Eleve, Commentaire_Copie_Eleve
FROM COPIE_ELEVE NATURAL JOIN EXERCICE
WHERE ID_Utilisateur=4 AND Date_Envoi_Copie_Eleve IS NULL
ORDER BY ID_Exercice ASC;
-- Possibilité de faire deux tris ??





-- Lorsque l'élève veut arrêter de travailler sur une copie :
	-- s'il pense avoir terminé et qu'il veut la correction, il clique sur "envoyer copie"
		-- enregistrement de la date de dernière modification et de la date d'envoie de copie
UPDATE COPIE_ELEVE 
SET Date_Derniere_Modif_Copie_Eleve=SYSDATE(), Date_Envoi_Copie_Eleve=SYSDATE()
WHERE ID_Copie_Eleve="valeur de l'ID_Copie_Eleve sauvegardée dans la variable";
-- Possibilité de n'envoyer la copie que lorsqu'elle répond aux critères de vérifivation d'intégrité du MEA et du DD





-- Lorsque l'élève veut arrêter de travailler sur une copie :
	-- s'il veut seulement enregistrer sa copie et la reprendre plus tard
		-- enregistrement de la date de dernière modification
UPDATE COPIE_ELEVE 
SET Date_Derniere_Modif_Copie_Eleve=SYSDATE()
WHERE ID_Copie_Eleve="valeur de l'ID_Copie_Eleve sauvegardée dans la variable";


-- Nous n'avons pas encore vu la progammation base de donnée orientée objet :
	-- peut être est t'il possible de voir la Copie_Eleve comme un objet ?
		-- a ce moment là, chaque nouvelle modification d'une copie donne naissance à un nouvel objet de manière à ce que 
		-- l'élève puisse retrouver des copies antérieures dans l'état dans lequel il les avait laissé









--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------









-- 						CORRECTION DETAILLEE DU DD (et un peu MEA) PAR ETAPES



-- Pour les requetes suivantes :
	-- SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4
		-- à remplacer par
		-- SELECT ID_DD FROM COPIE_ELEVE WHERE ID_Copie_Eleve="valeur de l'ID_Copie_Eleve sauvegardée dans une variable"
	-- ET
	-- SELECT MAX(ID_MEA) FROM COPIE_ELEVE WHERE ID_Utilisateur=4
		-- à remplacer par
		-- SELECT ID_MEA FROM COPIE_ELEVE WHERE ID_Copie_Eleve="valeur de l'ID_Copie_Eleve sauvegardée dans une variable"





-- Trouver le nombre de rubriques présentes dans le DD de la copie eleve et non présentes dans le DD de correction
SELECT COUNT(Nom_Rubrique)
FROM RUBRIQUE WHERE ID_DD=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4) AND Nom_Rubrique NOT IN (
	SELECT Nom_Rubrique
	FROM RUBRIQUE
	WHERE ID_DD=1 -- corection exercice 1
	)
GROUP BY ID_DD;

-- Trouver le nombre de rubriques présentes dans le DD de correction et non présentes dans le DD de la copie eleve
SELECT COUNT(Nom_Rubrique)
FROM RUBRIQUE WHERE ID_DD=1 AND Nom_Rubrique NOT IN ( -- corection exercice 1
	SELECT Nom_Rubrique
	FROM RUBRIQUE
	WHERE ID_DD=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4) 
	)
GROUP BY ID_DD;

-- Faire la somme des deux requêtes précédentes pour avoir le nombre total d'erreurs simples de DD
SELECT (
	(
    SELECT COUNT(Nom_Rubrique)
	FROM RUBRIQUE WHERE ID_DD=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4) AND Nom_Rubrique NOT IN (
		SELECT Nom_Rubrique
		FROM RUBRIQUE
		WHERE ID_DD=1)
		GROUP BY ID_DD
    ) +
    (
	SELECT COUNT(Nom_Rubrique)
	FROM RUBRIQUE WHERE ID_DD=1 AND Nom_Rubrique NOT IN ( -- corection exercice 1
		SELECT Nom_Rubrique
		FROM RUBRIQUE
		WHERE ID_DD=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4))
		GROUP BY ID_DD
	) 
	) AS DIFFERENCE_RUBRIQUE_ENTRE_COPIE_ET_CORRECTION;
    
       

-- Etant donné que la correction fine du DD prends en compte les rubriques qui sont dans la copie élève et pas dans la correction,
-- on ne retiendra plus que la requête donnant le nombre de rubriques présentes dans le DD de correction et non présentes dans la copie élève
SELECT COUNT(Nom_Rubrique) AS RUBRIQUES_OUBLIEES_PAR_ELEVE
FROM RUBRIQUE WHERE ID_DD=1 AND Nom_Rubrique NOT IN ( -- corection exercice 1
	SELECT Nom_Rubrique
	FROM RUBRIQUE
	WHERE ID_DD=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4) 
	)
GROUP BY ID_DD;



-- 				Correction fine du DD 				--

-- On part du principe que lorsqu'un élève rend une COPIE_ELEVE pour qu'elle soit corrigée, l'intégrité du MEA et du DD sont vérifiée
-- Il ne peut pas la rendre si la vérification décèle des erreurs de structure



--				ATTRIBUT 				--



-- Trouver tous les attributs corrects de l'élève, c'est a dire attributs présents dans le DD de l'élève ET :
	-- attribut présent dans la correction
	-- attibut affecté à la même entité
	-- même valeur que dans la correction pour le booléen "Cle_Primaire"
	-- même type de donnée que dans la correction
-- La requête suivante donne le nombre d'attributs CORRECTS
SELECT COUNT(A1.Nom_Attribut)
FROM ATTRIBUT A1
WHERE A1.ID_DD=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4) AND A1.Nom_Attribut IN (
	SELECT A2.Nom_Attribut
	FROM ATTRIBUT A2
	WHERE A2.ID_DD=1 AND A1.Cle_Primaire=A2.Cle_Primaire AND A1.Nom_Entite=A2.Nom_Entite AND A1.ID_Type_Donnee=A2.ID_Type_Donnee
	);
-- (requête donne le nombre correct de résultat en test)
-- on ne prend pas en compte le Nom_Association car l'élève crée lui-même ses associations et on part du principe que la base de données 
	-- est correcte, c'est à dire qu'il n'y a pas d'attributs présents à la fois dans une entité et une association
-- Pour information, voici une requête vérifiant à la fois les entités et les associations
/*
SELECT COUNT(A1.Nom_Attribut)
FROM ATTRIBUT A1
WHERE A1.ID_DD=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4) AND A1.Nom_Attribut IN (
	SELECT A2.Nom_Attribut
	FROM ATTRIBUT A2
	WHERE A2.ID_DD=1 AND A1.Cle_Primaire=A2.Cle_Primaire
    AND ((A1.Nom_Entite=A2.Nom_Entite AND A1.Nom_Association IS NULL AND A2.Nom_Association IS NULL) OR 
    	(A1.Nom_Association=A2.Nom_Association AND A1.Nom_Entite IS NULL AND A2.Nom_Entite IS NULL))
         AND A1.ID_Type_Donnee=A2.ID_Type_Donnee
	);
*/

-- Nombre d'attributs dans le DD de l'élève:
SELECT COUNT(Nom_Attribut)
FROM ATTRIBUT
WHERE ID_DD=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4);
-- Le nombre de fautes de l'élève (pour les attributs) sera donc égal au nombre total d'attributs présents dans son DD
--		moins le nombre de d'attributs corrects donnés par la première requête
-- Faisons la différence (toujours pour les attributs seulement)
SELECT (
	(
	SELECT COUNT(Nom_Attribut)
	FROM ATTRIBUT
	WHERE ID_DD=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4);
	) - 
	(
	SELECT COUNT(A1.Nom_Attribut)
	FROM ATTRIBUT A1
	WHERE A1.ID_DD=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4) AND A1.Nom_Attribut IN (
	SELECT A2.Nom_Attribut
	FROM ATTRIBUT A2
	WHERE A2.ID_DD=1 AND A1.Cle_Primaire=A2.Cle_Primaire AND A1.Nom_Entite=A2.Nom_Entite AND A1.ID_Type_Donnee=A2.ID_Type_Donnee)
	)
	) AS NOMBRE_DE_POINTS_EN_MOINS_CAR_MAUVAIS_ATTRIBUTS;








-- 				PARAMETRE 				-- 

-- Pas besoin de vérifier des égalités entre les lignes pour Parametre, le nombre d'erreur est donc
		-- "ce que l'élève à mit en trop par rapport à la correction"
		-- PLUS
		-- "ce que l'élève n'a pas mit qui est dans la correction"


-- Etant donné que la correction des rubriques prend déjà en compte les paramètres présents dans la correction et non présents dans le DD de l'élève,
-- on ne retiendra que la partie comptant le nombre de paramètres présents dans le DD de l'élève et non présents dans le DD de correction
SELECT COUNT(A1.Nom_Parametre) AS NOMBRE_DE_POINTS_EN_MOINS_CAR_MAUVAIS_PARAMETRES
FROM PARAMETRE A1
WHERE A1.ID_DD=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4) AND A1.Nom_Parametre IN (
	SELECT A2.Nom_Parametre
	FROM PARAMETRE A2
	WHERE A2.ID_DD=1)




-- 				CALCULEE 				-- 


-- Trouver toutes les calculées correctes de l'élève, c'est a dire calculées présentes dans le DD de l'élève ET :
	-- calculées présentes dans la correction
	-- qui possèdent le même type de données que dans la correction



-- Compter le nombre de rubriques servant à calculer une Calculée donnée
SELECT Nom_Calculee, COUNT(Nom_Rubrique)
FROM CALCULEE_A_PARTIR_DE
WHERE ID_DD=1
GROUP BY Nom_Calculee;


-- Selectionner les calculées qui sont correctes : 
	-- dans le NATURAL JOIN des tables CALCULEE et CALCULEE_A_PARTIR_DE pour l'ID_DD de la copie élève
	-- dans le NATURAL JOIN des tables CALCULEE et CALCULEE_A_PARTIR_DE pour l'ID_DD de la correction
	-- qui ont des lignes identique (ID_Type_Donnee et Nom_Rubrique)
	-- qui ont le même nombre de lignes quand on regroupe par Nom_Calculee (c'est a dire qui sont calculées à partir du même nombre de rubriques)
SELECT C1.Nom_Calculee
FROM CALCULEE C1 NATURAL JOIN CALCULEE_A_PARTIR_DE C_A1
WHERE ID_DD=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4) AND C1.Nom_Calculee IN (
	SELECT C2.Nom_Calculee
	FROM CALCULEE C2 NATURAL JOIN CALCULEE_A_PARTIR_DE C_A2
	WHERE ID_DD=1 AND C2.ID_Type_Donnee=C1.ID_Type_Donnee AND C_A2.Nom_Rubrique=C_A1.Nom_Rubrique
	)
HAVING (
		SELECT COUNT(Nom_Rubrique)
		FROM CALCULEE NATURAL JOIN CALCULEE_A_PARTIR_DE
		WHERE ID_DD=1
		GROUP BY Nom_Calculee
		) = 
		(
		SELECT COUNT(Nom_Rubrique)
		FROM CALCULEE NATURAL JOIN CALCULEE_A_PARTIR_DE
		WHERE ID_DD=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4)
		GROUP BY Nom_Calculee
		);

-- Les compter une fois qu'on les a sélectionné
SELECT COUNT(DISTINCT Nom_Calculee)
FROM (
	SELECT C1.Nom_Calculee
	FROM CALCULEE C1 NATURAL JOIN CALCULEE_A_PARTIR_DE C_A1
	WHERE ID_DD=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4) AND C1.Nom_Calculee IN (
		SELECT C2.Nom_Calculee
		FROM CALCULEE C2 NATURAL JOIN CALCULEE_A_PARTIR_DE C_A2
		WHERE ID_DD=1 AND C2.ID_Type_Donnee=C1.ID_Type_Donnee AND C_A2.Nom_Rubrique=C_A1.Nom_Rubrique
		)
	HAVING (
			SELECT COUNT(Nom_Rubrique)
			FROM CALCULEE NATURAL JOIN CALCULEE_A_PARTIR_DE
			WHERE ID_DD=1
			GROUP BY Nom_Calculee
			) = 
			(
			SELECT COUNT(Nom_Rubrique)
			FROM CALCULEE NATURAL JOIN CALCULEE_A_PARTIR_DE
			WHERE ID_DD=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4)
			GROUP BY Nom_Calculee
			)
) AS TT;



-- Le nombre d'erreurs pour les Calculées sera donc le nombre de calculées du DD de l'élève moins le nombre de Calculées correctes
-- (si elles sont toutes correctes alors il n'y a pas d'erreurs car 2-2 donne 0 par exemple)
SELECT (
	(
	SELECT COUNT(Nom_Calculee)
	FROM CALCULEE
	WHERE ID_DD=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4)
	) -
	(
	SELECT COUNT(DISTINCT Nom_Calculee)
	FROM (
		SELECT C1.Nom_Calculee
		FROM CALCULEE C1 NATURAL JOIN CALCULEE_A_PARTIR_DE C_A1
		WHERE ID_DD=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4) AND C1.Nom_Calculee IN (
			SELECT C2.Nom_Calculee
			FROM CALCULEE C2 NATURAL JOIN CALCULEE_A_PARTIR_DE C_A2
			WHERE ID_DD=1 AND C2.ID_Type_Donnee=C1.ID_Type_Donnee AND C_A2.Nom_Rubrique=C_A1.Nom_Rubrique
			)
		HAVING (
				SELECT COUNT(C1.Nom_Calculee)
				FROM CALCULEE C1 NATURAL JOIN CALCULEE_A_PARTIR_DE C_A1
				WHERE ID_DD=1
				GROUP BY C1.Nom_Calculee
				) = 
				(
				SELECT COUNT(C1.Nom_Calculee)
				FROM CALCULEE C1 NATURAL JOIN CALCULEE_A_PARTIR_DE C_A1
				WHERE ID_DD=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4) AND C1.Nom_Calculee IN (
					SELECT C2.Nom_Calculee
					FROM CALCULEE C2 NATURAL JOIN CALCULEE_A_PARTIR_DE C_A2
					WHERE ID_DD=1 AND C2.ID_Type_Donnee=C1.ID_Type_Donnee AND C_A2.Nom_Rubrique=C_A1.Nom_Rubrique)
				GROUP BY C1.Nom_Calculee
				)
		) AS TT
	)
	) AS NOMBRE_DE_POINTS_EN_MOINS_CAR_MAUVAISES_CALCULEES;

-- Le GROUP BY C1.Nom_Calculee dans la clause HAVING pause problème lorsqu'il y a plus d'une calculée car les sous-requêtes retournet plusieurs lignes
-- C'est pourtant ce qui permet de vérifier qu'une calculée est calculée à partir des mêmes rubriques d'une part, et qu'elle est calculée à partir du même nombre de rubriques d'autre part
-- Il faudrait pouvoir faire ces sous-requêtes pour chaque calculée présente dans le DD de correction (si une calculée est présente dans le DD de l'élève et pas dans le DD de correction, pas la peine de vérifier tout, la calculée est fausse)
		-- même fonctionnement que dans les script shell :
				-- for i in $listeCalculée
				-- do
				--		nombre_calculées_justes = nombre_calculées_justes + requête_pour_une_calculée_donnée
				-- done





----					TOTAL DE POINTS EN MOINS POUR LE DD (et un peu MEA car on a vérifié attributs dans entités)		--

-- RUBRIQUES_OUBLIEES_PAR_ELEVE
-- 							+
-- NOMBRE_DE_POINTS_EN_MOINS_CAR_MAUVAIS_ATTRIBUTS
--							+
-- NOMBRE_DE_POINTS_EN_MOINS_CAR_MAUVAIS_PARAMETRES
-- 							+
-- NOMBRE_DE_POINTS_EN_MOINS_CAR_MAUVAISES_CALCULEES



-- Requête longue à faire, peut être gérer ca en PHP avec des variables dans lesquelles ont met le résultat de ces requêtes ?












--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------









--									CORRECTION DU MEA 								--




-- Correction simple 


-- Entités qui sont dans le MEA de l'élève et pas dans la correction
SELECT Nom_Entite
FROM ENTITE
WHERE ID_MEA=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4) AND Nom_Entite NOT IN (
	SELECT Nom_Entite
	FROM ENTITE
	WHERE ID_MEA=1
	);

-- Entités qui sont dans la correction et pas dans le MEA de l'élève
SELECT Nom_Entite
FROM ENTITE
WHERE ID_MEA=1 AND Nom_Entite NOT IN (
	SELECT Nom_Entite
	FROM ENTITE
	WHERE ID_MEA=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4)
	);


-- Entités qui sont dans la correction et pas dans le MEA de l'élève
-- +
-- Entités qui sont dans le MEA de l'élève et pas dans la correction
SELECT (
	(
	SELECT COUNT(Nom_Entite)
	FROM ENTITE
	WHERE ID_MEA=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4) AND Nom_Entite NOT IN (
		SELECT Nom_Entite
		FROM ENTITE
		WHERE ID_MEA=1)
	) +
	(
	SELECT COUNT(Nom_Entite)
	FROM ENTITE
	WHERE ID_MEA=1 AND Nom_Entite NOT IN (
		SELECT Nom_Entite
		FROM ENTITE
		WHERE ID_MEA=(SELECT MAX(ID_DD) FROM COPIE_ELEVE WHERE ID_Utilisateur=4) )
	)
) AS NOMBRE_DE_POINTS_EN_MOINS_CAR_MAUVAISES_ENTITES;







-- Correction Fine avec comme hypothèse que les noms d'associations sont les mêmes pour la correction et la copie_eleve
-- Choix automatique de l'ordinateur du nom des association :
	-- l'utilisateur indique quelles entités il veut lier par une association
	-- le nom de l'association est alors composé des deux noms des entités triés par ordre alphabétique et séparés par un underscore
	-- il est alors demandé à l'utilisateur les cardinalités pour chaque entité
	-- s'il veut lier 3 entités, c'est pareil, le nom de l'association est composé des trois noms des entités triés par ordre alphabétique et séparés par une underscore
	-- possibilité de lier une entité à une association déjà existante pour transformer une binaire en ternaire, voire une ternaire en quaternaire









SELECT (
	(
	SELECT COUNT(Nom_Entite)
	FROM ENTITE
	WHERE ID_MEA=(SELECT MAX(ID_MEA) FROM COPIE_ELEVE WHERE ID_Utilisateur=4)
	) -
	(
	SELECT COUNT(DISTINCT Nom_Entite)
	FROM (
		SELECT P1.Nom_Entite
		FROM PATTE P1
		WHERE ID_MEA=(SELECT MAX(ID_MEA) FROM COPIE_ELEVE WHERE ID_Utilisateur=4) AND P1.Nom_Entite IN (
			SELECT P2.Nom_Entite
			FROM PATTE P2
			WHERE ID_MEA=1 AND P1.Nom_Association=P2.Nom_Association AND P1.Libelle_Cardinalite=P2.Libelle_Cardinalite)
		HAVING (
				SELECT COUNT(P1.Nom_Entite)
				FROM PATTE P1
				WHERE ID_MEA=1
				GROUP BY P1.Nom_Association
				) = 
				(
				SELECT COUNT(P1.Nom_Entite)
				FROM PATTE P1
				WHERE ID_MEA=(SELECT MAX(ID_MEA) FROM COPIE_ELEVE WHERE ID_Utilisateur=4) AND P1.Nom_Entite IN (
					SELECT P2.Nom_Entite
					FROM PATTE P2
					WHERE ID_MEA=1 AND P1.Nom_Association=P2.Nom_Association AND P1.Libelle_Cardinalite=P2.Libelle_Cardinalite)
				GROUP BY P1.Nom_Association
				)
		) AS TT
	)
	) AS NOMBRE_DE_POINTS_EN_MOINS_CAR_MAUVAISES_ENTITES


-- Le GROUP BY P1.Nom_Association dans la clause HAVING pause problème lorsqu'il y a plus d'une association car les sous-requêtes retournet plusieurs lignes
-- C'est pourtant ce qui permet de vérifier qu'une association est liée au mêmes entités d'une part, et qu'elle est liée au même nombre d'entités d'autre part
-- Il faudrait pouvoir faire ces sous-requêtes pour chaque association présente dans le MEA de correction (si une association est présente dans le DD de l'élève et pas dans le DD de correction, pas la peine de vérifier tout, l'association est fausse)
		-- même fonctionnement que dans les script shell :
				-- for i in $listeAssociation
				-- do
				--		...........
				-- done

























/*					Contraintes non modélisables

Contraintes de OU exclusif entre deux entités
	Prenons deux tables table1 et table2: un même "objet" existe soit dans l'une, soit dans l'autre, soit n'existe pas
		Trigger qui vérifie, lors d'une insertion, que ce que l'on veut insérer dans la table1 n'existe pas déjà dans la table2 et inversement

Ces contraintes de vérification doivent être implémentées :
	o	entre les tables PROFESSEUR, ELEVE et UTILISATEUR lors de l'insertion d'un nouvel utilisateur : un utilisateur est soit Professeur,
		soit Elève, soit autre (possibilité d'avoir un utilisateur Administrateur) mais en aucun cas il ne peut avoir plusieurs statuts à la fois

	o	entre les tables ENTITE, ASSOCIATION et ATTRIBUT lors de l'insertion d'attributs : un Attribut peut être associé soit à un Entité,
		soit à une Association et en aucun cas aux deux à la fois. Il peut aussi n'être associé à aucun des deux

	o	entre les tables EXERCICE, MEA et COPIE_ELEVE : un MEA donné (ID_MEA) appartient soit à la correction d'un exercice,
		soit au "fake" d'un exercice, soit à une copie élève. Il ne peut exister s'il n'appartient pas à l'une de ces trois catégories
		et ne peux absolument pas appartenir à plus d'une catégorie à la fois

	o	entre les tables EXERCICE, DD et COPIE_ELEVE : un DD donné (ID_DD) appartient soit à la correction d'un exercice,
		soit au "fake" d'un exercice, soit à une copie élève. Il ne peut exister s'il n'appartient pas à l'une de ces trois
		catégories et ne peux absolument pas appartenir à plus d'une catégorie à la fois

	o 	entre les tables ATTRIBUT, CALCULEE ET PARAMETRE : une rubrique donnée ne peut être à la fois de type ATTRIBUT et de type CALCULEE ou PARAMETRE
		Une rubrique donnée appartient à l'un des trois type de façon exclusive.




*/




/*

				A AMELIORER PENDANT LE DEVELOPPEMENT DE L'APPLICATION






o	Se pencher plus avant sur les requêtes de modification des exercices de la part d'un utilisateur PROFESSEUR
	o	possibilité de supprimer un exercice ?
		•	auquel cas qu'advient il des copies élèves relatives à cet exercice là ?
	o	possibilité de modifier une correction ?
		•	auquel cas qu'advient-il des copies élèves déjà rendues et corrigées ?


o	Réfléchir à la possibilité pour un utilisateur PROFESSEUR de vérifier une correction faite automatiquement par l'ordinateur ?
	o	création d'une table correction ?
	o	la correction génère t'elle un fichier? des notes par catégories? une note globale?


*/


















