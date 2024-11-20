<?php
    /**
     * Thunder class
     * Classe principale de l'application
     */
    namespace Thunder;

    defined('CPATH') OR exit('Access Denied!');

    class Thunder {

        private $version = '1.0.0';


        public function db($argv){

            $mode =         $argv[1] ?? null; // Récupère le mode
            $param1 =    $argv[2] ?? null; // Récupère le nom de la classe

            switch ($mode) {
                case 'db:create':
                    // Vérifie si param1 est défini
                    if(empty($param1)) 
                        die("\n\rPlease provide a database name\n\r");
                    
                    $db = new \Model\Database; // Crée une nouvelle instance de la classe Database
                    $query = "CREATE DATABASE IF NOT EXISTS" . $param1; // Crée la base de données
                    $db->query($query);

                    die ("\n\rDatabase created successfully!\n\r");
                    break;

                case 'db:table':
                    // Vérifie si param1 est défini
                    if(empty($param1)) 
                        die("\n\rPlease provide a table name\n\r");
                    
                    $db = new \Model\Database; // Crée une nouvelle instance de la classe Database
                    $query = "DESCRIBE" . $param1; // Supprime la base de données
                    $result = $db->query($query);

                    if($result){
                        
                        print_r($result); // Affiche les informations sur la table
                    } else {
                        echo "\n\rTable $param1 not found!\n\r";
                    }

                    die ();
                    break;

                case 'db:drop':
                    // Vérifie si param1 est défini
                    if(empty($param1)) 
                        die("\n\rPlease provide a database name\n\r");
                    
                    $db = new \Model\Database; // Crée une nouvelle instance de la classe Database
                    $query = "DROP DATABASE" . $param1; // Supprime la base de données
                    $db->query($query);

                    die ("\n\rDatabase deleted successfully!\n\r");
                    break;

                case 'db:seed':
                    echo "\n\rseeder function\n\r";
                    break;
                    
                default:
                    die("\n\rUnknown command $argv[1]");
                    break;
            }
        }


        public function make($argv){

            $mode =         $argv[1] ?? null; // Récupère le mode
            $classname =    $argv[2] ?? null; // Récupère le nom de la classe

            // Vérifie si le mode est défini
            if(empty($classname)) 
                die("\n\rPlease provide a name for the $mode\n\r");
            
            // Nettoie le nom de la classe
            $classname = preg_replace('/[^a-zA-Z0-9_]+/', '', $classname); 

            // Check si le nom de la classe commence par un chiffre ou un underscore
            if(preg_match('/^[^a-zA-Z_]+/', $classname)) 
                die("\n\rClassname must only contain letters, numbers and underscores\n\r");

            switch ($mode) {
                case 'make:controller':
                    // Vérifie si le fichier existe déjà
                    $filename = 'app' .DS. 'controllers' .DS. ucfirst($classname) .'.php';
                    if(file_exists($filename)) 
                        die("\n\r$filename already exists!\n\r");

                    $sample_file = file_get_contents('app' .DS. 'thunder' .DS. 'samples' .DS. 'controller-sample.php'); // Lire le contenu du controller
                    $sample_file = preg_replace('/\{CLASSNAME\}/', ucfirst($classname), $sample_file); // Remplace {CLASSNAME} par le nom de la classe
                    $sample_file = preg_replace('/\{classname\}/', strtolower($classname), $sample_file); // Remplace {classname} par le nom de la classe

                    if(file_put_contents($filename, $sample_file)){  // Crée le fichier du contrôleur
                        die ("\n\r$classname created successfully!\n\r");
                    } else {
                        die ("\n\rAn error occured while creating $classname\n\r");
                    };
                    break;

                case 'make:model':
                    // Vérifie si le fichier existe déjà
                    $filename = 'app' .DS. 'models' .DS. ucfirst($classname) .'.php';
                    if(file_exists($filename)) 
                        die("\n\r$filename already exists!\n\r");
                    
                    $sample_file = file_get_contents('app' .DS. 'thunder' .DS. 'samples' .DS. 'model-sample.php'); // Lire le contenu du model
                    $sample_file = preg_replace('/\{CLASSNAME\}/', ucfirst($classname), $sample_file); // Remplace {CLASSNAME} par le nom de la classe
                    
                    if(!preg_match('/s$/', $classname)) // Ajoute un 's' à la fin du nom de la table si ce n'est pas déjà le cas
                        $sample_file = preg_replace('/\{table\}/', strtolower($classname) .'s', $sample_file);
                    
                    if(file_put_contents($filename, $sample_file)){  // Crée le fichier du modèle
                        die ("\n\r$classname created successfully!\n\r");
                    } else {
                        die ("\n\rAn error occured while creating $classname\n\r");
                    };
                    break;

                case 'make:migration':
                    echo "\n\rmigration function\n\r";
                    break;

                case 'make:seeder':
                    echo "\n\rseeder function\n\r";
                    break;
                    
                default:
                    die("\n\rUnknown command $argv[1]");
                    break;
            }
        }


        public function migrate(){
            echo "\n\rmigrate function\n\r";
        }


        public function help(){
            echo "
                Thunder v$this->version Commande Line Tool

                Database 
                    db:create          Create a new database schema.
                    db:seed            Seed the database with records.
                    db:table           Retrieves information on the selected table.
                    db:drop            Drop/Delete the database schema.
                    migrate            Locate and runs a migration from the specifed plugin folder.
                    migrate:refresh    Does a rollback followed by a latest to refresh the current state of the database.
                    migrate:rollback   Runs the 'down'  method for a migration in the specified plugin folder.
                    
                Generators
                    make:controller    Create a new controller file.
                    make:model         Create a new model file.
                    make:migration     Create a new migration file.
                    make:seeder        Create a new seeder file.
            ";
        }
    }
