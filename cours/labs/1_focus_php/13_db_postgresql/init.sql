-- Connexion à la base "exemple_db"
\c exemple_db;

-- Création d'une table "etudiants"
CREATE TABLE etudiants (
    id                         SERIAL PRIMARY KEY,
    nom   VARCHAR(50) NOT NULL                   ,
    age   INT         NOT NULL                   ,
    ville VARCHAR(50)
);

-- Insertion de quelques données d'exemple
INSERT INTO etudiants (nom, age, ville) VALUES
('Alice', 25, 'Paris'),
('Bob', 22, 'Lyon'),
('Charlie', 28, 'Marseille'),
('Karim', 14, 'Nantes');
