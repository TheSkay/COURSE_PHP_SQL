<?php
// Exemple de classes, objets et héritage

class Etudiant {
    protected $nom;
    protected $age;

    // Constructeur
    public function __construct($nom, $age) {
        $this->nom = $nom;
        $this->age = $age;
    }

    // Méthode
    public function sePresenter() {
        echo "Bonjour, je m'appelle $this->nom et j'ai $this->age ans.\n";
    }
}

// Classe qui hérite de Etudiant
class EtudiantInternational extends Etudiant {
    private $pays;

    // Constructeur : on appelle le constructeur parent pour nom et age
    public function __construct($nom, $age, $pays) {
        parent::__construct($nom, $age); // Appel du constructeur de la classe parente
        $this->pays = $pays;             // On ajoute l'attribut spécifique
    }

    // Méthode : surcharge de sePresenter pour inclure le pays
    public function sePresenter() {
        // parent::sePresenter(); // Appelle la présentation de base
        echo "Bonjour, je m'appelle $this->nom, j'ai $this->age ans et je viens de $this->pays." . PHP_EOL;
    }
}

// Création d'objets
$etudiant1 = new Etudiant("Alice", 25);
$etudiant1->sePresenter();

$etudiant2 = new EtudiantInternational("Bob", 22, "Canada");
$etudiant2->sePresenter();
