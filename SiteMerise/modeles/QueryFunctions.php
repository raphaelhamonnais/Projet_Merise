<?php

include_once __DIR__ . "/connectDataBase.php";

/**
 * Created by PhpStorm.
 * User: raphael
 * Date: 08/04/15
 * Time: 06:59
 */

class QueryFunctions {


    /** ============================================================================================================ */
    /** ============================================================================================================ */
    /** ============================          FONCTIONS SUR LES UTILISATEURS           ============================= */
    /** ============================================================================================================ */
    /** ============================================================================================================ */


    /**
     * Fonction qui retourne toutes les infos pour un ID_User donné
     * @param PDO $bdd
     * @param $idUser
     * @return mixed
     */
    public static function getInfosUtilisateur($bdd, $idUser) {
        $infos = $bdd->query("SELECT * FROM UTILISATEUR WHERE ID_Utilisateur=$idUser");
        return $infos;
    }



    /**
     * fonction qui retourne la liste de tous les professeurs
     * @param PDO $bdd
     * @return mixed
     */
    public static function getProfesseurs($bdd) {
        $listeProf = $bdd->query("SELECT * FROM UTILISATEUR NATURAL JOIN PROFESSEUR");
        return $listeProf;
    }

    /**
     * fonction qui retourne la liste de tous les professeurs triés par nom
     * @param PDO $bdd
     * @return mixed
     */
    public static function getProfesseursOrderName($bdd) {
        $listeProf = $bdd->query("SELECT * FROM UTILISATEUR NATURAL JOIN PROFESSEUR ORDER BY Nom_Utilisateur ASC");
        return $listeProf;
    }

    /**
     * fonction qui retourne la liste de tous les élèves
     * @param PDO $bdd
     * @return mixed
     */
    public static function getEleves($bdd) {
        $listeEleve = $bdd->query("SELECT * FROM UTILISATEUR NATURAL JOIN ELEVE");
        return $listeEleve;
    }



    /**
     * fonction qui retourne la liste de tous les élèves triés par nom
     * @param PDO $bdd
     * @return mixed
     */
    public static function getElevesOrderName($bdd) {
        $listeEleve = $bdd->query("SELECT * FROM UTILISATEUR NATURAL JOIN ELEVE ORDER BY Nom_Utilisateur ASC");
        return $listeEleve;
    }

    /**
     * Fonction qui retourne les infos du groupe auquel un élève donné appartient
     * @param PDO $bdd
     * @param $idEleve
     * @return mixed
     */
    public static function getGroupeUnEleve($bdd, $idEleve) {
        $sonGroupe = $bdd->query("SELECT * FROM ELEVE NATURAL JOIN GROUPE WHERE ID_Utilisateur=$idEleve");
        $result = $sonGroupe->fetch();
        return $result;
    }


    /**
     * fonction qui retourne la liste des étudiants d'un groupe donné
     * @param PDO $bdd
     * @param $idGroupe
     * @return mixed
     */
    public static function getEtuGroupe($bdd, $idGroupe) {
        $listeEtuGroupe = $bdd->query("SELECT * FROM UTILISATEUR NATURAL JOIN ELEVE WHERE ID_Groupe = $idGroupe");
        return $listeEtuGroupe;
    }



    /**
     * Fonction qui supprime définitivement un élève ainsi que tous ses exercices
     * @param PDO $bdd
     * @param $idEleve
     */
    public static function supprimerEleve ($bdd, $idEleve) {
        $listeCopies = QueryFunctions::getAllCopiesUnEleve($bdd, $idEleve);
        while ($row_listeCopies = $listeCopies->fetch()) {
            $idCopie = $row_listeCopies['ID_Copie_Eleve'];
            $idMEA = $row_listeCopies['ID_MEA'];
            $idDD = $row_listeCopies['ID_DD'];

            $bdd->query("DELETE FROM ATTRIBUT WHERE ID_DD=$idDD");
            $bdd->query("DELETE FROM PATTE WHERE ID_MEA=$idMEA");
            $bdd->query("DELETE FROM ASSOCIATION WHERE ID_MEA=$idMEA");
            $bdd->query("DELETE FROM ENTITE WHERE ID_MEA=$idMEA");
            $bdd->query("DELETE FROM PARAMETRE WHERE ID_DD=$idDD");
            $bdd->query("DELETE FROM CALCULEE_A_PARTIR_DE WHERE ID_DD=$idDD");
            $bdd->query("DELETE FROM CALCULEE WHERE ID_DD=$idDD");
            $bdd->query("DELETE FROM RUBRIQUE WHERE ID_DD=$idDD");
            $bdd->query("DELETE FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopie");
            $bdd->query("DELETE FROM DD WHERE ID_DD=$idDD");
            $bdd->query("DELETE FROM MEA WHERE ID_MEA=$idMEA");
        }
        $bdd->query("DELETE FROM ELEVE WHERE ID_Utilisateur=$idEleve");
        $bdd->query("DELETE FROM UTILISATEUR WHERE ID_Utilisateur=$idEleve");

    }











    /** ============================================================================================================ */
    /** ============================================================================================================ */
    /** ============================          FONCTIONS SUR LES PROMOTIONS             ============================= */
    /** ============================================================================================================ */
    /** ============================================================================================================ */





    /**
     * fonction qui donne la liste de toutes les promotions
     * @param PDO $bdd
     * @return mixed
     */
    public static function getPromotions($bdd) {
        $listePromo = $bdd->query("SELECT * FROM PROMOTION");
        return $listePromo;
    }



    /**
     * fonction qui donne la liste de promotions dans lesquelles un professeur donné gère des groupes
     * @param PDO $bdd
     * @param $idProf
     * @return mixed
     */
    public static function getPromosProf($bdd, $idProf) {
        $query =
            "SELECT DISTINCT ID_Promotion, Nom_Promotion, Annee_Promotion
            FROM PROMOTION NATURAL JOIN GROUPE NATURAL JOIN PROF_GERE_GROUPE
            WHERE ID_Utilisateur = $idProf
            ORDER BY Annee_Promotion DESC";
        $listePromosProf = $bdd->query($query);
        return $listePromosProf;
    }


    /**
     * fonction retournant l'année d'une promotion
     * @param PDO $bdd
     * @param $idPromo
     * @return int
     */
    public static function getAnneePromo($bdd, $idPromo){
        $query =
            "SELECT Annee_Promotion
            FROM PROMOTION
            WHERE ID_Promotion = :id";
        $exec=$bdd->prepare($query);
        $exec->bindParam(':id',$idPromo);
        $exec->execute();
        $result=$exec->fetch(PDO::FETCH_NUM);
        return $result[0];
    }

    /**
     * @param PDO $bdd
     * @param $idPromo
     * @return mixed
     */
    public static function getNomPromo($bdd, $idPromo){
        $query =
            "SELECT Nom_Promotion
            FROM PROMOTION
            WHERE ID_Promotion = :id";
        $exec=$bdd->prepare($query);
        $exec->bindParam(':id',$idPromo,PDO::PARAM_INT);
        $exec->execute();
        $result=$exec->fetch();
        return $result[0];
    }











    /** ============================================================================================================ */
    /** ============================================================================================================ */
    /** ============================            FONCTIONS SUR LES GROUPES              ============================= */
    /** ============================================================================================================ */
    /** ============================================================================================================ */

    /**
     * Fonction qui renvoie vrai si le groupe appartient à une promo et faux sinon
     * @param PDO $bdd
     * @param $idGroupe
     * @return bool
     */
    public static function isGroupeDansUnePromo ($bdd, $idGroupe) {
        $query = "SELECT COUNT(ID_Groupe) AS RESULTAT
                  FROM GROUPE
                  WHERE ID_Groupe=$idGroupe AND ID_Promotion IS NULL";
        $pdoStatment = $bdd->query($query);
        $result = $pdoStatment->fetch();
        if ($result['RESULTAT'] > 0) { // le groupe n'a pas de promo
            return false;
        }
        else {
            return true;
        }

    }



    /**
     * Fonction qui supprime définitivement un groupe et tous ses élèves
     * @param PDO $bdd
     * @param $idGroupe
     */
    public static function delGroupeDefinitif ($bdd, $idGroupe) {
        $bdd->query("DELETE FROM PROF_GERE_GROUPE WHERE ID_Groupe=$idGroupe");
        $resultListeEleves = QueryFunctions::getEtuGroupe($bdd, $idGroupe);
        while ($row = $resultListeEleves->fetch()) {
            $idEleve = $row['ID_Utilisateur'];
            QueryFunctions::supprimerEleve($bdd, $idEleve);
        }
        $bdd->query("DELETE FROM GROUPE WHERE ID_Groupe=$idGroupe");
    }

    /**
     * Fonction qui retourne toutes les infos d'une promotion pour un groupe donné
     * @param PDO $bdd
     * @param $idGroupe
     * @return mixed
     */
    public static function getInfosPromoUnGroupe ($bdd, $idGroupe) {
        $query = "SELECT * FROM GROUPE NATURAL JOIN PROMOTION
                  WHERE ID_Groupe=$idGroupe";
        $pdoStatment = $bdd->query($query);
        $reponse = $pdoStatment->fetch();
        return $reponse;
    }

    /**
     * retourne l'id de la promotion à laquelle appartient un groupe donné
     * @param PDO $bdd
     * @param $idGroupe
     * @return int
     */
    public static function getIDPromoUnGroupe ($bdd, $idGroupe) {
        $query = "SELECT ID_Promotion FROM GROUPE
                  WHERE ID_Groupe=$idGroupe";
        $pdoStatment = $bdd->query($query);
        $reponse = $pdoStatment->fetch();
        if (isset($reponse)) {
            return $reponse['ID_Promotion'];
        }
        else {
            return 0;
        }
    }

    /**
     * Fonction qui retourne le nom d'un groupe d'un ID donnée
     * @param PDO $bdd
     * @param $idGroupe
     * @return string
     */
    public static function getNomGroupe ($bdd, $idGroupe) {
        $query = "SELECT Nom_Groupe FROM GROUPE WHERE ID_Groupe=$idGroupe";
        $pdoStatment = $bdd->query($query);
        $row = $pdoStatment->fetch();
        return (string)$row['Nom_Groupe'];
    }

    /**
     * Fonction qui retourne le nom de la promotion à laquelle appartient un groupe donné
     * @param PDO $bdd
     * @param $idGroupe
     * @return string
     */
    public static function getNomPromoduGroupe ($bdd, $idGroupe) {
        $query = "SELECT Nom_Promotion FROM GROUPE NATURAL JOIN PROMOTION
                  WHERE ID_Groupe=$idGroupe";
        $pdoStatment = $bdd->query($query);
        $reponse = $pdoStatment->fetch();
        if (isset($reponse['Nom_Promotion'])) {
            return (string)$reponse['Nom_Promotion'];
        }
        else {
            $reponse = "Aucune promotion pour ce groupe";
            return (string)$reponse;
        }
    }


    /**
     * Fonction qui retourne l'année de la promotion à laquelle appartient un groupe donné
     * @param PDO $bdd
     * @param $idGroupe
     * @return int|string
     */
    public static function getAnneePromoduGroupe ($bdd, $idGroupe) {
        $query = "SELECT Annee_Promotion FROM GROUPE NATURAL JOIN PROMOTION
                  WHERE ID_Groupe=$idGroupe";
        $pdoStatment = $bdd->query($query);
        $reponse = $pdoStatment->fetch();
        if (isset($reponse['Annee_Promotion'])) {
            return (int)$reponse['Annee_Promotion'];
        }
        else {
            $reponse = "Aucune promotion pour ce groupe";
            return $reponse;
        }
    }
    /**
     * fonction qui donne la liste de tous les groupes) => utile ?
     * @param PDO $bdd
     * @return PDOStatement
     */
    public static function getAllGroupes ($bdd) {
        $liste = $bdd->query("SELECT * FROM GROUPE");
        return $liste;
    }

    /**
     * Fonction qui retourne la liste des groupes qui ne sont pas assignés à une promotion
     * @param PDO $bdd
     * @return mixed
     */
    public static function getNakedGroupes ($bdd) {
        $liste = $bdd->query("SELECT ID_Groupe, Nom_Groupe, Commentaire_Groupe FROM GROUPE WHERE ID_Promotion IS NULL");
        return $liste;
    }


    /**
     * fonction qui donne la liste des groupes pour une promotion donnée
     * @param PDO $bdd
     * @param $idPromo
     * @return mixed
     */
    public static function getPromoGroupes ($bdd, $idPromo) {
        $liste = $bdd->query("SELECT ID_Groupe, Nom_Groupe, Commentaire_Groupe FROM GROUPE WHERE ID_Promotion = $idPromo");
        return $liste;
    }





    /**
     * fonction qui donne la liste des groupes pour un professeur donné (pour interface admin)
     * @param PDO $bdd
     * @param $idProf
     * @return mixed
     */
    public static function getProfGroupes ($bdd, $idProf) {
        $liste = $bdd->query("SELECT ID_Groupe, Nom_Groupe, Commentaire_Groupe FROM GROUPE NATURAL JOIN PROF_GERE_GROUPE WHERE ID_Utilisateur = $idProf");
        return $liste;
    }


    /**
     * fonction qui donne la liste des groupes pour une promotion et un prof donné (liste des groupes que le pof gère dans une promotion donnée)
     * @param PDO $bdd
     * @param $idPromotion
     * @param $idProf
     * @return mixed
     */
    public static function getProfPromotionGroupes ($bdd, $idPromotion, $idProf) {
        $liste = $bdd->query("
		SELECT ID_Groupe, Nom_Groupe, Commentaire_Groupe
		FROM GROUPE NATURAL JOIN PROF_GERE_GROUPE
		WHERE ID_Utilisateur = $idProf AND ID_Promotion = $idPromotion
		ORDER BY Nom_Groupe ASC"
        );
        return $liste;
    }







    /**
     * fonction qui donne les infos d'un groupe (nom, id, saFormation, sonAnnee, sonProfResponsable)
     * 		- appelera surement la fonction qui donne la liste des élèves pour un groupe donné
     * @param PDO $bdd
     * @param $idGroupe
     * @return mixed
     */
    public static function getInfosGroupe ($bdd, $idGroupe) {
        $liste = $bdd->query("
		SELECT ID_Groupe, Nom_Groupe, Commentaire_Groupe, ID_Promotion, ID_Utilisateur
		FROM GROUPE NATURAL JOIN PROF_GERE_GROUPE
		WHERE ID_Groupe = $idGroupe"
        );
        return $liste;
    }

    /**
     * fonction retournant si un nom de groupe est déja pris.
     * @param PDO $bdd
     * @param $nom
     * @param $promo
     * @return bool
     */
    public static function nomGroupeExiste ($bdd,$nom,$promo){
        $query="
			SELECT COUNT(NOM_GROUPE)
			FROM GROUPE
			WHERE Nom_Groupe=:nom AND ID_Promotion=:idPromo
		";
        $exec=$bdd->prepare($query);
        $exec->bindParam(':nom',$nom);
        $exec->bindParam(':idPromo',$promo);
        $exec->execute();
        $result=$exec->fetch(PDO::FETCH_NUM);
        if ($result[0]==1){
            return true;
        }
        return false;

    }

    /**
     * Fonction qui retourne l'iD d'un groupe lorsque l'on a son nom et l'id de la promo à laquelle il appartient
     * @param PDO $bdd
     * @param $choixPromo
     * @param $nomGroupe
     * @return mixed
     */
    public static function getIDGroupe($bdd,$choixPromo,$nomGroupe){
        $query="
            SELECT ID_GROUPE
            FROM GROUPE
            WHERE ID_Promotion=$choixPromo AND Nom_Groupe=:nom
        ";
        $exec= $bdd->prepare($query);
        $exec->bindParam(':nom',$nomGroupe);
        $exec->execute();
        $result=$exec->fetch(PDO::FETCH_NUM);
        return $result[0];
    }








    /** =======================             Fonctions de Delete                 =================================== */

    /**
     * Fonction qui supprime un groupe de sa promotion (SET à NULL)
     * @param PDO $bdd
     * @param $idGroupe
     */
    public static function delGroupeFromPromo ($bdd, $idGroupe) {
        $query = "UPDATE GROUPE SET ID_Promotion=NULL WHERE ID_Groupe=$idGroupe";
        $bdd->query($query);
    }









    /** ============================================================================================================ */
    /** ============================================================================================================ */
    /** ============================          FONCTIONS SUR LES EXERCICES              ============================= */
    /** ============================================================================================================ */
    /** ============================================================================================================ */





    /** ======================================================================================== */
    /** FONCTIONS GENERALES SUR LES EXERCICES */
    /** ======================================================================================== */

    /**
     * @param PDO $bdd
     * @param $nomExercice
     * @param $enonce string => le chemin du fichier dans uploads
     * @param $idTypeExercice
     * @param $MEA_Correction
     * @param $MEA_Fake
     * @param $DD_Correction
     * @param $DD_Fake
     */
    public static function insertNewExercice ($bdd, $nomExercice, $enonce, $idTypeExercice, $MEA_Correction, $MEA_Fake, $DD_Correction, $DD_Fake) {
        $query = " INSERT INTO EXERCICE(Nom_Exercice, Enonce_Exercice, ID_Type_Exercice, ID_MEA_Correction, ID_MEA_Fake, ID_DD_Correction, ID_DD_Fake, Exercice_Pret)
                    VALUES ('$nomExercice', '$enonce', $idTypeExercice, $MEA_Correction, $MEA_Fake, $DD_Correction, $DD_Fake, 0)
                ";
        $bdd->query($query);
    }



    /**
     * fonction qui retourne la liste des types d'exercices disponibles
     * @param PDO $bdd
     * @return mixed
     */
    public static function getAllTypeExercices ($bdd) {
        $liste = $bdd->query("SELECT * FROM TYPE_EXERCICE ORDER BY ID_Type_Exercice ASC");
        return $liste;
    }



    /**
     * fonction qui retourne tous les exercices différents, classés par type
     * @param PDO $bdd
     * @return mixed
     */
    public static function getAllExercices ($bdd) {
        $liste = $bdd->query("SELECT * FROM EXERCICE NATURAL JOIN TYPE_EXERCICE ORDER BY ID_Type_Exercice ASC");
        return $liste;
    }



    /**
     * fonction qui retourne tous les exercices différents, classés par type, qui sont en ligne
     * @param PDO $bdd
     * @return mixed
     */
    public static function getAllExercicesEnLigne ($bdd) {
        $liste = $bdd->query("SELECT * FROM EXERCICE NATURAL JOIN TYPE_EXERCICE WHERE Exercice_Pret ORDER BY ID_Type_Exercice ASC");
        return $liste;
    }


    /**
     * fonction qui retourne tous les exercices d'un type donné
     * @param PDO $bdd
     * @param $typeExercice
     * @return mixed
     */
    public static function getExercicesByType ($bdd, $typeExercice) {
        $liste = $bdd->query("SELECT * FROM EXERCICE NATURAL JOIN TYPE_EXERCICE WHERE ID_Type_Exercice = $typeExercice");
        return $liste;
    }


    /**
     * fonction qui retourne tous les exercices d'un type donné effectués par un élève
     * @param PDO $bdd
     * @param $typeExercice
     * @return mixed
     */
    public static function getExercicesEleveByType ($bdd, $typeExercice, $idEleve) {
        $liste = $bdd->query("SELECT DISTINCT ID_Exercice, Nom_Exercice, Enonce_Exercice, Commentaire_Type_Exercice, ID_Type_Exercice
                              FROM COPIE_ELEVE NATURAL JOIN EXERCICE NATURAL JOIN TYPE_EXERCICE
                              WHERE ID_Type_Exercice = $typeExercice AND ID_Utilisateur=$idEleve");
        return $liste;
    }


    /**
     * Fonction qui retourne le nombre d'exercices pour un id_type_exercice donné
     * @param PDO $bdd
     * @param $typeExercice
     * @return int
     */
    public static function countExercicesEleveByType ($bdd, $typeExercice, $idEleve) {
        $liste = $bdd->query("SELECT COUNT(ID_Exercice) AS NB
                              FROM COPIE_ELEVE NATURAL JOIN EXERCICE NATURAL JOIN TYPE_EXERCICE
                              WHERE ID_Type_Exercice = $typeExercice AND ID_Utilisateur=$idEleve");
        $resutl = $liste->fetch();
        return (int)$resutl['NB'];
    }

    /**
     * Fonction qui retourne le nombre d'exercices pour un id_type_exercice donné
     * @param PDO $bdd
     * @param $typeExercice
     * @return int
     */
    public static function countExercicesByType ($bdd, $typeExercice) {
        $liste = $bdd->query("SELECT COUNT(ID_Exercice) AS NB FROM EXERCICE NATURAL JOIN TYPE_EXERCICE WHERE ID_Type_Exercice = $typeExercice");
        $resutl = $liste->fetch();
        return (int)$resutl['NB'];
    }

    /**
     * Fonction qui retourne le nom d'un type d'exercice pour un ID_Type_Exercice donné
     * @param PDO $bdd
     * @param $typeExercice
     * @return string
     */
    public static function getNomTypeExercice ($bdd, $typeExercice) {
        $query = $bdd->query("SELECT Commentaire_Type_Exercice FROM TYPE_EXERCICE WHERE ID_Type_Exercice = $typeExercice");
        $row = $query->fetch();
        return (string)$row['Commentaire_Type_Exercice'];
    }

    /**
     * Fonction qui retourne vrai si un nom passé en paramètre est disponible pour créer un nouvel exercice de type donné
     * @param PDO $bdd
     * @param $nomExercice
     * @param $typeExercice
     * @return bool
     */
    public static function nomExerciceNonDispo ($bdd, $nomExercice, $typeExercice) {
        $liste = $bdd->query("SELECT COUNT(ID_Exercice) AS NB FROM EXERCICE NATURAL JOIN TYPE_EXERCICE WHERE ID_Type_Exercice = $typeExercice AND Nom_Exercice='$nomExercice'");
        $resutl = $liste->fetch();
        if ($resutl['NB'] > 0) { // dans ce cas le nom est déjà prit
            return true;
        }
        else { // le nom est disponible
            return false;
        }
    }

    /**
     * Fonction qui retourne vrai si un énoncé passé en paramètre est disponible pour créer un nouvel exercice de type donné
     * @param PDO $bdd
     * @param $enonceExercice
     * @param $typeExercice
     * @return bool
     */
    public static function enonceExerciceNonDispo ($bdd, $enonceExercice, $typeExercice) {
        $liste = $bdd->query("SELECT COUNT(ID_Exercice) AS NB FROM EXERCICE NATURAL JOIN TYPE_EXERCICE WHERE ID_Type_Exercice = $typeExercice AND Enonce_Exercice='$enonceExercice'");
        $resutl = $liste->fetch();
        if ($resutl['NB'] > 0) { // dans ce cas le nom de l'énoncé est déjà prit
            return true;
        }
        else { // le nom est disponible
            return false;
        }
    }



    /**
     * fonction qui retourne tous les exercices pour un élève donné
     * @param PDO $bdd
     * @param $idEleve
     * @return mixed
     */
    public static function getEleveExercices ($bdd, $idEleve) {
        $liste = $bdd->query("
                SELECT *
                FROM EXERCICE NATURAL JOIN TYPE_EXERCICE
                WHERE ID_Exercice IN (
                  SELECT DISTINCT ID_Exercice
                  FROM COPIE_ELEVE
                  WHERE ID_Utilisateur = $idEleve
                )
                ORDER BY ID_Type_Exercice ASC
                ");
        return $liste;
    }


    /**
     * fonction qui renvoi la liste des exercices pour un élève donné et un ID_Type_Exercice donné
     * @param PDO $bdd
     * @param $idEleve
     * @param $idType
     * @return mixed
     */
    public static function getEleveExercicesByType ($bdd, $idEleve, $idType) {
        $liste = $bdd->query("
                SELECT *
                FROM EXERCICE NATURAL JOIN TYPE_EXERCICE
                WHERE ID_Exercice IN (
                  SELECT DISTINCT ID_Exercice
                  FROM COPIE_ELEVE
                  WHERE ID_Utilisateur = $idEleve
                ) AND ID_Type_Exercice = $idType
                ORDER BY ID_Type_Exercice ASC
                ");
        return $liste;
    }

    /**
     * fonction qui retourne la liste des copies d'un élève pour un exercice donné
     * @param PDO $bdd
     * @param $idEleve
     * @param $idExercice
     * @return mixed
     */
    public static function getCopiesExerciceEleve ($bdd, $idEleve, $idExercice) {
        $liste = $bdd->query("
                            SELECT * FROM COPIE_ELEVE WHERE ID_Utilisateur = $idEleve AND ID_Exercice = $idExercice
                            ORDER BY Date_Derniere_Modif_Copie_Eleve DESC
                            ");
        return $liste;
    }

    /**
     * Fonction qui retourne toutes les infos pour une copie élève donnée
     * @param PDO $bdd
     * @param $idCopieEleve
     * @return mixed
     */
    public static function getInfosUneCopie ($bdd, $idCopieEleve) {
        $query = "SELECT * FROM COPIE_ELEVE NATURAL JOIN EXERCICE NATURAL JOIN TYPE_EXERCICE WHERE ID_Copie_Eleve=$idCopieEleve";
        $pdoStatment = $bdd->query($query);
        $result = $pdoStatment->fetch();
        return $result;
    }
    /**
     * Fonction qui renvoie vrai si la copie a été rendue par l'élève
     * @param PDO $bdd
     * @param $idCopie
     * @return bool
     */
    public static function copieRendue ($bdd, $idCopie) {
        $query = "SELECT Date_Envoi_Copie_Eleve FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopie";
        $pdoStatement = $bdd->query($query);
        $result = $pdoStatement->fetch();
        if ($result['Date_Envoi_Copie_Eleve'] == null) {
            return false;
        }
        else {
            return true;
        }
    }

    /**
     * Fonction qui retourne la liste de toutes les copies effectuées d'un élève
     * @param PDO $bdd
     * @param $idEleve
     * @return mixed
     */
    public static function getCopiesEleve ($bdd, $idEleve) {
        $liste = $bdd->query("
                            SELECT * FROM COPIE_ELEVE NATURAL JOIN EXERCICE
                            WHERE ID_Utilisateur = $idEleve
                            ORDER BY Date_Derniere_Modif_Copie_Eleve DESC
                            ");
        return $liste;
    }



    /**
     * Fonction qui retourne la liste de tous les exercices effectués par un élève donné
     * @param PDO $bdd
     * @param $idEleve
     * @return mixed
     */
    public static function getExercicesEleve ($bdd, $idEleve) {
        $liste = $bdd->query("
                            SELECT DISTINCT ID_Exercice FROM COPIE_ELEVE
                            WHERE ID_Utilisateur = $idEleve
                            ");
        return $liste;
    }

    /**
     * Fonction qui retourne la liste des types de données possibles
     * @param PDO $bdd
     * @return mixed
     */
    public static function getTypesDonneesPossibles ($bdd) {
        $query = "SELECT * FROM TYPE_DONNEE ORDER BY Libelle_Type_Donnee ASC";
        $result = $bdd->query($query);
        return $result;
    }




    /**
     * Fonction qui retourne la liste des cardinalités possibles
     * @param PDO $bdd
     * @return mixed
     */
    public static function getCardinalitesPossibles ($bdd) {
        $query = "SELECT * FROM TYPE_CARDINALITE ORDER BY Libelle_Cardinalite ASC";
        $result = $bdd->query($query);
        return $result;
    }























    /** ======================================================================================== */
    /**                   FONCTIONS DE CORRECTION AUTOMATIQUE D'UN EXERCICE                      */
    /** ======================================================================================== */


    /**
     * @param PDO $bdd
     * @param $idExercice
     * @return int
     */
    public static function getIDDDCorrection ($bdd, $idExercice) {
        $query = $bdd->query("SELECT ID_DD_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice");
        $result = $query->fetch();
        return (int)$result['ID_DD_Correction'];
    }


    /**
     * @param PDO $bdd
     * @param $idExercice
     * @return int
     */
    public static function getIDMEACorrection ($bdd, $idExercice) {
        $query = $bdd->query("SELECT ID_MEA_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice");
        $result = $query->fetch();
        return (int)$result['ID_MEA_Correction'];
    }


    /**
     * @param PDO $bdd
     * @param int $idExercice
     * @return int
     */
    public static function countTotalPointsExercice ($bdd, $idExercice) {
        // Points pour les entites
        $countEntites = $bdd->query("SELECT COUNT(Nom_Entite) AS NB
                                      FROM ENTITE
                                      WHERE ID_MEA=(SELECT ID_MEA_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice)");
        $result = $countEntites->fetch();
        $pointsEntites = $result['NB'];



        // Points pour les associations
        $countAssociations = $bdd->query("SELECT COUNT(Nom_Association) AS NB
                                          FROM ASSOCIATION
                                          WHERE ID_MEA=(SELECT ID_MEA_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice)");
        $result = $countAssociations->fetch();
        $pointsAssociations = $result['NB'];



        // Points pour les rubriques
        $countRubriques = $bdd->query("SELECT COUNT(Nom_Rubrique) AS NB
                                          FROM RUBRIQUE
                                          WHERE ID_DD=(SELECT ID_DD_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice)");
        $result = $countRubriques->fetch();
        $pointsRubriques = $result['NB'];



        $pointsExercice = $pointsEntites + $pointsAssociations + $pointsRubriques;
        return (int)$pointsExercice;
    }




    /**
     *
     * @param PDO $bdd
     * @param $idExercice
     * @return string
     */
    public static function verifierIntegriteCorrection ($bdd, $idExercice) {
        $message = "";

        /** Vérification qu'il n'y ait pas des entités non reliées par au moins une association
         * C'est a dire des Entités sans aucune patte
         */
        $entitesSansAssociations =
            "SELECT Nom_Entite, COUNT(DISTINCT Nom_Entite, Nom_Association) AS NOMBRE_PATTE
            FROM ENTITE NATURAL LEFT JOIN PATTE
            WHERE ID_MEA = (SELECT ID_MEA_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice)
            GROUP BY Nom_Entite
            HAVING COUNT(DISTINCT Nom_Entite, Nom_Association)=0";
        $pdoStatment = $bdd->query($entitesSansAssociations);

        $arrayEntites = array();
        while ($result = $pdoStatment->fetch()) {
            array_push($arrayEntites, $result['Nom_Entite']);
        }
        if ($pdoStatment->rowCount()>0) {
            $message = "Votre correction n'est pas intègre ! <br/>";
            $message = $message . "Vous avez une ou plusieurs entités qui ne sont pas reliées par une association : ";
            foreach ($arrayEntites as $nomEntite) {
                $message = $message . $nomEntite . " ";
            }
        }

        return $message;
    }


    /**
     *
     * @param PDO $bdd
     * @param $idCopieEleve
     * @return string
     */
    public static function verifierIntegriteCopie ($bdd, $idCopieEleve) {
        $message = "";

        /** Vérification qu'il n'y ait pas des entités non reliées par au moins une association
         * C'est a dire des Entités sans aucune patte
         */
        $entitesSansAssociations =
            "SELECT Nom_Entite, COUNT(DISTINCT Nom_Entite, Nom_Association) AS NOMBRE_PATTE
            FROM ENTITE NATURAL LEFT JOIN PATTE
            WHERE ID_MEA = (SELECT ID_MEA FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopieEleve)
            GROUP BY Nom_Entite
            HAVING COUNT(DISTINCT Nom_Entite, Nom_Association)=0";
        $pdoStatment = $bdd->query($entitesSansAssociations);

        $arrayEntites = array();
        while ($result = $pdoStatment->fetch()) {
            array_push($arrayEntites, $result['Nom_Entite']);
        }
        if ($pdoStatment->rowCount()>0) {
            $message = "Votre correction n'est pas intègre ! <br/>";
            $message = $message . "Vous avez une ou plusieurs entités qui ne sont pas reliées par une association : ";
            foreach ($arrayEntites as $nomEntite) {
                $message = $message . $nomEntite . " ";
            }
        }
        return $message;
    }


    /**
     * @param PDO $bdd
     * @param $idCopieEleve
     * @return int
     */
    public static function getIDDDCopieEleve ($bdd, $idCopieEleve) {
        $query = $bdd->query("SELECT ID_DD FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopieEleve");
        $result = $query->fetch();
        return (int)$result['ID_DD'];
    }


    /**
     * @param PDO $bdd
     * @param $idCopieEleve
     * @return int
     */
    public static function getIDMEACopieEleve ($bdd, $idCopieEleve) {
        $query = $bdd->query("SELECT ID_MEA FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopieEleve");
        $result = $query->fetch();
        return (int)$result['ID_MEA'];
    }

    /**
     * @param PDO $bdd
     * @param $idExercice
     * @param $idCopieEleve
     * @return float
     */
    public static function calculerPointsCopies ($bdd, $idExercice, $idCopieEleve) {
        $ID_DD_Correction = QueryFunctions::getIDDDCorrection($bdd, $idExercice);
        $ID_MEA_Correction = QueryFunctions::getIDMEACorrection($bdd, $idExercice);
        $ID_DD_Copie = QueryFunctions::getIDDDCopieEleve($bdd, $idCopieEleve);
        $ID_MEA_Copie = QueryFunctions::getIDMEACopieEleve($bdd, $idCopieEleve);

        $baremeExercice = QueryFunctions::countTotalPointsExercice($bdd, $idExercice);
        $pointsEleve = $baremeExercice;



        // calcul des points de l'élève pour les Entités
        $entitesEleves = QueryFunctions::getEntitesCopieEleve($bdd, $idCopieEleve);
        $entitesCorrection = QueryFunctions::getEntitesCorrectionExercice($bdd, $idExercice);
        while ($rowEleve = $entitesEleves->fetch() && $rowCorrection = $entitesCorrection->fetch()) {
            if (!QueryFunctions::entitePresenteDansMEA($bdd, $ID_MEA_Copie, $rowCorrection['Nom_Entite'])) {
                // si l'entité de la correction n'est pas présente dans le MEA de l'élève (il ne l'a pas prise)
                if ($pointsEleve != 0) {
                    $pointsEleve--;
                }
            }
        }




        // calcul des points de l'élève pour les Associations
        $associationsEleve = QueryFunctions::getAssociationsCopieEleve($bdd, $idCopieEleve);
        $associationsCorrection = QueryFunctions::getAssociationsCorrectionExercice($bdd, $idExercice);

        while ($rowCorrection = $associationsCorrection->fetch()) {
            // si l'association de correction n'est pas présente dans le MEA de la copie
            if (!QueryFunctions::associationPresenteDansMEA($bdd, $ID_MEA_Copie, $rowCorrection['Nom_Association'])) {
                if ($pointsEleve != 0) {
                    $pointsEleve--;
                }
            }
        }

        while ($rowEleve = $associationsEleve->fetch()) {
            // si l'association de l'élève est présente dans le MEA de correction
            if (QueryFunctions::associationPresenteDansMEA($bdd, $ID_MEA_Correction, $rowEleve['Nom_Association'])) {
                // alors si les cardinalités pour chaque entité reliée sont correctes
                if (!QueryFunctions::associationEleveCorrecte($bdd, $ID_MEA_Copie, $ID_MEA_Correction, $rowEleve['Nom_Association'])) {
                    if ($pointsEleve != 0) {
                        $pointsEleve--;
                    }
                }
            }
            else {
                if ($pointsEleve != 0) {
                    $pointsEleve--;
                }
            }
        }







        // calcul des points de l'élève pour les Attributs
        $attributsEleve = QueryFunctions::getAttributsCopieEleve($bdd, $idCopieEleve);
        $attributsCorrection = QueryFunctions::getAttributsCorrectionExercice($bdd, $idExercice);

        // verifier pour tous les attributs de l'élève qu'ils soient présents dans la correction et identiques à cette dernière
        while ($rowEleve = $attributsEleve->fetch()) {
            // si l'attribut de l'élève est correct
            if (!QueryFunctions::attributCorrect($bdd, $ID_DD_Correction, $rowEleve['Nom_Attribut'], $rowEleve['Cle_Primaire'], $rowEleve['Nom_Entite'], $rowEleve['Nom_Association'], $rowEleve['ID_Type_Donnee'])) {
                if ($pointsEleve != 0) {
                    $pointsEleve--;
                }
            }
        }

        // vérifier pout chaque attribut de la correction s'il est présent dans la copie de l'élève
        while ($rowCorrection = $attributsCorrection->fetch()) {
            if (!QueryFunctions::attributPresentDansDD($bdd, $ID_DD_Copie, $rowCorrection['Nom_Attribut'])) {
                if ($pointsEleve != 0) {
                    $pointsEleve--;
                }
            }
        }








        // calcul des points de l'élève pour les Parametres
        $parametresEleve = QueryFunctions::getParametresCopieEleve($bdd, $idCopieEleve);
        $parametresCorrection = QueryFunctions::getParametresCorrectionExercice($bdd, $idExercice);

        // si le paramètre de l'élève est présent dans le DD de correction
        while ($rowEleve = $parametresEleve->fetch()) {
            if (!QueryFunctions::parametrePresentDansDD($bdd, $ID_DD_Correction, $rowEleve['Nom_Parametre'])) {
                if ($pointsEleve != 0) {
                    $pointsEleve--;
                }
            }
        }

        // si le paramètre de la correction n'est pas présent dans le DD de la copie
        while ($rowCorrection = $parametresCorrection->fetch()) {
            if (!QueryFunctions::parametrePresentDansDD($bdd, $ID_DD_Copie, $rowCorrection['Nom_Parametre'])) {
                if ($pointsEleve != 0) {
                    $pointsEleve--;
                }
            }
        }




        // calcul des points de l'élève pour les Calculées
        $calculeesEleve = QueryFunctions::getCalculeesCopieEleve($bdd, $idCopieEleve);
        $calculeesCorrection = QueryFunctions::getCalculeesCorrectionExercice($bdd, $idExercice);

        // si la calculée de la correction n'est pas présente dans le DD de la copie de l'élève
        while ($rowCorrection = $calculeesCorrection->fetch()) {
            if (!QueryFunctions::calculeePresenteDansDD($bdd, $ID_DD_Copie, $rowCorrection['Nom_Calculee'])) {
                if ($pointsEleve != 0) {
                    $pointsEleve--;
                }
            }
        }



        while ($rowEleve = $calculeesEleve->fetch()) {
            if (QueryFunctions::calculeePresenteDansDD($bdd, $ID_DD_Correction, $rowEleve['Nom_Calculee'])) {
                $infosCalculee = QueryFunctions::getCalculeeAPartirDe($bdd, $ID_DD_Copie, $rowEleve['Nom_Calculee']);
                $tmp = 0;
                while ($row = $infosCalculee->fetch()) {
                    if (QueryFunctions::calculeeCorrecte($bdd, $ID_DD_Correction, $row['Nom_Calculee'], $row['Nom_Rubrique']))
                        $tmp++;
                }
                if ($tmp != 0) {
                    if ($pointsEleve != 0) {
                        //$pointsEleve--; //todo probleme de correction pour les calculées
                    }
                }
            }
        }



        $noteEleve = ($pointsEleve/$baremeExercice)*20;
        return $noteEleve;



    }



















    /**
     * @param PDO $bdd
     * @param $idDD
     * @param $nomCalculee
     * @return bool
     */
    public static function calculeePresenteDansDD ($bdd, $idDD, $nomCalculee) {
        $query = $bdd->query("SELECT COUNT(Nom_Calculee) AS NB FROM CALCULEE WHERE ID_DD=$idDD AND Nom_Calculee='$nomCalculee'");
        $result = $query->fetch();

        if ($result['NB'] > 0) {
            return true;
        }
        else {
            return false;
        }
    }


    /**
     * @param PDO $bdd
     * @param $ID_DD_Cible
     * @param $nomCalculee
     * @param $nomRubrique
     * @return bool
     */
    public static function calculeeCorrecte ($bdd, $ID_DD_Cible, $nomCalculee, $nomRubrique) {

        // si la "calculee a partir de" passée en paramètre est présent dans le DD passé en paramètre
        $query = $bdd->query("SELECT COUNT(Nom_Calculee) AS NB FROM CALCULEE_A_PARTIR_DE
                                WHERE ID_DD=$ID_DD_Cible
                                  AND Nom_Calculee='$nomCalculee'
                                  AND Nom_Rubrique='$nomRubrique'
                                  ");
        $result = $query->fetch();

        if ($result['NB'] > 0) {
            return true;
        }
        else {
            return false;
        }
    }


    /**
     * @param PDO $bdd
     * @param $idDD
     * @param $nomParametre
     * @return bool
     */
    public static function parametrePresentDansDD ($bdd, $idDD, $nomParametre) {
        $query = $bdd->query("SELECT COUNT(Nom_Parametre) AS NB FROM PARAMETRE WHERE ID_DD=$idDD AND Nom_Parametre='$nomParametre'");
        $result = $query->fetch();

        if ($result['NB'] > 0) {
            return true;
        }
        else {
            return false;
        }
    }


    /**
     * @param PDO $bdd
     * @param $idDD
     * @param $nomAttribut
     * @return bool
     */
    public static function attributPresentDansDD ($bdd, $idDD, $nomAttribut) {
        $query = $bdd->query("SELECT COUNT(Nom_Attribut) AS NB FROM ATTRIBUT WHERE ID_DD=$idDD AND Nom_Attribut='$nomAttribut'");
        $result = $query->fetch();

        if ($result['NB'] > 0) {
            return true;
        }
        else {
            return false;
        }
    }


    /**
     * @param PDO $bdd
     * @param $ID_DD_Cible
     * @param $nomAttribut
     * @param $clePrimaire
     * @param $Nom_Entite
     * @param $nomAssociation
     * @param $idTypeDonnee
     * @return bool
     */
    public static function attributCorrect ($bdd, $ID_DD_Cible, $nomAttribut, $clePrimaire, $Nom_Entite, $nomAssociation, $idTypeDonnee) {

        if (empty($nomAssociation)) {
            $query = $bdd->query("SELECT COUNT(Nom_Attribut) AS NB FROM ATTRIBUT
                                WHERE ID_DD=$ID_DD_Cible
                                  AND Nom_Attribut='$nomAttribut'
                                  AND Cle_Primaire=$clePrimaire
                                  AND Nom_Entite='$Nom_Entite'
                                  AND ID_Type_Donnee=$idTypeDonnee
                                  ");
            $result = $query->fetch();

            if ($result['NB'] > 0) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            // si l'attribut passé en paramètre est présent dans le DD passé en paramètre
            $query = $bdd->query("SELECT COUNT(Nom_Attribut) AS NB FROM ATTRIBUT
                                WHERE ID_DD=$ID_DD_Cible
                                  AND Nom_Attribut='$nomAttribut'
                                  AND Cle_Primaire=$clePrimaire
                                  AND Nom_Association='$nomAssociation'
                                  AND ID_Type_Donnee=$idTypeDonnee
                                  ");
            $result = $query->fetch();

            if ($result['NB'] > 0) {
                return true;
            }
            else {
                return false;
            }
        }
    }

    /**
     * @param PDO $bdd
     * @param $idMEA
     * @param $nomAssociation
     * @return bool
     */
    public static function associationPresenteDansMEA ($bdd, $idMEA, $nomAssociation) {
        $query = $bdd->query("SELECT COUNT(Nom_Association) AS NB FROM ASSOCIATION WHERE ID_MEA=$idMEA AND Nom_Association='$nomAssociation'");
        $result = $query->fetch();

        if ($result['NB'] > 0) {
            return true;
        }
        else {
            return false;
        }
    }


    /**
     * @param PDO $bdd
     * @param $ID_MEA_Copie
     * @param $ID_MEA_Correction
     * @param $nomAsso
     * @return bool
     */
    public static function associationEleveCorrecte ($bdd, $ID_MEA_Copie, $ID_MEA_Correction, $nomAsso) {
        // l'association de l'élève à le même nom que celui de la correction donc elles relient les mêmes entités, il suffit de vérifier les cardinalités

        $detailsAssoEleve = $bdd->query("SELECT * FROM PATTE WHERE ID_MEA=$ID_MEA_Copie AND Nom_Association='$nomAsso' ORDER BY Nom_Entite ASC");

        $tmp=0;
        while ($rowEleve = $detailsAssoEleve->fetch()) {
            $nomAsso = $rowEleve['Nom_Association'];
            $nomEntite = $rowEleve['Nom_Entite'];
            $cardinalite = $rowEleve['Libelle_Cardinalite'];
            $query = $bdd->query("SELECT COUNT(Nom_Association) AS NB
                                  FROM ASSOCIATION NATURAL JOIN PATTE
                                  WHERE ID_MEA=$ID_MEA_Correction
                                    AND Nom_Entite='$nomEntite'
                                    AND Libelle_Cardinalite = '$cardinalite'
                                    AND Nom_Association='$nomAsso'
                                    ");
            $result = $query->fetch();

            if ($result['NB'] == 0) {
                $tmp++;
            }
        }

        if ($tmp == 0) {
            return true;
        }
        else {
            return false;
        }

    }

    /**
     * @param PDO $bdd
     * @param $idMEA
     * @param $nomEntite
     * @return bool
     */
    public static function entitePresenteDansMEA ($bdd, $idMEA, $nomEntite) {
        $query = $bdd->query("SELECT COUNT(Nom_Entite) AS NB FROM ENTITE WHERE ID_MEA=$idMEA AND Nom_Entite='$nomEntite'");
        $result = $query->fetch();

        if ($result['NB'] > 0) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Fonction qui passe le booléen Correction_Prete de la table exercice à vrai (=1)
     * @param PDO $bdd
     * @param $idExercice
     */
    public static function validerCorrection ($bdd, $idExercice) {
        $query = "UPDATE EXERCICE SET Correction_Prete=1 WHERE ID_Exercice=$idExercice";
        $bdd->query($query);
    }


    /**
     * Fonction qui passe le booléen Correction_Prete de la table exercice à vrai (=1)
     * @param PDO $bdd
     * @param $idExercice
     */
    public static function modifierCorrection ($bdd, $idExercice) {
        $query = "UPDATE EXERCICE SET Correction_Prete=0 WHERE ID_Exercice=$idExercice";
        $bdd->query($query);
    }





























    /** ======================================================================================== */
    /** FONCTIONS RETOURNANT DES DONNEES PRECISES POUR UN EXERCICE DONNE */
    /** ======================================================================================== */



    /**
     * Fonction qui passe le booléen Exercice_Pret de la table exercice à vrai (=1)
     * @param PDO $bdd
     * @param $idExercice
     */
    public static function validerExercice ($bdd, $idExercice) {
        $query = "UPDATE EXERCICE SET Exercice_Pret=1 WHERE ID_Exercice=$idExercice";
        $bdd->query($query);
    }


    /**
     * Fonction qui passe le booléen Exercice_Pret de la table exercice à faux (=0)
     * @param PDO $bdd
     * @param $idExercice
     */
    public static function modifierExercice ($bdd, $idExercice) {
        $query = "UPDATE EXERCICE SET Exercice_Pret=0 WHERE ID_Exercice=$idExercice";
        $bdd->query($query);
    }



    /**
     * fonction qui retourne la liste des Rubriques de la correction d'un exercice donné
     * @param PDO $bdd
     * @param $idExercice
     * @return mixed
     */
    public static function getRubriquesCorrectionExercice ($bdd, $idExercice) {
        $query = "
        SELECT * FROM RUBRIQUE
        WHERE ID_DD = (SELECT ID_DD_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice)
        ORDER BY Nom_Rubrique ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
    }




    /**
     * Fonction qui retourne vrai si la correction de l'exercice est prête, et faux sinon
     * @param PDO $bdd
     * @param $idExercice
     * @return bool
     */
    public static function correctionPrete($bdd, $idExercice) {
        $query = $bdd->query("SELECT Correction_Prete FROM EXERCICE WHERE ID_Exercice=$idExercice");
        $pdoStatement = $query->fetch();
        $result = $pdoStatement['Correction_Prete'];
        if ($result == 0) {
            return false;
        }
        else {
            return true;
        }
    }


    /**
     * Fonction qui retourne vrai si le fake de l'exercice est prêt, et faux sinon
     * @param PDO $bdd
     * @param $idExercice
     * @return bool
     */
    public static function fakePret($bdd, $idExercice) {
        $query = $bdd->query("SELECT Fake_Pret FROM EXERCICE WHERE ID_Exercice=$idExercice");
        $pdoStatement = $query->fetch();
        $result = $pdoStatement['Fake_Pret'];
        if ($result == 0) {
            return false;
        }
        else {
            return true;
        }
    }


    /**
     * Fonction qui retourne vrai si l'exercice est en ligne, et faux sinon
     * @param PDO $bdd
     * @param $idExercice
     * @return bool
     */
    public static function exerciceEnLigne($bdd, $idExercice) {
        $query = $bdd->query("SELECT Exercice_Pret FROM EXERCICE WHERE ID_Exercice=$idExercice");
        $pdoStatement = $query->fetch();
        $result = $pdoStatement['Exercice_Pret'];
        if ($result == 0) {
            return false;
        }
        else {
            return true;
        }
    }


    /**
     * fonction qui donne toutes les infos d'un exercice donné (nom, type, id, etat de l'exercice)
     * @param PDO $bdd
     * @param $idExercice
     * @return mixed
     */
    public static function getInfosExercice ($bdd, $idExercice) {
        $liste = $bdd->query("SELECT * FROM EXERCICE WHERE ID_Exercice = $idExercice");
        $result = $liste->fetch();
        return $result;
    }


    /**
     * fonction qui retourne la liste des Paramètres de la correction d'un exercice
     * @param PDO $bdd
     * @param $idExercice
     * @return mixed
     */
    public static function getParametresCorrectionExercice ($bdd, $idExercice) {
        $query = "
                    SELECT * FROM PARAMETRE
                    WHERE ID_DD = (SELECT ID_DD_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice)
                    ORDER BY Nom_Parametre ASC
                    ";
        $liste = $bdd->query($query);
        return $liste;
    }

    /**
     * Fonction qui retourne vrai si le nom d'un paramètre est déjà prit, et faux sinon
     * @param PDO $bdd
     * @param $idExercice
     * @param $nomPossible
     * @return bool
     */
    public static function nomParametreNonDispo ($bdd, $idExercice, $nomPossible) {
        $query = "
                    SELECT COUNT(Nom_Parametre) AS NB FROM PARAMETRE
                    WHERE ID_DD = (SELECT ID_DD_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice) AND Nom_Parametre='$nomPossible'
                    ";
        $liste = $bdd->query($query);
        $result = $liste->fetch();
        if ($result['NB']>0) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Fonction qui insère un nouveau paramètre
     * @param PDO $bdd
     * @param $idDD
     * @param $nom
     * @param $valeur
     */
    public static function insertNouveauParametre ($bdd, $idDD, $nom, $valeur) {
        $query = "INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES ($idDD, '$nom')";
        $bdd->query($query);
        $query2 = "INSERT INTO PARAMETRE(ID_DD, Nom_Parametre, Valeur) VALUES ($idDD, '$nom', '$valeur')";
        $bdd->query($query2);
    }





    /**
     * Fonction qui insère un nouveau paramètre pour un copie élève
     * @param PDO $bdd
     * @param $idDD
     * @param $nom
     * @param $valeur
     */
    public static function insertNouveauParametreCopieEleve ($bdd, $idDD, $nom, $valeur) {
        $query2 = "INSERT INTO PARAMETRE(ID_DD, Nom_Parametre, Valeur) VALUES ($idDD, '$nom', '$valeur')";
        $bdd->query($query2);
    }

    /**
     * Fonction qui supprime un paramètre de la table Rubrique et de la table Paramètre et de la table calculée à partir de
     * @param PDO $bdd
     * @param $idDD
     * @param $nomParametre
     */
    public static function supprimerParametre ($bdd, $idDD, $nomParametre) {

        $query2 = "DELETE FROM PARAMETRE WHERE ID_DD=$idDD AND Nom_Parametre='$nomParametre'";
        $bdd->query($query2);
        $query3 = "DELETE FROM CALCULEE_A_PARTIR_DE WHERE ID_DD=$idDD AND Nom_Rubrique='$nomParametre'";
        $bdd->query($query3);
        $query = "DELETE FROM RUBRIQUE WHERE ID_DD=$idDD AND Nom_Rubrique='$nomParametre'";
        $bdd->query($query);
    }

    /**
     * fonction qui retourne la liste des Calculées de la correction d'un exercice
     * @param PDO $bdd
     * @param $idExercice
     * @return mixed
     */
    public static function getCalculeesCorrectionExercice ($bdd, $idExercice) {
        $query = "
                    SELECT * FROM CALCULEE NATURAL JOIN TYPE_DONNEE
                    WHERE ID_DD = (SELECT ID_DD_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice)
                    ORDER BY Nom_Calculee ASC
                    ";
        $liste = $bdd->query($query);
        return $liste;
    }

    /**
     * Fonction qui retourne vrai si le nom d'une calculée est déjà prit, et faux sinon
     * @param PDO $bdd
     * @param $idExercice
     * @param $nomPossible
     * @return bool
     */
    public static function nomCalculeeNonDispo ($bdd, $idExercice, $nomPossible) {
        $query = "
                    SELECT COUNT(Nom_Calculee) AS NB FROM CALCULEE
                    WHERE ID_DD = (SELECT ID_DD_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice) AND Nom_Calculee='$nomPossible'
                    ";
        $liste = $bdd->query($query);
        $result = $liste->fetch();
        if ($result['NB']>0) {
            return true;
        }
        else {
            return false;
        }
    }




    /**
     * Fonction qui insère une nouvelle calculée
     * @param PDO $bdd
     * @param $idDD
     * @param $nom
     * @param $idTypeDonnee
     */
    public static function insertNouvelleCalculee ($bdd, $idDD, $nom, $idTypeDonnee) {
        $query = "INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES ($idDD, '$nom')";
        $bdd->query($query);
        $query2 = "INSERT INTO CALCULEE(ID_DD, Nom_Calculee, ID_Type_Donnee)
                      VALUES ($idDD, '$nom', $idTypeDonnee)";
        $bdd->query($query2);
    }




    /**
     * Fonction qui insère une nouvelle ligne dans CALCULEE_A_PARTIR_DE
     * @param PDO $bdd
     * @param $idDD
     * @param $nomCalculee
     * @param $nomRubrique
     */
    public static function insertCalculeeAPartirDe ($bdd, $idDD, $nomCalculee, $nomRubrique) {
        $query = "INSERT INTO CALCULEE_A_PARTIR_DE(ID_DD, Nom_Calculee, Nom_Rubrique)
                      VALUES ($idDD, '$nomCalculee', '$nomRubrique')";
        $bdd->query($query);
    }


    /**
     * Fonction qui supprime une calculée
     * @param PDO $bdd
     * @param $idDD
     * @param $nomCalculee
     */
    public static function supprimerCalculee ($bdd, $idDD, $nomCalculee) {

        $query = "DELETE FROM CALCULEE_A_PARTIR_DE WHERE ID_DD=$idDD AND Nom_Calculee='$nomCalculee'";
        $bdd->query($query);
        $query1 = "DELETE FROM CALCULEE_A_PARTIR_DE WHERE ID_DD=$idDD AND Nom_Rubrique='$nomCalculee'";
        $bdd->query($query1);
        $query2 = "DELETE FROM CALCULEE WHERE ID_DD=$idDD AND Nom_Calculee='$nomCalculee'";
        $bdd->query($query2);
        $query3 = "DELETE FROM RUBRIQUE WHERE ID_DD=$idDD AND Nom_Rubrique='$nomCalculee'";
        $bdd->query($query3);
    }




    /**
     * fonction qui retourne la liste des rubriques à partir desquelles est calculée une "Calculée" de nom et de DD donné
     * @param PDO $bdd
     * @param $nomCalculee
     * @param $ID_DD
     * @return mixed
     */
    public static function getCalculeeAPartirDe ($bdd, $ID_DD, $nomCalculee) {
        $liste = $bdd->query("SELECT * FROM CALCULEE_A_PARTIR_DE WHERE ID_DD = $ID_DD AND Nom_Calculee = '$nomCalculee'");
        return $liste;
    }


    /**
     * fonction qui retourne la liste des Attributs de la correction d'un exercice
     * @param PDO $bdd
     * @param $idExercice
     * @return mixed
     */
    public static function getAttributsCorrectionExercice ($bdd, $idExercice) {
        $query = "
        SELECT * FROM ATTRIBUT NATURAL JOIN TYPE_DONNEE
        WHERE ID_DD = (SELECT ID_DD_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice)
        ORDER BY Nom_Entite ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
    }


    /**
     * fonction qui retourne la liste des Attributs appartenant à une entité
     * @param PDO $bdd
     * @param $idExercice
     * @param $nomEntite
     * @return mixed
     */
    public static function getAttributsEntite ($bdd, $idExercice, $nomEntite) {
        $query = "
        SELECT * FROM ATTRIBUT
        WHERE ID_DD = (SELECT ID_DD_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice) AND Nom_Entite='$nomEntite'
        ";
        $liste = $bdd->query($query);
        return $liste;
    }


    /**
     * @param PDO $bdd
     * @param $idDD
     * @param $nomEntite
     * @return mixed
     */
    public static function getAttributsEntiteFake ($bdd, $idDD, $nomEntite) {
        $query = "
        SELECT * FROM ATTRIBUT
        WHERE ID_DD = $idDD AND Nom_Entite='$nomEntite'
        ";
        $liste = $bdd->query($query);
        return $liste;
    }



    /**
     * Fonction qui retourne vrai si le nom d'un attribut est déjà prit, et faux sinon
     * @param PDO $bdd
     * @param $idExercice
     * @param $nomPossible
     * @return bool
     */
    public static function nomAttributNonDispo ($bdd, $idExercice, $nomPossible) {
        $query = "
                    SELECT COUNT(Nom_Attribut) AS NB FROM ATTRIBUT
                    WHERE ID_DD = (SELECT ID_DD_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice) AND Nom_Attribut='$nomPossible'
                    ";
        $liste = $bdd->query($query);
        $result = $liste->fetch();
        if ($result['NB']>0) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Fonction qui insère un nouvel attribut
     * @param PDO $bdd
     * @param $idDD
     * @param $nom
     * @param $cle
     * @param $idMEA
     * @param $entite
     * @param $association
     * @param $idTypeDonnee
     */
    public static function insertNouvelAttribut ($bdd, $idDD, $nom, $cle, $idMEA, $entite, $association, $idTypeDonnee) {
        $query = "INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES ($idDD, '$nom')";
        $bdd->query($query);
        if ($entite == -1) {
            $query2 = "INSERT INTO ATTRIBUT(ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Association, ID_Type_Donnee)
                      VALUES ($idDD, '$nom', $cle, $idMEA, '$association', $idTypeDonnee)";
            $bdd->query($query2);
        }
        else if ($association == -1) {
            $query2 = "INSERT INTO ATTRIBUT(ID_DD, Nom_Attribut, Cle_Primaire, ID_MEA, Nom_Entite, ID_Type_Donnee)
                      VALUES ($idDD, '$nom', $cle, $idMEA, '$entite', $idTypeDonnee)";
            $bdd->query($query2);
        }

    }



    /**
     * Fonction qui supprime un attribut de la table Rubrique et de la table Attribut et de la table calculée à partir de
     * @param PDO $bdd
     * @param $idDD
     * @param $nomAttribut
     */
    public static function supprimerAttribut ($bdd, $idDD, $nomAttribut) {

        $query2 = "DELETE FROM ATTRIBUT WHERE ID_DD=$idDD AND Nom_Attribut='$nomAttribut'";
        $bdd->query($query2);
        $query3 = "DELETE FROM CALCULEE_A_PARTIR_DE WHERE ID_DD=$idDD AND Nom_Rubrique='$nomAttribut'";
        $bdd->query($query3);
        $query = "DELETE FROM RUBRIQUE WHERE ID_DD=$idDD AND Nom_Rubrique='$nomAttribut'";
        $bdd->query($query);
    }


    /**
     * fonction qui retourne la liste des Entités de la correction d'un exercice
     * @param PDO $bdd
     * @param $idExercice
     * @return mixed
     */
    public static function getEntitesCorrectionExercice ($bdd, $idExercice) {
        $query = "
        SELECT * FROM ENTITE
        WHERE ID_MEA = (SELECT ID_MEA_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice)
        ORDER BY Nom_Entite ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
    }


    /**
     * Fonction qui retourne vrai si le nom d'une entité est déjà prit, et faux sinon
     * @param PDO $bdd
     * @param $idExercice
     * @param $nomPossible
     * @return bool
     */
    public static function nomEntiteNonDispo ($bdd, $idExercice, $nomPossible) {
        $query = "
                    SELECT COUNT(Nom_Entite) AS NB FROM ENTITE
                    WHERE ID_MEA = (SELECT ID_MEA_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice) AND Nom_Entite='$nomPossible'
                    ";
        $liste = $bdd->query($query);
        $result = $liste->fetch();
        if ($result['NB']>0) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * Fonction qui retourne vrai si le nom d'une entité est déjà prit, et faux sinon
     * @param PDO $bdd
     * @param $idExercice
     * @param $nomPossible
     * @return bool
     */
    public static function nomEntiteFakeNonDispo ($bdd, $idExercice, $nomPossible) {
        $query = "
                    SELECT COUNT(Nom_Entite) AS NB FROM ENTITE
                    WHERE ID_MEA = (SELECT ID_MEA_Fake FROM EXERCICE WHERE ID_Exercice=$idExercice) AND Nom_Entite='$nomPossible'
                    ";
        $liste = $bdd->query($query);
        $result = $liste->fetch();
        if ($result['NB']>0) {
            return true;
        }
        else {
            return false;
        }
    }


    /**
     * Fonction qui retourne vrai si le nom de la rubrique n'est pas dispo dans la correction de l'exercice
     * @param PDO $bdd
     * @param $idExercice
     * @param $nomPossible
     * @return bool
     */
    public static function nomRubriqueNonDispoCorrection ($bdd, $idExercice, $nomPossible) {
        $query = "
                    SELECT COUNT(Nom_Rubrique) AS NB FROM RUBRIQUE
                    WHERE ID_DD = (SELECT ID_DD_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice) AND Nom_Rubrique='$nomPossible'
                    ";
        $liste = $bdd->query($query);
        $result = $liste->fetch();
        if ($result['NB']>0) {
            return true;
        }
        else {
            return false;
        }
    }




    /**
     * Fonction qui retourne vrai si le nom de la rubrique n'est pas dispo dans le fake de l'exercice
     * @param PDO $bdd
     * @param $idExercice
     * @param $nomPossible
     * @return bool
     */
    public static function nomRubriqueNonDispoFake ($bdd, $idExercice, $nomPossible) {
        $query = "
                    SELECT COUNT(Nom_Rubrique) AS NB FROM RUBRIQUE
                    WHERE ID_DD = (SELECT ID_DD_Fake FROM EXERCICE WHERE ID_Exercice=$idExercice) AND Nom_Rubrique='$nomPossible'
                    ";
        $liste = $bdd->query($query);
        $result = $liste->fetch();
        if ($result['NB']>0) {
            return true;
        }
        else {
            return false;
        }
    }






    /**
     * Fonction qui insère une nouvelle entité
     * @param PDO $bdd
     * @param $idMEA
     * @param $nom
     */
    public static function insertNouvelleEntite ($bdd, $idMEA, $nom) {
        $query = "INSERT INTO ENTITE(ID_MEA, Nom_Entite) VALUES ($idMEA, '$nom')";
        $bdd->query($query);
    }



    /**
     * Fonction qui insère une nouvelle rubrique
     * @param PDO $bdd
     * @param $idDD
     * @param $nom
     */
    public static function insertNouvelleRubrique ($bdd, $idDD, $nom) {
        $query = "INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES ($idDD, '$nom')";
        $bdd->query($query);
    }


    /**
     * Fonction qui supprime nouvelle rubrique du fake
     * @param PDO $bdd
     * @param $idDD
     * @param $nom
     */
    public static function supprimerRubriqueFake ($bdd, $idDD, $nom) {
        $query = "DELETE FROM RUBRIQUE WHERE ID_DD=$idDD AND Nom_Rubrique='$nom'";
        $bdd->query($query);
    }




    /**
     * Fonction qui supprime une rubrique d'une copie élève et toute spécialisation qu'elle puisse avoir
     * @param PDO $bdd
     * @param $idDD
     * @param $nom
     */
    public static function supprimerRubriqueCopie ($bdd, $idDD, $nom) {
        $query="DELETE FROM CALCULEE_A_PARTIR_DE WHERE ID_DD=$idDD AND Nom_Rubrique='$nom'";
        $bdd->query($query);
        $query = "DELETE FROM CALCULEE WHERE ID_DD=$idDD AND Nom_Calculee='$nom'";
        $bdd->query($query);
        $query = "DELETE FROM ATTRIBUT WHERE ID_DD=$idDD AND Nom_Attribut='$nom'";
        $bdd->query($query);
        $query = "DELETE FROM PARAMETRE WHERE ID_DD=$idDD AND Nom_Parametre='$nom'";
        $bdd->query($query);
        $query = "DELETE FROM RUBRIQUE WHERE ID_DD=$idDD AND Nom_Rubrique='$nom'";
        $bdd->query($query);
    }



    /**
     * Fonction qui supprime une entité
     * @param PDO $bdd
     * @param $idMEA
     * @param $nomEntite
     * @param $idExercice
     */
    public static function supprimerEntite ($bdd, $idExercice, $idMEA, $nomEntite) {



        $listeAttributs = QueryFunctions::getAttributsEntite($bdd, $idExercice, $nomEntite);
        while ($row = $listeAttributs->fetch()) {
            QueryFunctions::supprimerAttribut($bdd, $row['ID_DD'], $row['Nom_Attribut']);
        }
        $listeAssociations = QueryFunctions::getAssociationsCorrectionExerciceUneEntite($bdd, $idExercice, $nomEntite);
        while ($row = $listeAssociations->fetch()) {
            QueryFunctions::supprimerAssociation($bdd, $idExercice, $row['ID_MEA'], $row['Nom_Association']);
        }

        $query = "DELETE FROM ENTITE WHERE ID_MEA=$idMEA AND Nom_Entite='$nomEntite'";
        $bdd->query($query);

    }


    /**
     * @param PDO $bdd
     * @param $idMEA
     * @param $idDD
     * @param $nomEntite
     */
    public static function supprimerEntiteFake ($bdd, $idMEA, $idDD, $nomEntite) {

        $listeAttributs = QueryFunctions::getAttributsEntiteFake($bdd, $idDD, $nomEntite);
        while ($row = $listeAttributs->fetch()) {
            QueryFunctions::supprimerAttribut($bdd, $row['ID_DD'], $row['Nom_Attribut']);
        }
        $listeAssociations = QueryFunctions::getAssociationsUneEntite($bdd, $idMEA, $nomEntite);
        while ($row = $listeAssociations->fetch()) {
            QueryFunctions::supprimerAssociationFake($bdd, $idDD, $row['ID_MEA'], $row['Nom_Association']);
        }

        $query = "DELETE FROM ENTITE WHERE ID_MEA=$idMEA AND Nom_Entite='$nomEntite'";
        $bdd->query($query);
    }



    /**
     * fonction qui retourne la liste des Associations de la correction d'un exercice
     * @param PDO $bdd
     * @param $idExercice
     * @return mixed
     */
    public static function getAssociationsCorrectionExercice ($bdd, $idExercice) {
        $query = "
        SELECT * FROM ASSOCIATION
        WHERE ID_MEA = (SELECT ID_MEA_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice)
        ORDER BY Nom_Association ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
    }


    /**
     * fonction qui retourne la liste des Associations de la correction d'un exercice pour une entité donnée
     * @param PDO $bdd
     * @param $idExercice
     * @param $nomEntite
     * @return mixed
     */
    public static function getAssociationsCorrectionExerciceUneEntite ($bdd, $idExercice, $nomEntite) {
        $query = "
        SELECT DISTINCT Nom_Association, ID_MEA FROM ASSOCIATION NATURAL JOIN PATTE
        WHERE ID_MEA = (SELECT ID_MEA_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice) AND Nom_Entite='$nomEntite'
        ORDER BY Nom_Association ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
    }





    public static function getAssociationsUneEntite ($bdd, $idMEA, $nomEntite) {
        $query = "
        SELECT DISTINCT Nom_Association, ID_MEA FROM ASSOCIATION NATURAL JOIN PATTE
        WHERE ID_MEA = $idMEA AND Nom_Entite='$nomEntite'
        ORDER BY Nom_Association ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
    }


    /**
     * Fonction qui retourne vrai si le nom d'une association est déjà prit, et faux sinon
     * @param PDO $bdd
     * @param $idExercice
     * @param $nomPossible
     * @return bool
     */
    public static function nomAssociationNonDispo ($bdd, $idExercice, $nomPossible) {
        $query = "
                    SELECT COUNT(Nom_Association) AS NB FROM ASSOCIATION
                    WHERE ID_MEA = (SELECT ID_MEA_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice) AND Nom_Association='$nomPossible'
                    ";
        $liste = $bdd->query($query);
        $result = $liste->fetch();
        if ($result['NB']>0) {
            return true;
        }
        else {
            return false;
        }
    }



    /**
     * Fonction qui retourne vrai si le nom d'une association est déjà prit, et faux sinon
     * @param PDO $bdd
     * @param $idCopieEleve
     * @param $nomPossible
     * @return bool
     */
    public static function nomAssociationNonDispoCopieEleve ($bdd, $idCopieEleve, $nomPossible) {
        $query = "
                    SELECT COUNT(Nom_Association) AS NB FROM ASSOCIATION
                    WHERE ID_MEA = (SELECT ID_MEA FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopieEleve) AND Nom_Association='$nomPossible'
                    ";
        $liste = $bdd->query($query);
        $result = $liste->fetch();
        if ($result['NB']>0) {
            return true;
        }
        else {
            return false;
        }
    }



    /**
     * Fonction qui insère une nouvelle association
     * @param PDO $bdd
     * @param $idMEA
     * @param $nom
     */
    public static function insertNouvelleAssociation ($bdd, $idMEA, $nom) {
        $query = "INSERT INTO ASSOCIATION(ID_MEA, Nom_Association) VALUES ($idMEA, '$nom')";
        $bdd->query($query);
    }



    /**
     * Fonction qui insère une nouvelle patte
     * @param PDO $bdd
     * @param $idMEA
     * @param $nomAssociation
     * @param $nomEntite
     * @param $card
     */
    public static function insertPatte ($bdd, $idMEA, $nomAssociation, $nomEntite, $card) {
        $query = "INSERT INTO PATTE(ID_MEA, Nom_Entite, Nom_Association, Libelle_Cardinalite)
                  VALUES ($idMEA, '$nomEntite', '$nomAssociation', '$card')";
        $bdd->query($query);
    }




    /**
     * Fonction qui SUPPRIME une  association
     * @param PDO $bdd
     * @param $idExercice
     * @param $idMEA
     * @param $nomAssociation
     */
    public static function supprimerAssociation ($bdd, $idExercice, $idMEA, $nomAssociation) {
        $listeAttributs = QueryFunctions::getAttributsAssociation($bdd, $idExercice, $nomAssociation);
        while ($row = $listeAttributs->fetch()) {
            QueryFunctions::supprimerAttribut($bdd, $row['ID_DD'], $row['Nom_Attribut']);
        }
        $query = "DELETE FROM PATTE
                  WHERE ID_MEA=$idMEA AND Nom_Association='$nomAssociation'";
        $bdd->query($query);
        $query2 = "DELETE FROM ASSOCIATION
                  WHERE ID_MEA=$idMEA AND Nom_Association='$nomAssociation'";
        $bdd->query($query2);
    }


    /**
     * @param PDO $bdd
     * @param $idDD
     * @param $idMEA
     * @param $nomAssociation
     */
    public static function supprimerAssociationFake ($bdd, $idDD, $idMEA, $nomAssociation) {
        $listeAttributs = QueryFunctions::getAttributsAssociationFake($bdd, $idDD, $nomAssociation);
        while ($row = $listeAttributs->fetch()) {
            QueryFunctions::supprimerAttribut($bdd, $row['ID_DD'], $row['Nom_Attribut']);
        }
        $query = "DELETE FROM PATTE
                  WHERE ID_MEA=$idMEA AND Nom_Association='$nomAssociation'";
        $bdd->query($query);
        $query2 = "DELETE FROM ASSOCIATION
                  WHERE ID_MEA=$idMEA AND Nom_Association='$nomAssociation'";
        $bdd->query($query2);
    }
    /**
     * Fonction qui SUPPRIME une  association d'une copie d'élève
     * @param PDO $bdd
     * @param $idCopieEleve
     * @param $idMEA
     * @param $nomAssociation
     */
    public static function supprimerAssociationCopieEleve ($bdd, $idCopieEleve, $idMEA, $nomAssociation) {
        $listeAttributs = QueryFunctions::getAttributsCopieEleve($bdd, $idCopieEleve);
        while ($row = $listeAttributs->fetch()) {
            $idDD = $row['ID_DD'];
            $nomAttribut = $row['Nom_Attribut'];
            $query = "DELETE FROM ATTRIBUT WHERE ID_DD=$idDD AND Nom_Attribut='$nomAttribut'";
            $bdd->query($query);
        }
        $bdd->query("DELETE FROM PATTE WHERE ID_MEA=$idMEA AND Nom_Association='$nomAssociation'");
        $bdd->query("DELETE FROM ASSOCIATION WHERE ID_MEA=$idMEA AND Nom_Association='$nomAssociation'");
    }


    /**
     * fonction qui retourne la liste des Attributs appartenant à une association
     * @param PDO $bdd
     * @param $idExercice
     * @param $nomAssociation
     * @return mixed
     */
    public static function getAttributsAssociation($bdd, $idExercice, $nomAssociation) {
        $query = "
        SELECT * FROM ATTRIBUT
        WHERE ID_DD = (SELECT ID_DD_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice) AND Nom_Association='$nomAssociation'
        ";
        $liste = $bdd->query($query);
        return $liste;
    }



    public static function getAttributsAssociationFake($bdd, $idDD, $nomAssociation) {
        $query = "
        SELECT * FROM ATTRIBUT
        WHERE ID_DD = $idDD AND Nom_Association='$nomAssociation'
        ";
        $liste = $bdd->query($query);
        return $liste;
    }

    /**
     * fonction qui retourne la liste des entités que relie une association donnée avec les cardinalités correspondantes
     * @param PDO $bdd
     * @param $ID_MEA
     * @param $NomAsso
     * @return mixed
     */
    public static function getEntitesRelieesParUneAsso ($bdd, $ID_MEA, $NomAsso) {
        $query = "
        SELECT Nom_Entite, Libelle_Cardinalite
        FROM ASSOCIATION NATURAL JOIN PATTE
        WHERE ID_MEA = $ID_MEA AND Nom_Association = '$NomAsso'
        ORDER BY Nom_Entite ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
    }

    /**
     * Fonction qui retourne toutes les copies d'élève pour un id_exercice donné
     * @param PDO $bdd
     * @param $idExercice
     * @return mixed
     */
    public static function getAllCopiesUnExercice ($bdd, $idExercice) {
        $query = "
        SELECT ID_Copie_Eleve, ID_DD, ID_MEA FROM COPIE_ELEVE
        WHERE ID_Exercice = $idExercice";
        $liste = $bdd->query($query);
        return $liste;
    }

    /**
     * Fonction qui supprime définitivement un exercice et tout ce qui lui est relié
     * @param PDO $bdd
     * @param $idExercice
     */
    public static function supprimerExercice($bdd, $idExercice){
        $listeCopies = QueryFunctions::getAllCopiesUnExercice($bdd, $idExercice);
        while ($row_listeCopies = $listeCopies->fetch()) {
            $idCopie = $row_listeCopies['ID_Copie_Eleve'];
            $idMEA = $row_listeCopies['ID_MEA'];
            $idDD = $row_listeCopies['ID_DD'];

            $bdd->query("DELETE FROM ATTRIBUT WHERE ID_DD=$idDD");
            $bdd->query("DELETE FROM PATTE WHERE ID_MEA=$idMEA");
            $bdd->query("DELETE FROM ASSOCIATION WHERE ID_MEA=$idMEA");
            $bdd->query("DELETE FROM ENTITE WHERE ID_MEA=$idMEA");
            $bdd->query("DELETE FROM PARAMETRE WHERE ID_DD=$idDD");
            $bdd->query("DELETE FROM CALCULEE_A_PARTIR_DE WHERE ID_DD=$idDD");
            $bdd->query("DELETE FROM CALCULEE WHERE ID_DD=$idDD");
            $bdd->query("DELETE FROM RUBRIQUE WHERE ID_DD=$idDD");
            $bdd->query("DELETE FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopie");
            $bdd->query("DELETE FROM DD WHERE ID_DD=$idDD");
            $bdd->query("DELETE FROM MEA WHERE ID_MEA=$idMEA");
        }

        $bdd->query("DELETE FROM EDITION_EXERCICE WHERE ID_Exercice=$idExercice");

        $infoExercice = QueryFunctions::getInfosExercice($bdd, $idExercice);
        unlink(__DIR__."/../uploads/".basename($infoExercice['Enonce_Exercice']));

        $bdd->query("DELETE FROM EXERCICE WHERE ID_EXERCICE=$idExercice");


        $idMeaCorrection = $infoExercice['ID_MEA_Correction'];
        $bdd->query("DELETE FROM PATTE WHERE ID_MEA=$idMeaCorrection");
        $bdd->query("DELETE FROM ASSOCIATION WHERE ID_MEA=$idMeaCorrection");
        $bdd->query("DELETE FROM MEA WHERE ID_MEA=$idMeaCorrection");

        $idMeaFake = $infoExercice['ID_MEA_Fake'];
        $bdd->query("DELETE FROM PATTE WHERE ID_MEA=$idMeaCorrection");
        $bdd->query("DELETE FROM ASSOCIATION WHERE ID_MEA=$idMeaCorrection");
        $bdd->query("DELETE FROM MEA WHERE ID_MEA=$idMeaFake");

        $idDDCorrection = $infoExercice['ID_DD_Correction'];
        $bdd->query("DELETE FROM ATTRIBUT WHERE ID_DD=$idDDCorrection");
        $bdd->query("DELETE FROM PARAMETRE WHERE ID_DD=$idDDCorrection");
        $bdd->query("DELETE FROM CALCULEE_A_PARTIR_DE WHERE ID_DD=$idDDCorrection");
        $bdd->query("DELETE FROM CALCULEE WHERE ID_DD=$idDDCorrection");
        $bdd->query("DELETE FROM RUBRIQUE WHERE ID_DD=$idDDCorrection");
        $bdd->query("DELETE FROM DD WHERE ID_DD=$idDDCorrection");

        $idDDFake = $infoExercice['ID_DD_Fake'];
        $bdd->query("DELETE FROM ATTRIBUT WHERE ID_DD=$idDDFake");
        $bdd->query("DELETE FROM PARAMETRE WHERE ID_DD=$idDDFake");
        $bdd->query("DELETE FROM CALCULEE_A_PARTIR_DE WHERE ID_DD=$idDDFake");
        $bdd->query("DELETE FROM CALCULEE WHERE ID_DD=$idDDFake");
        $bdd->query("DELETE FROM RUBRIQUE WHERE ID_DD=$idDDFake");
        $bdd->query("DELETE FROM DD WHERE ID_DD=$idDDFake");
    }



    /** ======================================================================================== */
    /** FONCTIONS POUR AVOIR LES DONNEES DE LA PARTIE FAKE D'UN EXERCICE */
    /** ======================================================================================== */

    /**
     * Fonction qui passe le booléen Fake_Pret de la table exercice à vrai (=1)
     * @param PDO $bdd
     * @param $idExercice
     */
    public static function validerFake ($bdd, $idExercice) {
        $query = "UPDATE EXERCICE SET Fake_Pret=1 WHERE ID_Exercice=$idExercice";
        $bdd->query($query);
    }


    /**
     * Fonction qui passe le booléen Fake_Pret de la table exercice à vrai (=1)
     * @param PDO $bdd
     * @param $idExercice
     */
    public static function modifierFake ($bdd, $idExercice) {
        $query = "UPDATE EXERCICE SET Fake_Pret=0 WHERE ID_Exercice=$idExercice";
        $bdd->query($query);
    }

    /**
     * Fonction qui copie colle les données de la correction dans le fake pour un exercice donné
     * @param PDO $bdd
     * @param $idExercice
     */
    public static function initFAKE ($bdd, $idExercice) {
        $infoExercice = QueryFunctions::getInfosExercice($bdd, $idExercice);
        $idDDFake = $infoExercice['ID_DD_Fake'];
        $idMEAFake = $infoExercice['ID_MEA_Fake'];

        $listeRubriques = QueryFunctions::getRubriquesCorrectionExercice($bdd, $idExercice);
        while ($row = $listeRubriques->fetch()) {
            QueryFunctions::insertRubriqueFake($bdd, $idDDFake, $row['Nom_Rubrique']);
        }

        $listeEntites = QueryFunctions::getEntitesCorrectionExercice($bdd, $idExercice);
        while ($row = $listeEntites->fetch()) {
            QueryFunctions::insertEntiteFake($bdd, $idMEAFake, $row['Nom_Entite']);
        }
    }


    /**
     * Fonction qui copie colle les données de la correction dans le fake pour un exercice donné
     * @param PDO $bdd
     * @param $idExercice
     */
    public static function supprimerFake ($bdd, $idExercice) {
        $infoExercice = QueryFunctions::getInfosExercice($bdd, $idExercice);
        $idDDFake = $infoExercice['ID_DD_Fake'];
        $idMEAFake = $infoExercice['ID_MEA_Fake'];

        $deleteRubrique = "DELETE FROM RUBRIQUE WHERE ID_DD=$idDDFake";
        $bdd->query($deleteRubrique);
        $deleteEntite = "DELETE FROM ENTITE WHERE ID_MEA=$idMEAFake";
        $bdd->query($deleteEntite);
    }

    /**
     * Fonction qui insère une nouvelle rubrique dans la partie fake d'un exercice
     * @param PDO $bdd
     * @param $idDDFake
     * @param $nomRubrique
     */
    public static function insertRubriqueFake ($bdd, $idDDFake, $nomRubrique) {
        $query = "INSERT INTO RUBRIQUE(ID_DD, Nom_Rubrique) VALUES ($idDDFake, '$nomRubrique')";
        $bdd->query($query);
    }


    /**
     * Fonction qui insère une nouvelle enité dans la partie fake d'un exercice
     * @param PDO $bdd
     * @param $idMEAFake
     * @param $nomEntite
     */
    public static function insertEntiteFake ($bdd, $idMEAFake, $nomEntite) {
        $query = "INSERT INTO ENTITE(ID_MEA, Nom_Entite) VALUES ($idMEAFake, '$nomEntite')";
        $bdd->query($query);
    }


    /**
     * fonction qui retourne la liste des Rubriques d'un Fake d'un exercice donné
     * @param PDO $bdd
     * @param $idExercice
     * @return mixed
     */
    public static function getRubriquesFakeExercice ($bdd, $idExercice) {
        $query = "
        SELECT * FROM RUBRIQUE
        WHERE ID_DD = (SELECT ID_DD_Fake FROM EXERCICE WHERE ID_Exercice=$idExercice)
        ORDER BY Nom_Rubrique ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
    }


    /**
     * fonction qui retourne la liste des Rubriques d'un Fake d'un exercice donné et qui ne sont pas dans la liste des rubriques de la copie d'élève
     * @param PDO $bdd
     * @param $idExercice
     * @param $idCopie
     * @return mixed
     */
    public static function getRubriquesFakeNotInCopieEleve ($bdd, $idExercice, $idCopie) {
        $query = "
        SELECT * FROM RUBRIQUE
        WHERE ID_DD = (SELECT ID_DD_Fake FROM EXERCICE WHERE ID_Exercice=$idExercice) AND Nom_Rubrique NOT IN (
            SELECT Nom_Rubrique FROM RUBRIQUE
            WHERE ID_DD = (SELECT ID_DD FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopie)
            )
        ORDER BY Nom_Rubrique ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
    }



    /**
     * fonction qui retourne la liste des Entités d'un Fake d'un exercice donné et qui ne sont pas dans la liste des entités de la copie d'élève
     * @param PDO $bdd
     * @param $idExercice
     * @param $idCopie
     * @return mixed
     */
    public static function getEntitesFakeNotInCopieEleve ($bdd, $idExercice, $idCopie) {
        $query = "
        SELECT * FROM ENTITE
        WHERE ID_MEA = (SELECT ID_MEA_Fake FROM EXERCICE WHERE ID_Exercice=$idExercice) AND Nom_Entite NOT IN (
            SELECT Nom_Entite FROM ENTITE
            WHERE ID_MEA = (SELECT ID_MEA FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopie)
            )
        ORDER BY Nom_Entite ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
    }

    /**
     * fonction qui retourne la liste des Entités d'un Fake d'un exercice donné
     * @param PDO $bdd
     * @param $idExercice
     * @return mixed
     */
    public static function getEntitesFakeExercice ($bdd, $idExercice) {
        $query = "
        SELECT * FROM ENTITE
        WHERE ID_MEA = (SELECT ID_MEA_Fake FROM EXERCICE WHERE ID_Exercice=$idExercice)
        ORDER BY Nom_Entite ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
    }

    /**
     * fonction qui retourne la liste des Entités d'un Fake d'un exercice donné
     * @param PDO $bdd
     * @param $idExercice
     * @return mixed
     */
    public static function getEntitesSeulementDuFake ($bdd, $idExercice) {
        $query = "
        SELECT * FROM ENTITE
        WHERE ID_MEA = (SELECT ID_MEA_Fake FROM EXERCICE WHERE ID_Exercice=$idExercice)
        AND Nom_Entite NOT IN (
            SELECT Nom_Entite
            FROM ENTITE
            WHERE ID_MEA = (SELECT ID_MEA_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice)
            )
        ORDER BY Nom_Entite ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
    }






    /**
     * fonction qui retourne la liste des Rubriques d'un Fake d'un exercice donné
     * @param PDO $bdd
     * @param $idExercice
     * @return mixed
     */
    public static function getRubriquesSeulementDuFake ($bdd, $idExercice) {
        $query = "
        SELECT * FROM RUBRIQUE
        WHERE ID_DD = (SELECT ID_DD_Fake FROM EXERCICE WHERE ID_Exercice=$idExercice)
        AND Nom_Rubrique NOT IN (
            SELECT Nom_Rubrique
            FROM RUBRIQUE
            WHERE ID_DD = (SELECT ID_DD_Correction FROM EXERCICE WHERE ID_Exercice=$idExercice)
            )
        ORDER BY Nom_Rubrique ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
    }






    /** ============================================================================================================ */
    /** ============================================================================================================ */
    /** ============================         FONCTIONS SUR LES COPIES ELEVES           ============================= */
    /** ============================================================================================================ */
    /** ============================================================================================================ */


    /**
     * @param PDO $bdd
     * @param $idCopieEleve
     */
    public static function updateDateModifCopieSysdate ($bdd, $idCopieEleve) {
        $bdd->query("UPDATE COPIE_ELEVE SET Date_Derniere_Modif_Copie_Eleve=SYSDATE() WHERE ID_Copie_Eleve=$idCopieEleve");
    }


    /**
     * @param PDO $bdd
     * @param $idCopieEleve
     * @param $note
     */
    public static function updateDateEnvoiCopieSysdate ($bdd, $idCopieEleve, $note) {
        $bdd->query("UPDATE COPIE_ELEVE
                      SET Date_Envoi_Copie_Eleve=SYSDATE(), Note_Copie_Eleve=$note
                      WHERE ID_Copie_Eleve=$idCopieEleve");
    }



    /**
     * Fonction de création d'une nouvelle copie élève
     * @param PDO $bdd
     * @param $nomCopie
     * @param $idExercice
     * @param $idUser
     * @param $idMEA
     * @param $idDD
     * @param $commentaire
     */
    public static function insertNewCopie ($bdd, $nomCopie, $idExercice, $idUser, $idMEA, $idDD, $commentaire) {
        $query = "INSERT INTO COPIE_ELEVE(Nom_Copie_Eleve, ID_Exercice, ID_Utilisateur, ID_MEA, ID_DD, Date_Derniere_Modif_Copie_Eleve, Commentaire_Copie_Eleve)
                    VALUES ('$nomCopie', $idExercice, $idUser, $idMEA, $idDD, SYSDATE(), '$commentaire')";
        $bdd->query($query);
    }

    /**
     * Fonction qui retourne l'IDCopieEleve
     * @param PDO $bdd
     * @param $idUser
     * @param $idExercice
     * @param $idMEA
     * @param $idDD
     * @param $nomCopie
     * @return string
     */
    public static function selectIDCopie ($bdd, $idUser, $idExercice, $idMEA, $idDD, $nomCopie) {
        $query = "SELECT ID_Copie_Eleve FROM COPIE_ELEVE
                    WHERE ID_Utilisateur=$idUser AND ID_Exercice=$idExercice AND ID_DD=$idDD AND ID_MEA=$idMEA AND Nom_Copie_Eleve='$nomCopie'";
        $pdoStatement = $bdd->query($query);
        $result = $pdoStatement->fetch();
        return (String)$result['ID_Copie_Eleve'];
    }


    /**
     * retourne vrai si le nom de l'exercice est dispo
     * @param PDO $bdd
     * @param $nom
     * @param $idUser
     * @param $idExercice
     * @return bool
     */
    public static function nomCopieNonDispo($bdd, $nom, $idUser, $idExercice){
        $query="
			SELECT COUNT(Nom_Copie_Eleve) AS NB
			FROM COPIE_ELEVE
			WHERE ID_Exercice=$idExercice AND ID_Utilisateur=$idUser AND Nom_Copie_Eleve='$nom'";
        $req = $bdd->query($query);
        $result = $req->fetch();
        if ($result['NB'] > 0) {
            return true;
        }
        else {
            return false;
        }

    }





    /** Fonction qui retourne la liste des copies pour un élève donné
     * @param PDO $bdd
     * @param $idEleve
     * @return mixed
     */
    public static function getAllCopiesUnEleve ($bdd, $idEleve) {
        $query = "
        SELECT ID_Copie_Eleve, ID_DD, ID_MEA FROM COPIE_ELEVE
        WHERE ID_Utilisateur = $idEleve";
        $liste = $bdd->query($query);
        return $liste;
    }



    /**
     * fonction qui retourne la liste des Paramètres d'une copie
     * @param PDO $bdd
     * @param $idCopie
     * @return mixed
     */
    public static function getParametresCopieEleve ($bdd, $idCopie) {
        $query = "
        SELECT * FROM PARAMETRE
        WHERE ID_DD = (SELECT ID_DD FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopie)
        ORDER BY Nom_Parametre ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
    }


    /**
     * fonction qui retourne la liste des Calculées d'une copie
     * @param PDO $bdd
     * @param $idCopie
     * @return mixed
     */
    public static function getCalculeesCopieEleve ($bdd, $idCopie) {
        $query = "
        SELECT * FROM CALCULEE NATURAL JOIN TYPE_DONNEE
        WHERE ID_DD = (SELECT ID_DD FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopie)
        ORDER BY Nom_Calculee ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
        // utiliser la fonction QueryFunctions::getCalculeeAPartirDe qui donne la liste des attributs à partir desquels est calculée une Calculée de ID_DD et de Nom_Calculee donnés
    }




    /**
     * fonction qui retourne la liste des Attributs d'une copie
     * @param PDO $bdd
     * @param $idCopie
     * @return mixed
     */
    public static function getAttributsCopieEleve ($bdd, $idCopie) {
        $query = "
        SELECT * FROM ATTRIBUT NATURAL JOIN TYPE_DONNEE
        WHERE ID_DD = (SELECT ID_DD FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopie)
        ORDER BY Nom_Entite ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
    }




    /**
     * fonction qui retourne la liste des Entités d'une copie
     * @param PDO $bdd
     * @param $idCopie
     * @return mixed
     */
    public static function getEntitesCopieEleve ($bdd, $idCopie) {
        $query = "
        SELECT * FROM ENTITE
        WHERE ID_MEA = (SELECT ID_MEA FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopie)
        ORDER BY Nom_Entite ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
    }


    /**
     * fonction qui retourne la liste des Rubriques d'une copie élève
     * @param PDO $bdd
     * @param $idCopie
     * @return mixed
     */
    public static function getRubriquesCopieEleve ($bdd, $idCopie) {
        $query = "
        SELECT * FROM RUBRIQUE
        WHERE ID_DD = (SELECT ID_DD FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopie)
        ORDER BY Nom_Rubrique ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
    }



    /**
     * fonction qui retourne la liste des Rubriques d'une copie élève
     * @param PDO $bdd
     * @param $idCopie
     * @return mixed
     */
    public static function getRubriquesNonSpecialiseeCopieEleve ($bdd, $idCopie) {
        $query = "
            SELECT * FROM RUBRIQUE
            WHERE ID_DD = (SELECT ID_DD FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopie)
              AND Nom_Rubrique NOT IN (
                  SELECT Nom_Calculee
                  FROM CALCULEE
                  WHERE CALCULEE.ID_DD = (SELECT ID_DD FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopie)
                  )
              AND Nom_Rubrique NOT IN (
                  SELECT Nom_Parametre
                  FROM PARAMETRE
                  WHERE PARAMETRE.ID_DD = (SELECT ID_DD FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopie)
                  )
              AND Nom_Rubrique NOT IN (
                  SELECT Nom_Attribut
                  FROM ATTRIBUT
                  WHERE ATTRIBUT.ID_DD = (SELECT ID_DD FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopie)
                  )
            ORDER BY Nom_Rubrique ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
    }



    /** ============================================================================================================ */
    /** ============================================================================================================ */
    /** ============================                  FONCTIONS UTILES                 ============================= */
    /** ============================================================================================================ */
    /** ============================================================================================================ */


    /**
     * Fonction qui retourne le max ID_MEA présent dans MEA
     * @param PDO $bdd
     * @return int
     */
    public static function selectMaxFromMEA ($bdd) {
        $query = $bdd->query("SELECT MAX(ID_MEA) AS resultMax FROM MEA");
        $result = $query->fetch();
        return (int)$result['resultMax'];
    }

    /**
     * Fonction qui retourne le max ID_MEA présent dans MEA
     * @param PDO $bdd
     * @return int
     */
    public static function selectMaxFromDD ($bdd) {
        $query = $bdd->query("SELECT MAX(ID_DD) AS resultMax FROM DD");
        $result = $query->fetch();
        return (int)$result['resultMax'];
    }



    /**
     * fonction qui retourne la liste des Associations d'une copie
     * @param PDO $bdd
     * @param $idCopie
     * @return mixed
     */
    public static function getAssociationsCopieEleve ($bdd, $idCopie) {
        $query = "
        SELECT * FROM ASSOCIATION
        WHERE ID_MEA = (SELECT ID_MEA FROM COPIE_ELEVE WHERE ID_Copie_Eleve=$idCopie)
        ORDER BY Nom_Association ASC
        ";
        $liste = $bdd->query($query);
        return $liste;
        // utiliser QueryFunctions::getEntitesRelieesParUneAsso ($bdd, $ID_MEA, $NomAsso) pour trouver les entités reliées par une association
    }




    /**
     * retourne l'identifiant de l'utilisateur passé en parmamètre (utilisateur connecté)
     * @param $login
     * @param PDO $bdd
     * @return int
     */
    public static function getID($login, $bdd){
        $query="
			SELECT ID_UTILISATEUR
			FROM UTILISATEUR
			WHERE LOGIN=:login
		";
        $exec=$bdd->prepare($query);
        $exec->bindParam(':login',$login);
        $exec->execute();
        $result=$exec->fetch(PDO::FETCH_NUM);
        if (isset($result[0])) return $result[0];
        else return -9999;
    }


    /**
     * renvoi vrai si l'user passé en paramètre (l'user connecté) est un professeur
     * @param $login
     * @param PDO $bdd
     * @return bool
     */
    public static function estProf($login,$bdd){

        $id = QueryFunctions::getID($login, $bdd);

        $query="
			SELECT COUNT(ID_UTILISATEUR)
			FROM PROFESSEUR
			WHERE ID_UTILISATEUR=:id
		";
        $exec=$bdd->prepare($query);
        $exec->bindParam(':id',$id);
        $exec->execute();
        $result=$exec->fetch(PDO::FETCH_NUM);
        if ($result[0]==1){
            return true;
        }
        return false;
    }



    /**
     * renvoi vrai si l'user passé en paramètre (l'user connecté) est un professeur
     * @param $id
     * @param PDO $bdd
     * @return bool
     */
    public static function estProfID($id,$bdd){

        $query="
			SELECT COUNT(ID_UTILISATEUR)
			FROM PROFESSEUR
			WHERE ID_UTILISATEUR=:id
		";
        $exec=$bdd->prepare($query);
        $exec->bindParam(':id',$id);
        $exec->execute();
        $result=$exec->fetch(PDO::FETCH_NUM);
        if ($result[0]==1){
            return true;
        }
        return false;
    }


    /**
     * renvoi vrai si l'user passé en paramètre (l'user connecté) est un étudiant
     * @param $login
     * @param PDO $bdd
     * @return bool
     */
    public static function estEtu($login,$bdd){

        $id=QueryFunctions::getID($login,$bdd);

        $query="
			SELECT COUNT(ID_UTILISATEUR)
			FROM ELEVE
			WHERE ID_UTILISATEUR=:id
		";
        $exec=$bdd->prepare($query);
        $exec->bindParam(':id',$id);
        $exec->execute();
        $result=$exec->fetch(PDO::FETCH_NUM);
        if ($result[0]==1){
            return true;
        }
        return false;
    }

    /**
     * renvoi vrai si l'user passé en paramètre (l'user connecté) est un professeur
     * @param $id
     * @param PDO $bdd
     * @return bool
     */
    public static function estEtuID($id,$bdd){

        $query="
			SELECT COUNT(ID_UTILISATEUR)
			FROM ELEVE
			WHERE ID_UTILISATEUR=:id
		";
        $exec=$bdd->prepare($query);
        $exec->bindParam(':id',$id);
        $exec->execute();
        $result=$exec->fetch(PDO::FETCH_NUM);
        if ($result[0]==1){
            return true;
        }
        return false;
    }



    /**
     * renvoi vrai si l'user passé en paramètre (l'user connecté) est un administrateur
     * @param $login
     * @param PDO $bdd
     * @return bool
     */
    public static function estAdmin($login,$bdd){

        $id=QueryFunctions::getID($login,$bdd);

        $query="
			SELECT COUNT(ID_UTILISATEUR)
			FROM ADMINISTRATEUR
			WHERE ID_UTILISATEUR=:id
		";
        $exec=$bdd->prepare($query);
        $exec->bindParam(':id',$id);
        $exec->execute();
        $result=$exec->fetch(PDO::FETCH_NUM);
        if ($result[0]==1){
            return true;
        }
        return false;
    }


    /**
     * renvoi vrai si l'user passé en paramètre (l'user connecté) est un professeur
     * @param $id
     * @param PDO $bdd
     * @return bool
     */
    public static function estAdminID($id,$bdd){

        $query="
			SELECT COUNT(ID_UTILISATEUR)
			FROM ADMINISTRATEUR
			WHERE ID_UTILISATEUR=:id
		";
        $exec=$bdd->prepare($query);
        $exec->bindParam(':id',$id);
        $exec->execute();
        $result=$exec->fetch(PDO::FETCH_NUM);
        if ($result[0]==1){
            return true;
        }
        return false;
    }

    /**
     * retourne vrai si le login passé en paramètre est disponible : cad si il n'est pas déja pris par un id différent de l'id donné
     * @param PDO $bdd
     * @param $login
     * @param null $id
     * @return bool
     */
    public static function loginNonPris($bdd, $login, $id=null){
        $query="
			SELECT COUNT(login) AS NB
			FROM UTILISATEUR
			WHERE login='$login'";
        if($id != null){
            $query=$query.' AND ID_Utilisateur<>'.$id;
        }
        $req = $bdd->query($query);


        $result = $req->fetch();
        if ($result['NB']==0) {
            return true;
        }
        else {
            return false;
        }
    }
}