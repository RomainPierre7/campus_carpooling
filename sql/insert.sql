-- ============================================================
--   Ce script peuple la base de donnees                                            
-- ============================================================

TRUNCATE Etudiants, Avis, Reception_avis, Voitures, Trajets, Points_arret, Inscriptions RESTART IDENTITY;

-- Etudiants
INSERT INTO Etudiants(ID_etudiant, prenom, nom, date_de_naissance, ecole) VALUES
(1, 'Yannis', 'MOUSSO', TO_DATE('2002-11-01','YYYY-MM-DD'), 'ENSEIRB-MATMECA'),
(2, 'Romain', 'CAILLOU', TO_DATE('2002-01-08','YYYY-MM-DD') , 'ENSEIRB-MATMECA'), 
(3, 'Abdurahman', 'KANSAIDI', TO_DATE('2002-05-05','YYYY-MM-DD'), 'ENSEIRB-MATMECA'),
(4, 'Mathys', 'OSTELLO', TO_DATE('2002-08-07','YYYY-MM-DD'), 'ENSEIRB-MATMECA'),
(5, 'Laura', 'MARTIN', TO_DATE('2000-04-05','YYYY-MM-DD'), 'ENSTBB'),
(6, 'Eva', 'SORNE', TO_DATE('2001-01-20','YYYY-MM-DD'), 'ENSTBB'),
(7, 'Gabin', 'TOUTAIN ', TO_DATE('2002-02-18','YYYY-MM-DD'), 'ENSPIMA'),
(8, 'Samira', 'HAKIMI', TO_DATE('1999-03-04','YYYY-MM-DD'), 'ENSTBB'), 
(9, 'Moussa', 'MENDY', TO_DATE('2001-11-10','YYYY-MM-DD'), 'ENSPIMA'),
(10, 'Charlotte', 'GONALONS', TO_DATE('1998-06-22','YYYY-MM-DD'), 'UNIVERSITÉ MONTAIGNE'),
(11, 'Ines', 'ZIDANE', TO_DATE('2004-12-11','YYYY-MM-DD'), 'INSEEC'),  
(12, 'Marie', 'LEROUX', TO_DATE('2005-07-09','YYYY-MM-DD'), 'ENSAM'),  
(13, 'Leslie', 'PIERRE', TO_DATE('2002-09-24','YYYY-MM-DD'), 'ENSAM'),
(14, 'Massimo', 'DE BIASI', TO_DATE('2001-08-26','YYYY-MM-DD'), 'UNIVERSITÉ MONTAIGNE'),
(15, 'Iyes', 'BAOUAL', TO_DATE('2005-10-08','YYYY-MM-DD'), 'SCIENCES POLITIQUES'),
(16, 'Maximilien', 'VIANO', TO_DATE('2000-12-16','YYYY-MM-DD'), 'KEDGE'), 
(17, 'Lola', 'STAVO', TO_DATE('1998-01-20','YYYY-MM-DD'), 'KEDGE'),
(18, 'Stanislas', 'DOUTEMENT', TO_DATE('2005-03-11','YYYY-MM-DD'), 'SCIENCES AGRO'),
(19, 'Marcel', 'DESAILLY', TO_DATE('2000-01-17','YYYY-MM-DD'), 'SCIENCES AGRO'),
(20, 'Angela', 'KOPA', TO_DATE('1997-02-08','YYYY-MM-DD'), 'SCIENCES POLITIQUES'),
(21, 'Eden', 'DESTIN', TO_DATE('1998-11-06','YYYY-MM-DD'), 'ENSAM'),  
(22, 'Lionel', 'MOSSES', TO_DATE('2003-05-03','YYYY-MM-DD'), 'ENSPIMA'),
(23, 'Olivier', 'GIRATOIRE', TO_DATE('2005-11-09','YYYY-MM-DD'), 'ENSTBB'), 
(24, 'Ugo', 'ETIQUETTE', TO_DATE('2002-02-02','YYYY-MM-DD'), 'KEDGE'),
(25, 'Marie', 'MENSONGE', TO_DATE('2004-05-31','YYYY-MM-DD'), 'ENSEIRB-MATMECA'),
(26, 'Samy', 'TORTUE', TO_DATE('2003-12-11','YYYY-MM-DD'), 'INSEEC'); 

--Avis
INSERT INTO Avis(ID_avis, note, commentaire, etudiant_redacteur) VALUES
(1, 4, 'Trajet très sympathique. Conducteur sérieux et sympathique, très animé.', 3),
(2, 4, 'Trajet agréable, conducteur sympathique et avenant.',2),
(3, 5, 'Passager sympa et drôle.', 1),
(4, 4, 'Passager sympa et calme.', 1),
(5, 5, 'Conducteur drôle qui a réussi à créer une conversation entre tous les passagers et à mettre une bonne ambiance lors du voyage.', 4),
(6, 5, '', 9),
(7, 4, 'Bonne ambiance lors du voyage.', 6),
(8, 5, 'Passager gentil et calme.', 2),
(9, 4, 'Passager silencieux.', 2),
(10, 4, 'Passager sympathique.', 2),
(11, 5, 'Deuxième trajet sympathique.',2), 
(12, 5, 'Passager toujours aussi agréable pour le voyage.', 1),
(13, 5, 'Passager toujours aussi agréable pour le voyage.', 1),
(14, 1, 'Conducteur dangereux et irrespectueux.', 22), 
(15, 2, 'Trajet long et ennuyeux dans un terrible froid.', 18),  
(16, 1, 'Trajet soporifique avec une faible température dans la voiture.', 7),
(17, 4, 'Passager fatigué mais calme.', 26),
(18, 4, 'Passager qui a dormi.', 26),
(19, 5, 'Conducteur parfait avec une voiture spatieuse et confortable.', 10),
(20, 5, 'Voyage agréable, le conducteur était sympathique. Je recommande fortement.', 12),
(21, 5, '', 14),
(22, 5, 'Co-passager incroyablement drôle.', 10),
(23, 4, 'Voyage sympathique.', 24),
(24, 5, 'Conducteur prudent et agréable.', 19),
(25, 5, 'Super voyage.', 20),
(26, 5, '', 8),
(27, 5, 'Musique incroyable, le conducteur est un vrai DJ.', 5),
(28, 1, 'Voiture très désagréable pour le voyage.', 15),
(29, 1, 'Prix élevé par rapport au confort de la voiture.', 13),
(30, 1, 'Seule voyage disponible malheuresement pour mon dos et mes oreilles.', 21),
(31, 1, 'Passager irrespectueux et en retard.', 12),
(32, 5, 'Conducteur gentil et sympathique.', 26),
(33, 4, 'Passager sympathique et bon co-pilote.', 2);

-- Reception_avis
INSERT INTO Reception_avis(etudiant_note, ID_avis) VALUES
(1, 1),
(1, 2),
(2, 3), 
(3, 4),
(2, 5),
(2, 6),
(2, 7),
(4, 8),
(9, 9),
(6, 10),
(1, 11),
(2, 12),
(3, 13),
(21, 14),
(26, 15),
(26, 16),
(18, 17),
(7, 18),
(23, 19),
(23, 20),
(23, 21),
(12, 22),
(8, 23),
(8, 24),
(8, 25),
(19, 26),
(20, 26),
(24, 26),
(16, 27),
(12, 28),
(12, 29),
(12, 30),
(13, 31),
(2, 32),
(26, 33);

--Voitures
INSERT INTO Voitures(immatriculation, type_voiture, couleur, nombre_de_places, etat, divers, ID_etudiant) VALUES
('EK-667-IP', 'Berline', 'Noire', 4, 'Très bon', 'Coffre spacieux', 1), -- 2 trajets actu
('FR-258-OI', 'Citadine', 'Bleu', 3, 'Excellent', 'Petit coffre, petit bagage à privilégier.', 2), -- 1 trajet
('SF-895-PL', 'Break', 'Rouge', 4, 'Moyen', 'Chauffage et clim cassés.', 26), -- 1 trajet et nen refera plus 
('AL-112-BR', 'Citadine', 'Bleu', 3, 'Excellent', NULL, 23),
('RG-542-VX', 'Citadine', 'Blanche', 4, 'Excellent', 'Un son de qualité pour un voyage musical.', 16),
('WS-765-DH', 'Citadine', 'Rouge', 3, 'Moyen', 'La vitre côté passager ne fonctionne plus.', 21),
('CE-669-LK', 'SUV', 'Verte', 4, 'Bon', NULL, 8),
('PO-354-TO', 'Break', 'Bleu', 3, 'Mauvais', 'Chauffage et clim cassés, ceinture compliqué à attacher et bruit de moteur omniprésent.', 12),
('LO-152-ER', 'Berline', 'Jaune', 4, 'Bon', NULL, 11);

--Trajets
INSERT INTO Trajets(ID_trajet, ville_depart, date_depart, heure_depart, immatriculation) VALUES
(1, 'Bordeaux', TO_DATE('2023-10-28', 'YYYY-MM-DD'), '20:00:00', 'EK-667-IP'),
(2, 'Havre', TO_DATE('2023-12-25', 'YYYY-MM-DD'), '07:30:00', 'FR-258-OI'),
(3, 'Toulouse', TO_DATE('2023-11-05', 'YYYY-MM-DD'), '16:00:00', 'EK-667-IP'),
(4, 'Bordeaux', TO_DATE('2023-10-10', 'YYYY-MM-DD'), '13:30:00', 'WS-765-DH'),
(5, 'Bordeaux', TO_DATE('2024-02-29', 'YYYY-MM-DD'), '09:00:00', 'SF-895-PL'),
(6, 'Bordeaux', TO_DATE('2024-03-17', 'YYYY-MM-DD'), '15:00:00', 'AL-112-BR'),
(7, 'Bordeaux', TO_DATE('2024-01-03', 'YYYY-MM-DD'), '10:00:00', 'RG-542-VX'),
(8, 'Marseille',TO_DATE('2024-02-20', 'YYYY-MM-DD'), '14:00:00', 'CE-669-LK'),
(9, 'Bordeaux', TO_DATE('2024-03-26', 'YYYY-MM-DD'), '16:00:00', 'PO-354-TO'),
(10, 'Bordeaux', TO_DATE('2024-04-10', 'YYYY-MM-DD'), '09:00:00', 'FR-258-OI');


--Points d'arret
INSERT INTO Points_arret(ville_arret, duree_trajet, distance_trajet, prix_par_passager, statut_arret, ID_trajet) VALUES
('Toulouse', 163, 245, 15, TRUE, 1), -- trajet BDX -> Toulouse (passager )
('Mont-de-Marsan', 103, 134, 7, FALSE, 1),
('Havre', 403, 686 , 35, TRUE, 2), -- trajet LH -> BDX (passager)
('Caen', 347, 603, 25, TRUE, 2), --demande a monter a Caen pour aller a Bordeaux
('Rouen', 378, 655, 30, FALSE, 2),
('Poitiers', 442, 251, 10, TRUE, 2),
('Toulouse', 163, 245, 15, TRUE, 3), -- trajet Toulouse -> BDX (passager)
('Bayonne', 130, 184, 6, TRUE, 4), -- trajet BDX -> Bayonne
('Montpellier', 310, 485, 20, TRUE, 5), -- trajet BDX-> MTP
('Lyon', 346, 557, 35, TRUE, 6),
('Marseille', 402, 646, 30, TRUE, 7),
('Carcassonne', 214, 312, 15, TRUE, 7),
('Marseille', 402, 646, 20, TRUE, 8), -- trajet MARS -> BDX
('Toulouse', 163, 245, 10, FALSE, 8),
('Paris', 309, 585, 25, TRUE, 9),
('Reims', 435, 719, 30, TRUE, 10);


--Inscriptions
INSERT INTO Inscriptions(ID_etudiant, ID_trajet, ID_point_arret, statut_inscription) VALUES 
(2, 1, 1, TRUE),  --TLS -> BDX romain 
(3, 1, 1, TRUE),  --TLS -> BDX abdu  
(4, 1, 2, FALSE), -- BDX ->  mont de marsan
(4, 2, 3, TRUE), -- LH -> BDX mathys
(9, 2, 4, TRUE), -- 
(6, 2, 6, TRUE),
(17, 2, 5, FALSE),
(2, 3, 7, TRUE),
(3, 3, 7, TRUE),
(22, 4, 8, TRUE),
(18, 5, 9, TRUE),
(7, 5, 9, TRUE);

INSERT INTO Inscriptions(ID_etudiant, ID_trajet, ID_point_arret, statut_inscription) VALUES 
(10, 6, 10, TRUE),
(12, 6, 10, TRUE),
(11, 7, 11, TRUE),
(17, 7, 11, TRUE),
(25, 7, 12, TRUE),
(24, 8, 13, TRUE),
(15, 8, 14, FALSE),
(19, 8, 13, TRUE),
(20, 8, 13, TRUE),
(13, 9, 15, TRUE),
(15, 9, 15, FALSE),
(21, 9, 15, FALSE),
(26, 10, 16, TRUE),
(15, 10, 15, FALSE),
(21, 10, 15, FALSE);


-- ============================================================
--    verification des donnees
-- ============================================================

select count(*),'= 26 ?' as expected ,'Etudiants' as table from Etudiants 
union
select count(*),'= 33 ?','Avis' from Avis 
union
select count(*), '= 35 ?', 'Reception_avis' from Reception_avis
union
select count(*),'= 9 ?','Voitures' from Voitures 
union
select count(*),'= 10 ?','Trajets' from Trajets 
union
select count(*), '= 27 ?', 'Inscriptions' from Inscriptions
union
select count(*),'= 16 ?','Points_arret' from Points_arret;
