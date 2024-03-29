CREATE TABLE ENTRAINEUR (
    id_entraineur INT,
    mail VARCHAR(50),
    mdp CHAR,
    nom_e VARCHAR(50),
    prenom_e VARCHAR(50),
    datenaiss DATE,
    rue VARCHAR(255),
    cp INT,
    ville VARCHAR(100),

    PRIMARY KEY (id_entraineur)
);

CREATE TABLE `MATCH` (
    id_match INT,
    equipe_adverse VARCHAR(50),
    date_match DATE,
    type_match VARCHAR(50),
    score_equipe INT,
    score_equipe_adverse INT,

    PRIMARY KEY (id_match)
);

CREATE TABLE EQUIPE (
    id_equipe INT,
    nom_equipe VARCHAR(50),
    pays VARCHAR(100),
    ligue VARCHAR(50),
    id_entraineur INT,

    PRIMARY KEY (id_equipe),
    FOREIGN KEY (id_entraineur) REFERENCES ENTRAINEUR (id_entraineur)
);

CREATE TABLE STAFF (
    id INT, 
    nom VARCHAR(50) NOT NULL, 
    prenom VARCHAR(50) NOT NULL, 
    role VARCHAR(50) NOT NULL,
    id_equipe INT, 

    PRIMARY KEY (id),
    FOREIGN KEY (id_equipe) REFERENCES EQUIPE (id_equipe)
);

CREATE TABLE JOUEUR (
    num_joueur INT,
    nom_joueur VARCHAR(50),
    prenom_joueur VARCHAR(50),
    nationalité VARCHAR(50),
    poste VARCHAR(50),
    id_equipe INT,

    PRIMARY KEY (num_joueur),
    FOREIGN KEY (id_equipe) REFERENCES EQUIPE (id_equipe)
);

CREATE TABLE STATISTIQUE_EQUIPE (
    id_stats_equipe INT,
    victoires INT,
    defaites INT,
    buts_marquees INT,
    buts_encaissses INT,
    nombre_match INT,
    id_equipe INT,

    PRIMARY KEY (id_stats_equipe),
    FOREIGN KEY (id_equipe) REFERENCES EQUIPE (id_equipe)
);

CREATE TABLE BLESSURE (
    id_blessure INT,
    date_blessure DATE,
    type_blessure VARCHAR(50),
    duree_blessure INT,
    num_joueur INT,

    PRIMARY KEY (id_blessure),
    FOREIGN KEY (num_joueur) REFERENCES JOUEUR (num_joueur)
);

CREATE TABLE STATISTIQUE_JOUEURS (
    id_stats_joueurs INT,
    buts INT,
    passe_d INT,
    carton_jaune INT,
    carton_rouge INT,
    nombre_match INT,
    num_joueur INT, 

    PRIMARY KEY (id_stats_joueurs),
    FOREIGN KEY (num_joueur) REFERENCES JOUEUR (num_joueur)
);

CREATE TABLE PARTICIPATION (
    num_joueur INT,
    id_match INT,
    statut VARCHAR(50),

    PRIMARY KEY (num_joueur,id_match),
    FOREIGN KEY (num_joueur) REFERENCES JOUEUR (num_joueur),
    FOREIGN KEY (id_match) REFERENCES `MATCH` (id_match)
);