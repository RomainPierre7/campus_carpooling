-- ============================================================
--   Ce script cree la base de donnees                                            
-- ============================================================

-- ============================================================
--   Nom de la base   :  covoiturage_du_campus                                
--   Nom de SGBD      :  PostgreSQL                   
-- ============================================================

-- Table Etudiants
CREATE TABLE Etudiants
(
    ID_etudiant SERIAL NOT NULL UNIQUE,
    prenom VARCHAR(20) NOT NULL,
    nom VARCHAR(20) NOT NULL,
    date_de_naissance DATE NOT NULL,
    ecole VARCHAR(20) NOT NULL,
    CONSTRAINT pk_etudiants PRIMARY KEY (ID_etudiant)
);

-- Table Avis
CREATE TABLE Avis
(
    ID_avis SERIAL NOT NULL UNIQUE,
    note INT NOT NULL,
    commentaire VARCHAR(200) NOT NULL,
    etudiant_redacteur INT NOT NULL,
    CONSTRAINT pk_avis PRIMARY KEY (ID_avis),
    CONSTRAINT fk_etudiant_redacteur FOREIGN KEY (etudiant_redacteur) REFERENCES Etudiants(ID_etudiant)
);

-- Table Recpetion_avis
CREATE TABLE Reception_avis(
    etudiant_note INT NOT NULL,
    ID_avis INT NOT NULL,
    CONSTRAINT pk_recp_avis PRIMARY KEY (etudiant_note, ID_avis),
    CONSTRAINT fk_etudiant_note FOREIGN KEY (etudiant_note) REFERENCES Etudiants(ID_etudiant),
    CONSTRAINT fk_id_avis FOREIGN KEY (ID_avis) REFERENCES Avis(ID_avis)
);

-- Table Voitures
CREATE TABLE Voitures
(
    immatriculation VARCHAR(20) NOT NULL UNIQUE,
    type_voiture VARCHAR(20) NOT NULL,
    couleur VARCHAR(20),
    nombre_de_places INT NOT NULL,
    etat VARCHAR(200) NOT NULL,
    divers VARCHAR(200),
    ID_etudiant INT NOT NULL,
    CONSTRAINT pk_voitures PRIMARY KEY (immatriculation),
    CONSTRAINT fk_etudiant_voitures FOREIGN KEY (ID_etudiant) REFERENCES Etudiants(ID_etudiant)
);

-- Table Trajets
CREATE TABLE Trajets
(
    ID_trajet SERIAL NOT NULL UNIQUE,
    ville_depart VARCHAR(20) NOT NULL,
    date_depart DATE NOT NULL,
    heure_depart TIME NOT NULL,
    immatriculation VARCHAR(20) NOT NULL,
    CONSTRAINT pk_trajets PRIMARY KEY (ID_trajet),
    CONSTRAINT fk_voiture_trajets FOREIGN KEY (immatriculation) REFERENCES Voitures(immatriculation)
);

-- Table Points d'arret
CREATE TABLE Points_arret
(
    ID_point_arret SERIAL NOT NULL UNIQUE,
    ville_arret VARCHAR(20) NOT NULL,
    duree_trajet INT NOT NULL,
    distance_trajet FLOAT NOT NULL,
    prix_par_passager FLOAT NOT NULL,
    statut_arret BOOLEAN NOT NULL,
    ID_trajet INT NOT NULL,
    CONSTRAINT pk_points_arret PRIMARY KEY (ID_point_arret),
    CONSTRAINT fk_trajet_points_arret FOREIGN KEY (ID_trajet) REFERENCES Trajets(ID_trajet)

);

-- Table Inscriptions
CREATE TABLE Inscriptions
(
    ID_etudiant INT NOT NULL,
    ID_trajet INT NOT NULL,
    ID_point_arret INT NOT NULL,
    statut_inscription BOOLEAN,
    CONSTRAINT pk_inscriptions PRIMARY KEY (ID_etudiant, ID_trajet),
    CONSTRAINT fk_etudiant_inscriptions FOREIGN KEY (ID_etudiant) REFERENCES Etudiants(ID_etudiant),
    CONSTRAINT fk_trajet_inscriptions FOREIGN KEY (ID_trajet) REFERENCES Trajets(ID_trajet),
    CONSTRAINT fk_points_arret_inscriptions FOREIGN KEY (ID_point_arret) REFERENCES Points_arret(ID_point_arret)
);

-- Les contraintes d'integrité concernent les valeurs introductibles par un utilisateur, 
-- on suppose que la durée du trajet par exemple serait calculée par une applicatione externe

ALTER TABLE Avis
    ADD CONSTRAINT note_positive CHECK (note BETWEEN 1 AND 5);
    
ALTER TABLE Voitures
    ADD CONSTRAINT nombre_de_places_positive CHECK (nombre_de_places > 0);

ALTER TABLE Points_arret
    ADD CONSTRAINT arret_unique UNIQUE (ville_arret, ID_trajet);

ALTER TABLE Points_arret
    ADD CONSTRAINT prix_positif CHECK (prix_par_passager > 0);


CREATE OR REPLACE FUNCTION non_conducteur_inscription()
RETURNS TRIGGER AS $$
BEGIN
    IF (SELECT v.ID_etudiant FROM Trajets t, Voitures v WHERE t.ID_trajet = NEW.ID_trajet AND v.immatriculation = t.immatriculation ) = NEW.ID_etudiant THEN
        RAISE EXCEPTION 'Le conducteur ne peut pas s''inscrire à son propre trajet';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

CREATE TRIGGER non_conducteur_inscription_trigger
BEFORE INSERT ON Inscriptions
FOR EACH ROW
EXECUTE FUNCTION non_conducteur_inscription();





-- Trigger pour a chaque validation d'inscription verifier que le trajet n'est pas plein
CREATE OR REPLACE FUNCTION trajet_non_plein_check()
RETURNS TRIGGER AS $$
DECLARE
BEGIN
    IF 

    (SELECT COUNT(*)
    FROM Inscriptions i
    WHERE i.ID_trajet = NEW.ID_trajet AND i.statut_inscription = true)
    
    >= 
    
    (SELECT v.nombre_de_places 
    FROM Voitures v
    WHERE v.immatriculation IN (
        SELECT t.immatriculation
        FROM Trajets t
        WHERE t.ID_trajet = NEW.ID_trajet)) 
     
    THEN
        RAISE EXCEPTION 'Le trajet est plein. L''inscription ne peut pas être validée.';
    ELSE
    END IF;

    RETURN NEW;
END;
$$ LANGUAGE plpgsql;
             

-- Requête pour tester le trigger : UPDATE Inscriptions SET statut_inscription = TRUE WHERE  ID_trajet = '2' AND ID_etudiant = '17';
CREATE TRIGGER trajet_non_plein_trigger
BEFORE INSERT OR UPDATE ON Inscriptions
FOR EACH ROW
WHEN (NEW.statut_inscription = true)
EXECUTE FUNCTION trajet_non_plein_check();



-- Eviter que les gens se donnent des avis à eux mêmes : 
CREATE OR REPLACE FUNCTION verif_auto_avis_reception()
RETURNS TRIGGER AS $$
BEGIN
    IF NEW.etudiant_note IN (SELECT a.etudiant_redacteur FROM Avis a WHERE a.ID_avis = NEW.ID_avis)THEN
        RAISE EXCEPTION 'Un rédacteur d''avis ne peut pas donner un avis à lui-même.';
    END IF;
    RETURN NEW;
END;
$$ LANGUAGE plpgsql;

-- Requête pour tester le trigger : INSERT INTO Reception_avis(etudiant_note, ID_avis) VALUES(3, 1);
CREATE TRIGGER verif_auto_avis_reception_trigger
BEFORE INSERT ON Reception_avis
FOR EACH ROW
EXECUTE FUNCTION verif_auto_avis_reception();

