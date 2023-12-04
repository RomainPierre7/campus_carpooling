-- ========================================================================
--   Ce script contient les requêtes de consultations et de statistiques                                            
-- ========================================================================


-- ============================================================
--   consultations                                           
-- ============================================================

-- Informations sur les conducteurs , les passagers

-- Info conduteurs:
SELECT Etudiants.*
FROM Etudiants INNER JOIN Voitures
ON Etudiants.ID_etudiant = Voitures.ID_etudiant;

-- Info passagers:
SELECT Etudiants.*
FROM Etudiants INNER JOIN Inscriptions
ON Etudiants.ID_etudiant = Inscriptions.ID_etudiant
WHERE statut_inscription = 'TRUE';

-- La liste des véhicules disponibles pour un jour donné pour une ville donnée

CREATE OR REPLACE FUNCTION vehicule_dispo(jour DATE, ville VARCHAR(20))
RETURNS SETOF Voitures
LANGUAGE plpgsql
AS $$
BEGIN 
    RETURN QUERY SELECT Voitures.*
    FROM Voitures INNER JOIN Trajets
        ON Voitures.immatriculation = Trajets.immatriculation
    WHERE Trajets.date_depart = jour AND Trajets.ville_depart = ville;
END;
$$
;

-- Les trajets proposés dans un intervalle de jours donné

CREATE OR REPLACE FUNCTION trajet_proposes(jour_debut DATE, jour_fin DATE)
RETURNS SETOF RECORD
LANGUAGE plpgsql AS
$$
BEGIN
    RETURN QUERY SELECT * FROM Trajets WHERE date_depart BETWEEN jour_debut AND jour_fin;
END;
$$;


-- La liste des villes renseignées entre le campus et une ville donnée
CREATE OR REPLACE FUNCTION villes_renseignees(identifiant_trajet INTEGER ,ville_destination VARCHAR(20))
RETURNS TABLE(ville_arret VARCHAR(20))
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY SELECT p.ville_arret
    FROM Points_arret p
    WHERE p.ID_trajet = identifiant_trajet 
    AND p.distance_trajet <= (SELECT p2.distance_trajet 
                                FROM Points_arret p2
                                WHERE p2.ID_trajet = identifiant_trajet
                                AND p2.ville_arret = ville_destination
                                ORDER BY p2.distance_trajet ASC
                                LIMIT 1);
END;
$$
;

-- Les trajets pouvant desservir une ville donnée dans un intervalle de temps

CREATE OR REPLACE FUNCTION desservir_villes(ville_deservie VARCHAR(20), borne_inf INTEGER , borne_sup INTEGER)
RETURNS SETOF Trajets
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY SELECT Trajets.*
    FROM Trajets INNER JOIN Points_arret
    ON Trajets.ID_trajet = Points_arret.ID_trajet
    WHERE Points_arret.ville_arret = ville_deservie 
    AND Points_arret.duree_trajet BETWEEN borne_inf AND borne_sup;
END;
$$
;


-- ============================================================
--   statistiques                                         
-- ============================================================


-- Moyenne des passagers sur l’ensemble des trajets effectués

SELECT AVG(nombre_passager) as moyenne_des_passagers
FROM ( 
        SELECT COUNT(*) as nombre_passager
        FROM Inscriptions
        WHERE statut_inscription = 'TRUE'
        GROUP BY Id_trajet
) as nombre_passager_par_trajet;

-- Moyenne des distances parcourues en covoiturage par jour
SELECT
    date_depart,
    AVG(distance_trajet) AS moyenne_distance
FROM
    Trajets
    JOIN Points_arret ON Trajets.ID_trajet = Points_arret.ID_trajet
GROUP BY
    date_depart;

-- Tous les trajets pour 1 jour donné

CREATE OR REPLACE FUNCTION trajets_date(p_date_depart DATE)
RETURNS SETOF Trajets
LANGUAGE plpgsql
AS $$
BEGIN
    RETURN QUERY
    SELECT Trajets.*
    FROM
        Trajets
    WHERE
        date_depart >= p_date_depart
        AND date_depart < (p_date_depart + 1);
END;
$$
;

-- Classement des meilleurs conducteurs d’aprés les avis

SELECT
    v.ID_etudiant,
    e.prenom, e.nom,
    AVG(a.note)
FROM
    Voitures v
JOIN
    Trajets t ON v.immatriculation = t.immatriculation
JOIN
    Avis a ON v.ID_etudiant = a.etudiant_redacteur
JOIN
    Etudiants e ON v.ID_etudiant = e.ID_etudiant
GROUP BY
    v.ID_etudiant, e.prenom, e.nom
ORDER BY
    AVG(a.note) DESC;

-- ============================================================
--   requetes bonus                                      
-- ============================================================



-- classement des villes selon le nombre de trajets qui les dessert.

SELECT ville_arret, COUNT(DISTINCT ID_trajet) AS nombre_trajet
FROM Points_arret
WHERE statut_arret = TRUE 
GROUP BY ville_arret
ORDER BY nombre_trajet DESC;

-- moyenne des avis pour 1 etudaiant

CREATE OR REPLACE FUNCTION moy_avis(etudiant_arg INTEGER)
RETURNS NUMERIC
LANGUAGE plpgsql AS $$
DECLARE
    moyenne_note NUMERIC;
    etudiant_cast INTEGER := etudiant_arg::INTEGER; -- Explicit casting, if necessary
BEGIN
    SELECT AVG(a.note) INTO moyenne_note
    FROM Etudiants e
    INNER JOIN Reception_avis r ON e.ID_etudiant = r.etudiant_note
    INNER JOIN Avis a ON r.ID_avis = a.ID_avis 
    WHERE r.etudiant_note = etudiant_cast; -- Use the cast variable

    IF moyenne_note IS NULL THEN
        RETURN 0;
    ELSE
        RETURN ROUND(moyenne_note,2); 
    END IF;
END;
$$;


SELECT e.nom, e.prenom, v.immatriculation, v.couleur, moy_avis(e.ID_etudiant) as note_moyenne
FROM Voitures v INNER JOIN Etudiants e
ON v.ID_etudiant = e.ID_etudiant
    INNER JOIN Trajets t 
    ON t.immatriculation = v.immatriculation
WHERE t.Id_trajet = 1
GROUP BY v.immatriculation, e.ID_etudiant;

