<?php
    /**
     * Thunder class
     * Classe principale de l'application
     */
    namespace Thunder;

    defined('CPATH') OR exit('Access Denied!');

    class Thunder {
        public function make(){
            echo "\n\rmake function\n\r";
        }

        public function help(){
            echo "
                Thunder v$version Commande Line Tool

                Database 
                    db:create          Create a new database schema.
                    db:seed            Seed the database with records.
                    db:table           Retrieves information on the selected table.
                    db:drop            Drop/Delete the database schema.
                    migrate            Locate and runs a migration from the specifed plugin folder.
                    migrate:refresh    Does a rollback followed by a latest to refresh the current state of the database.
                    migrate:rollback   Runs the 'down'  method for a migration in the specified plugin folder.
                    
                Generators
                    make:migration    Create a new migration file.
                    make:model        Create a new model file.
                    make:seeder       Create a new seeder file.
            ";
        }
    }
