drop table if exists
    utilisateur_etablissement,
    utilisateur,
    salleClasse_matiere,
    professeur_salleClasse,
    donnee,
    salleClasse,
    professeur_matiere,
    presence_professeur,
    professeur,
    classe_matiere,
    matiere,
    horaire_jour,
    fourche_preferentiel,
    jour,
    horaire_creneau,
    horaire,
    eleve,
    creneau,
    classe,
    etablissement;

create table etablissement
(
    etablissementId      int auto_increment
        primary key,
    etablissementAdresse varchar(255) not null,
    etablissementNom     varchar(255) not null,

    unique (etablissementAdresse)
);

create table classe
(
    classeId        int auto_increment
        primary key,
    classeRef       varchar(255) not null,
    etablissementId int          not null,

    unique (classeRef, etablissementId),
    foreign key (etablissementId) references etablissement (etablissementId)
);

create table creneau
(
    creneauId         int auto_increment
        primary key,
    creneauHeureStart time not null,
    creneauHeureEnd   time not null,
    creneauGroupe     int           not null,
    etablissementId   int           not null,

    unique (creneauHeureEnd, etablissementId),
    unique (creneauHeureStart, etablissementId),
    foreign key (etablissementId) references etablissement (etablissementId)
);

create table eleve
(
    eleveId         int auto_increment
        primary key,
    eleveEmail      varchar(255) not null,
    eleveNom        varchar(255) not null,
    elevePrenom     varchar(255) not null,
    etablissementId int          not null,
    classeId        int          not null,

    unique (eleveEmail, etablissementId),
    foreign key (classeId) references classe (classeId),
    foreign key (etablissementId) references etablissement (etablissementId)
);

create table horaire
(
    horaireId       int auto_increment
        primary key,
    etablissementId int not null,

    foreign key (etablissementId) references etablissement (etablissementId)
);

create table horaire_creneau
(
    horaireCreneauId int auto_increment
        primary key,
    horaireId        int not null,
    creneauId        int not null,

    unique (horaireId, creneauId),
    foreign key (creneauId) references creneau (creneauId),
    foreign key (horaireId) references horaire (horaireId)
);

create table jour
(
    jourId  int auto_increment
        primary key,
    jourNom varchar(255) not null,

    unique (jourNom)
);

create table fourche_preferentiel
(
    fourchePreferentielId         int auto_increment
        primary key,
    creneauId                     int                                                    not null,
    jourId                        int                                                    not null,
    fourchePreferentielPreference enum ('very-unlikely', 'unlikely', 'normal', 'better') not null,

    unique (creneauId, jourId),
    foreign key (creneauId) references creneau (creneauId),
    foreign key (jourId) references jour (jourId)
);

create table horaire_jour
(
    horaireJourId int auto_increment
        primary key,
    horaireId     int not null,
    jourId        int not null,

    unique (horaireId, jourId),
    foreign key (horaireId) references horaire (horaireId),
    foreign key (jourId) references jour (jourId)
);

create table matiere
(
    matiereId       int auto_increment
        primary key,
    matiereNom      varchar(255) not null,
    etablissementId int          not null,

    unique (matiereNom, etablissementId),
    foreign key (etablissementId) references etablissement (etablissementId)
);

create table classe_matiere
(
    classeMatiereId           int auto_increment
        primary key,
    classeMatiereNombreHeures int not null,
    classeId                  int not null,
    matiereId                 int not null,

    unique (classeId, matiereId),
    foreign key (classeId) references classe (classeId),
    foreign key (matiereId) references matiere (matiereId)
);

create table professeur
(
    professeurId     int auto_increment
        primary key,
    professeurEmail  varchar(255) not null,
    professeurNom    varchar(255) not null,
    professeurPrenom varchar(255) not null,
    etablissementId  int          not null,

    unique (professeurEmail, etablissementId),
    foreign key (etablissementId) references etablissement (etablissementId)
);

create table presence_professeur
(
    presenceProfesseurId            int auto_increment
        primary key,
    professeurId                    int                                             not null,
    creneauId                       int                                             not null,
    jourId                          int                                             not null,
    presenceProfesseurDisponibilite enum ('unavailable', 'prefer-not', 'available') not null,

    unique (professeurId, creneauId, jourId),
    foreign key (creneauId) references creneau (creneauId),
    foreign key (jourId) references jour (jourId),
    foreign key (professeurId) references professeur (professeurId)
);

create table professeur_matiere
(
    professeurMatiereId int auto_increment
        primary key,
    professeurId        int not null,
    matiereId           int not null,

    unique (professeurId, matiereId),
    foreign key (matiereId) references matiere (matiereId),
    foreign key (professeurId) references professeur (professeurId)
);

create table salleClasse
(
    salleClasseId            int auto_increment
        primary key,
    salleClasseRef           varchar(255)                      not null,
    salleClasseNombrePlace   int                               null,
    salleClasseProjecteur    tinyint(1)                        null,
    salleClasseDisponibilite enum ('unavailable', 'available') null,
    etablissementId          int                               not null,

    unique (salleClasseRef, etablissementId),
    foreign key (etablissementId) references etablissement (etablissementId)
);

create table donnee
(
    donneeId      int auto_increment
        primary key,
    horaireId     int null,
    jourId        int not null,
    creneauId     int not null,
    classeId      int not null,
    professeurId  int not null,
    salleClasseId int not null,
    matiereId     int not null,

    foreign key (classeId) references classe (classeId),
    foreign key (creneauId) references creneau (creneauId),
    foreign key (horaireId) references horaire (horaireId),
    foreign key (jourId) references jour (jourId),
    foreign key (matiereId) references matiere (matiereId),
    foreign key (professeurId) references professeur (professeurId),
    foreign key (salleClasseId) references salleClasse (salleClasseId)
);

create table professeur_salleClasse
(
    professeurSalleClasseId         int auto_increment
        primary key,
    professeurId                    int not null,
    salleClasseId                   int not null,
    professeurSalleClasseClassement int not null,

    unique (professeurId, salleClasseId),
    foreign key (professeurId) references professeur (professeurId),
    foreign key (salleClasseId) references salleClasse (salleClasseId)
);

create table salleClasse_matiere
(
    salleClasseMatiereId int auto_increment
        primary key,
    salleClasseId        int not null,
    matiereId            int not null,

    unique (salleClasseId, matiereId),
    foreign key (matiereId) references matiere (matiereId),
    foreign key (salleClasseId) references salleClasse (salleClasseId)
);

create table utilisateur
(
    utilisateurId         int auto_increment
        primary key,
    utilisateurEmail      varchar(255) not null,
    utilisateurNom        varchar(255) not null,
    utilisateurPrenom     varchar(255) not null,
    utilisateurMotDePasse varchar(255) not null,

    unique (utilisateurEmail)
);

create table utilisateur_etablissement
(
    utilisateurEtablissementId int auto_increment
        primary key,
    utilisateurId              int not null,
    etablissementId            int not null,

    unique (utilisateurId, etablissementId),
    foreign key (etablissementId) references etablissement (etablissementId),
    foreign key (utilisateurId) references utilisateur (utilisateurId)
);
