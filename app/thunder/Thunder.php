<?php
    /**
     * Thunder class
     * Classe principale de l'application
     */
    namespace Thunder;

    defined('CPATH') OR exit('Access Denied!');

    class Thunder {

        private $version = '1.0.0';


        public function db(){
            echo "\n\rdb function\n\r";
        }


        public function make($file, $mode = null, $classname = null){

            // Vérifie si le mode est défini
            if(empty($classname)) 
                die("\n\rPlease provide a name for the $mode\n\r");
            
            // Nettoie le nom de la classe
            $classname = preg_replace('/[^a-zA-Z0-9_]+/', '', $classname); 

            // Check si le nom de la classe commence par un chiffre ou un underscore
            if(preg_match('/^[^a-zA-Z_]+/', $classname)) 
                die("\n\rClassname must only contain letters, numbers and underscores\n\r");

            // Vérifie si le fichier existe déjà
            $filename = 'app' .DS. 'controllers' .DS. ucfirst($classname) .'.php';
            if(file_exists($filename)) 
                die("\n\r$filename already exists!\n\r");

            switch ($mode) {
                case 'make:controller':
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
                    echo "\n\rmodel function\n\r";
                    break;
                case 'make:migration':
                    echo "\n\rmigration function\n\r";
                    break;
                case 'make:seeder':
                    echo "\n\rseeder function\n\r";
                    break;
                default:
                    die("\n\rUnknown 'make' command");
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
