-- ============================================================
--   Ce script contient les requêtes de mise à jour                                          
-- ============================================================


-- ============================================================
-- Les requêtes de mise à jour concernent les données suceptibles d'être mises à jour fréquemment (ex : date du trajet, prix du trajet)
-- Et non celles qui ont peu de chances d'être modifées (ex : plaque d'immatriculation, date de naissance)

-- Ces requêtes sont presentées sous forme de fonctions, prenant en paramètre l'ID de la requête à modifier, et les nouvelles 
-- valeur des attributs à modifier.

-- Etudiants ; mise a jour de l'école (en cas de changement d'école, réorientation / expulsion)
-- ============================================================

CREATE OR REPLACE FUNCTION maj_etudiants (maj_ID_etudiant INT, maj_ecole VARCHAR(20) DEFAULT NULL)
RETURNS VOID
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE Etudiants
    SET ecole = COALESCE(maj_ecole, ecole)
    WHERE ID_etudiant = maj_ID_etudiant;
END;
$$
;

-- Avis ; mise à jour du commentaire (en cas de commentaire inaproprié)

CREATE OR REPLACE FUNCTION maj_avis (maj_ID_avis INT,maj_commentaire VARCHAR(200) DEFAULT NULL)
RETURNS VOID 
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE Avis
    SET commentaire = COALESCE(maj_commentaire, commentaire) 
    WHERE ID_avis = maj_ID_avis;
END;
$$
;

-- Voiture; mise à jour de l'etat et du champ divers

CREATE OR REPLACE FUNCTION maj_voiture (maj_immatriculation VARCHAR(20) DEFAULT NULL, maj_etat VARCHAR(200) DEFAULT NULL , maj_divers VARCHAR(200) DEFAULT NULL)
RETURNS VOID 
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE Voitures
    SET etat = COALESCE(maj_etat, etat), divers = COALESCE(maj_divers,divers)
    WHERE immatriculation = maj_immatriculation;
END;
$$
;



-- Trajets; mise à jour de la ville, la date, heure du trajet   

CREATE OR REPLACE FUNCTION maj_trajets (maj_ID_trajet INT, maj_ville_depart VARCHAR(20) DEFAULT NULL, maj_date_depart DATE  DEFAULT NULL, maj_heure_depart TIME DEFAULT NULL)
RETURNS VOID
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE Trajets
    SET ville_depart=  COALESCE(maj_ville_depart, ville_depart), date_depart = COALESCE(maj_date_depart, date_depart), heure_depart = COALESCE(maj_heure_depart,heure_depart)
    WHERE ID_trajet = maj_ID_trajet;
END;
$$
;

--Trajets; mise à jour du prix et du statut d'acceptation/refus

CREATE OR REPLACE FUNCTION maj_points_arret (maj_ID_point_arret INT, maj_prix_par_passager FLOAT DEFAULT NULL, maj_statut_arret BOOLEAN DEFAULT NULL)
RETURNS VOID
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE Points_arret 
    SET prix_par_passager = COALESCE(maj_prix_par_passager, prix_par_passager), statut_arret = COALESCE(maj_statut_arret, statut_arret)
    WHERE ID_point_arret = maj_ID_point_arret;
END;
$$
;


-- Inscription; mise à jour du statut, pour accepter une demande d'inscription ou la révoquer
CREATE OR REPLACE FUNCTION maj_inscription (maj_ID_etudiant INT DEFAULT NULL, maj_ID_trajet INT DEFAULT NULL, maj_statut_inscription BOOLEAN DEFAULT NULL)
RETURNS VOID 
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE Inscriptions
    SET statut_inscription = COALESCE(maj_statut_inscription, statut_inscription)
    WHERE  ID_trajet = maj_ID_trajet AND ID_etudiant = maj_ID_etudiant;
END;
$$
;

-- ============================================================
-- Utilisation des fonctions
-- ============================================================

SELECT maj_etudiants(4,'BDS ENSEIRB-MATMECA');
SELECT maj_avis(30,'J''ai eu mal au dos et à mes oreilles');
SELECT maj_voiture('AL-112-BR','Elle a pris quelques pets la denière fois mais oklm', 'Vers');
SELECT maj_trajets(2, 'Havre', TO_DATE('2023-11-05', 'YYYY-MM-DD'), '17:00:00');
SELECT maj_points_arret(2, 200, TRUE);
SELECT maj_inscription(2, 1, TRUE);

-- ============================================================
-- Autres requètes
-- ============================================================

-- L'ENSEIRB fait faillite, réorientation pour tout le monde...
UPDATE Etudiants
    SET ecole = 'FAC DE LETTRES'
    WHERE ecole = 'ENSEIRB-MATMECA' ;
--SELECT * FROM Etudiants;


-- 5 étoiles pour tout le monde !
UPDATE Avis
    SET note = 5;
--SELECT * FROM Avis;

-- Inflation, on double les prix...
UPDATE Points_arret 
    SET prix_par_passager = prix_par_passager *2;


--Suppression des voitures SUV et de toutes les entrées qui en dépendent.
DELETE FROM Inscriptions WHERE Inscriptions.ID_point_arret IN (SELECT Points_arret.ID_point_arret FROM Points_arret WHERE Points_arret.ID_trajet IN (SELECT Trajets.ID_trajet FROM Trajets WHERE Trajets.immatriculation IN (SELECT Voitures.immatriculation  FROM Voitures WHERE Voitures.type_voiture = 'SUV')));
DELETE FROM Points_arret WHERE Points_arret.ID_trajet IN (SELECT Trajets.ID_trajet FROM Trajets WHERE Trajets.immatriculation IN (SELECT Voitures.immatriculation  FROM Voitures WHERE Voitures.type_voiture = 'SUV'));
DELETE FROM Trajets WHERE Trajets.immatriculation IN (SELECT Voitures.immatriculation  FROM Voitures WHERE Voitures.type_voiture = 'SUV');
DELETE FROM Voitures WHERE type_voiture = 'SUV';
--SELECT * FROM Voitures;

--Confinement on annule tout...
UPDATE Inscriptions
    SET statut_inscription = FALSE;
--SELECT * FROM Inscriptions;
