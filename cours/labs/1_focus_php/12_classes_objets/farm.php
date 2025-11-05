<?php

    // - Création d'une classe animal (nom, age, date d'achat)
    // - Création d'une classe chien (ouafOuaf() + race + robe)
    // - Création d'une classe vache (meuhMeuh() + couleur + meilleur cuisson)

    // -> SONORITE : Prénom, Age, Race, Robe
    // -> SONORITE : Prénom, Age, Couleur, La cuisson 

    abstract class Animal {
        protected $nom;
        protected $age;
        private   $dateAchat;

        public function __construct($nom, $age, $dateAchat) {
            $this->nom = $nom;
            $this->age = $age;
            $this->dateAchat = $dateAchat;
        }

        final public function getDateAchat(){
            return $this->dateAchat;
        }

        public function sayHello(){
            echo "Salut !\n\n" ;
        }
    }

    abstract class Chien extends Animal {
        protected $race;
        protected $robe;

        public function __construct($nom, $age, $dateAchat, $race, $robe) {
            parent::__construct($nom, $age, $dateAchat);
            $this->race = $race ;
            $this->robe = $robe ;
        }

        public function sePresenter(){
            echo "Ouaf Ouaf, je m'appelle $this->nom et je suis un chien avec une robe $this->robe." . PHP_EOL ;
            echo "J'ai $this->age an" . ( $this->age > 1 ? "s" : "" ) . ", j'ai été acceuilli " . parent::getDateAchat() . PHP_EOL . PHP_EOL ;
        }

        public abstract function getRace();
    }

    class Chiot extends Chien {

        protected $race ;

        public function __construct($nom, $age, $dateAchat, $race, $robe) {
            parent::__construct($nom, $age, $dateAchat, $race, $robe);
            $this->race = $race ;
        }

        public final function sePresenter(){
            echo "Ouaf Ouaf, je m'appelle $this->nom et je suis un chien avec une robe $this->robe." . PHP_EOL ;
            echo "J'ai $this->age an" . ( $this->age > 1 ? "s" : "" ) . ", j'ai été acceuilli " . parent::getDateAchat() . PHP_EOL . PHP_EOL ;
        }

        public function getRace(){
            return $this->race;
        }
    }

    class Vache extends Animal {
        protected $couleur;
        protected $grilleMeilleurCuisson;

        public function __construct($nom, $age, $dateAchat, $couleur, $grille) {
            parent::__construct($nom, $age, $dateAchat);
            $this->couleur = $couleur ;
            $this->grilleMeilleurCuisson = $grille ;
        }

        public final function sePresenter(){
            echo "Meuuuuh Meuuuuh, je m'appelle $this->nom et je suis une vache $this->couleur." . PHP_EOL ;
            echo "J'ai $this->age an" . ( $this->age > 1 ? "s" : "" ) . ", j'ai été acheté le " . parent::getDateAchat() . "." . PHP_EOL ;
            echo "Je sens que je vais être mangé " . $this->grilleMeilleurCuisson . "." . PHP_EOL . PHP_EOL ;
        }
    }

    // $animalExemple = new Animal( "EElld", 9, "10/11/2020");
    // $animalExemple->sayHello();

    $futurWaguyDeQualite = new Vache( "Clémentine", 1, "10/11/2025", "rousse", "Sashimi");
    $futurWaguyDeQualite->sePresenter();

    // $chienDeLaBergerie = new Chien( "fox", 5, "vers novembre 2025", "Pitbull red noise", "tacheté blanc / noir");
    // $chienDeLaBergerie->sePresenter();

    $chienDeLaBergerie = new Chiot( "rex", 5, "vers novembre 2025", "Pekinoi", "noir");
    $chienDeLaBergerie->sePresenter();