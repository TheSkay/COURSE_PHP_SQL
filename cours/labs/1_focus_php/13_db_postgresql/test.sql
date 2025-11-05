CREATE TABLE Eleve (
    idEleve                    SERIAL PRIMARY KEY ,
    firstNameEleve VARCHAR(50) NOT NULL           ,
    lastNameEleve  VARCHAR(50) NOT NULL           ,
    ageEleve       INT             NULL           ,
    hasAlternance  BOOLEAN     DEFAULT FALSE        
);