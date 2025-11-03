## Création d'un nouveau projet Laravel

Utilise Composer pour créer un nouveau projet Laravel (ici on crée un projet `mon_projet`):

```bash
composer create-project laravel/laravel mon_projet
```

> Ce processus télécharge Laravel et toutes ses dépendances dans le dossier `mon_projet`.

## Structure du projet

Après l’installation, tu verras la structure suivante :

```
mon_projet/
├── app/          # Contient le code applicatif (controllers, models, etc.)
├── bootstrap/    # Bootstrap et configuration initiale
├── config/       # Fichiers de configuration
├── database/     # Migrations, seeds, factories
├── public/       # Point d’entrée web (index.php)
├── routes/       # Définition des routes
├── storage/      # Logs, fichiers générés
├── tests/        # Tests unitaires
└── artisan       # CLI de Laravel
```

## Tester le projet

Laravel fournit un serveur de développement intégré. Place-toi dans le dossier du projet et lance la commande :

```bash
cd mon_projet
php artisan serve --host=0.0.0.0 --port=8000
```

Tu verras un message du type :

```
Laravel development server started: http://127.0.0.1:8000
```

Tu pourras accéder au projet depuis ton navigateur à `http://localhost:8000`.

## Commandes Artisan utiles

Quelques commandes de base :

```bash
php artisan list          # Liste toutes les commandes Artisan
php artisan route:list    # Affiche toutes les routes définies
php artisan make:controller MonController   # Crée un nouveau controller
php artisan make:model MonModel -m          # Crée un modèle avec migration
php artisan migrate       # Exécute les migrations de la base
php artisan tinker        # Lance un REPL (Read-Eval-Print Loop) interactif
```

<u>Explore les routes définis par défaut !</u>

## Exercices

Ces exercices te guideront pas à pas pour créer un projet Laravel complet avec modèle, migration, contrôleur et routes.

### 1. Configurer la connection à la BDD 

Mettre à jour la section base de données de ton fichier `.env` pour que Laravel utilise le container PostgreSQL `univ_postgres` :

```env
DB_CONNECTION=pgsql
DB_HOST=univ_postgres
DB_PORT=5432
DB_DATABASE=laravel_db
DB_USERNAME=username
DB_PASSWORD=password
```

### 2. Créer un modèle `Etudiant` avec migration

```bash
php artisan make:model Etudiant -m
```

* `Etudiant` → modèle PHP qui représentera les étudiants.
* `-m` → crée également une **migration** pour la table `etudiants`.

La migration se trouve dans `database/migrations/` et doit ressembler à ceci :

```php
public function up(): void
{
    Schema::create('etudiants', function (Blueprint $table) {
        $table->id();
        $table->string('nom');
        $table->integer('age');
        $table->timestamps(); // created_at et updated_at
    });
}

public function down(): void
{
    Schema::dropIfExists('etudiants');
}
```

* `up()` → applique la migration (crée la table).
* `down()` → annule la migration (supprime la table).

**Appliquer la migration :**

```bash
php artisan migrate
```

**Annuler la migration (rollback) :**

```bash
php artisan migrate:rollback
```

### 3. Créer un contrôleur `EtudiantController`

```bash
php artisan make:controller EtudiantController
```

Dans `app/Http/Controllers/EtudiantController.php` :

```php
<?php

namespace App\Http\Controllers;

use App\Models\Etudiant;

class EtudiantController extends Controller
{
    public function index()
    {
        return response()->json(Etudiant::all());
    }
}
```

### 4. Ajouter une route `/etudiants`

Dans `routes/web.php` (ou `routes/api.php`) :

```php
use App\Http\Controllers\EtudiantController;

Route::get('/etudiants', [EtudiantController::class, 'index']);
```

### 5. Mettre à jour le model

Mise à jour du fichier `Etudiant.php` dans le dossier `Models` :

```php

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    protected $fillable = ['nom', 'age'];
}
```

### 6. Tester l’application

Démarrer le serveur Laravel :

```bash
php artisan serve --host=0.0.0.0 --port=8000
```

Accéder à la route :

* Dans le navigateur : `http://127.0.0.1:8000/etudiants`

### 7. Bonus : ajouter des étudiants manuellement

Depuis `tinker` :

```bash
php artisan tinker
```

```php
\App\Models\Etudiant::create(['nom' => 'Alice', 'age' => 25]);
\App\Models\Etudiant::create(['nom' => 'Bob', 'age' => 22]);
```

Puis actualise la route `/etudiants` dans ton navigateur pour voir les données JSON.