CREATE TABLE IF NOT EXISTS ENTRAINEUR (
    id_entraineur smallint(5) unsigned NOT NULL AUTO_INCREMENT,
    mail VARCHAR(50) NOT NULL,
    mdp CHAR(64) NOT NULL,
    nom_e VARCHAR(50) NOT NULL DEFAULT '',
    prenom_e VARCHAR(50) NOT NULL DEFAULT '',
    datenaiss DATE NOT NULL,
    rue VARCHAR(255) NOT NULL DEFAULT '',
    cp mediumint(5) DEFAULT '0',
    ville VARCHAR(100) NOT NULL DEFAULT '',

    PRIMARY KEY (id_entraineur)
);

CREATE TABLE IF NOT EXISTS `MATCH` (
    id_match smallint(5) unsigned NOT NULL AUTO_INCREMENT,
    equipe_adverse VARCHAR(50) NOT NULL DEFAULT '',
    date_match DATE NOT NULL,
    type_match VARCHAR(50) NOT NULL DEFAULT '',
    score_equipe INT DEFAULT NULL,
    score_equipe_adverse INT DEFAULT NULL,
    statut_match VARCHAR(50) NOT NULL DEFAULT 'Convoquer des joueurs (pas assez)',
    id_entraineur smallint(5) unsigned,

    PRIMARY KEY (id_match),
    FOREIGN KEY (id_entraineur) REFERENCES ENTRAINEUR (id_entraineur)
);

CREATE TABLE IF NOT EXISTS EQUIPE (
    id_equipe smallint(5) unsigned NOT NULL AUTO_INCREMENT,
    nom_equipe VARCHAR(50) NOT NULL DEFAULT '',
    pays VARCHAR(100) NOT NULL DEFAULT '',
    ligue VARCHAR(50) NOT NULL DEFAULT '',
    id_entraineur smallint(5) unsigned NOT NULL,

    PRIMARY KEY (id_equipe),
    FOREIGN KEY (id_entraineur) REFERENCES ENTRAINEUR (id_entraineur)
);

CREATE TABLE IF NOT EXISTS STAFF (
    id_staff smallint(5) unsigned NOT NULL AUTO_INCREMENT, 
    nom VARCHAR(50) NOT NULL DEFAULT '', 
    prenom VARCHAR(50) NOT NULL DEFAULT '', 
    role_staff VARCHAR(50) NOT NULL DEFAULT '',
    id_equipe smallint(5) unsigned NOT NULL, 

    PRIMARY KEY (id_staff),
    FOREIGN KEY (id_equipe) REFERENCES EQUIPE (id_equipe)
);

CREATE TABLE IF NOT EXISTS JOUEUR (
    id_joueur smallint(5) unsigned NOT NULL AUTO_INCREMENT,
    num_joueur smallint(2) NOT NULL,
    nom_joueur VARCHAR(50) NOT NULL,
    prenom_joueur VARCHAR(50) NOT NULL,
    nationalité VARCHAR(50) NOT NULL,
    poste VARCHAR(50) NOT NULL,
    id_equipe smallint(5) unsigned NOT NULL,

    CONSTRAINT check_num_joueur_range CHECK (num_joueur BETWEEN 1 AND 99),

    PRIMARY KEY (id_joueur),
    FOREIGN KEY (id_equipe) REFERENCES EQUIPE (id_equipe)
);

CREATE TABLE IF NOT EXISTS STATISTIQUE_EQUIPE (
    id_stats_equipe smallint(5) unsigned NOT NULL AUTO_INCREMENT,
    victoires INT NOT NULL DEFAULT 0,
    defaites INT NOT NULL DEFAULT 0,
    buts_marquees INT NOT NULL DEFAULT 0,
    buts_encaissses INT NOT NULL DEFAULT 0,
    nombre_match INT NOT NULL DEFAULT 0,
    id_equipe smallint(5) unsigned NOT NULL,

    PRIMARY KEY (id_stats_equipe),
    FOREIGN KEY (id_equipe) REFERENCES EQUIPE (id_equipe)
);

CREATE TABLE IF NOT EXISTS BLESSURE (
    id_blessure smallint(5) unsigned NOT NULL AUTO_INCREMENT,
    date_blessure DATE NOT NULL,
    type_blessure VARCHAR(50) NOT NULL DEFAULT '',
    duree_blessure INT NOT NULL DEFAULT 0,
    id_joueur smallint(5) unsigned NOT NULL,

    PRIMARY KEY (id_blessure),
    FOREIGN KEY (id_joueur) REFERENCES JOUEUR (id_joueur)
);

CREATE TABLE IF NOT EXISTS STATISTIQUE_JOUEURS (
    id_stats_joueurs smallint(5) unsigned NOT NULL AUTO_INCREMENT,
    buts INT NOT NULL DEFAULT 0,
    passe_d INT NOT NULL DEFAULT 0,
    carton_jaune INT NOT NULL DEFAULT 0,
    carton_rouge INT NOT NULL DEFAULT 0,
    nombre_match INT NOT NULL DEFAULT 0,
    id_joueur smallint(5) unsigned NOT NULL, 

    PRIMARY KEY (id_stats_joueurs),
    FOREIGN KEY (id_joueur) REFERENCES JOUEUR (id_joueur)
);

CREATE TABLE IF NOT EXISTS PARTICIPATION (
    id_joueur smallint(5) unsigned NOT NULL,
    id_match smallint(5) unsigned NOT NULL,
    id_entraineur smallint(5) unsigned NOT NULL,
    statut VARCHAR(50) NOT NULL DEFAULT '',

    PRIMARY KEY (id_joueur, id_match),
    FOREIGN KEY (id_joueur) REFERENCES JOUEUR (id_joueur),
    FOREIGN KEY (id_match) REFERENCES `MATCH` (id_match),
    FOREIGN KEY (id_entraineur) REFERENCES ENTRAINEUR (id_entraineur)
);