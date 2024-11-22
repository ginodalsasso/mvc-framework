<?php
    /**
     * Migration class
     */
    namespace Thunder;

    defined('CPATH') OR exit('Access Denied!');

    class Migration {
    
        use \Model\Database;

        protected $columns      = [];
        protected $keys         = [];
        protected $primaryKeys  = []; 
        protected $foreignKeys  = []; 
        protected $uniqueKeys   = [];
        protected $data         = [];
        

        protected function createTable($table) {
            if(!empty($this->columns)){

                $query = 'CREATE TABLE IF NOT EXISTS '.$table.' (';
                
                foreach($this->columns as $column) {
                    $query .= $column.',';
                }

                foreach($this->primaryKeys as $key) {
                    $query .= 'PRIMARY KEY ('.$key.'),';
                }

                foreach($this->foreignKeys as $key) {
                    $query .= 'FOREIGN KEY ('.$key.'), ';
                }

                foreach($this->uniqueKeys as $key) {
                    $query .= 'UNIQUE KEY ('.$key.'),';
                }

                foreach($this->keys as $key) {
                    $query .= 'KEY ('.$key.'),';
                }

                $query = trim($query,",");
                $query .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

                $this->query($query);

                // Remise à zéro des valeures
                $this->columns      = [];
                $this->keys         = [];
                $this->primaryKeys  = []; 
                $this->foreignKeys  = []; 
                $this->uniqueKeys   = [];

                echo "\n\r Table ".$table." successfully created \n\r";
            } else {
                echo "\n\r No columns to create table ".$table." \n\r";
            }
        }

        
        protected function addColumn($text) {
            $this->columns[] = $text; 
        }


        protected function addPrimaryKeys($key) {
            $this->primaryKeys[] = $key; 
        }


        protected function addUniqueKeys($key) {
            $this->uniqueKeys[] = $key; 
        }


        protected function addData($key, $value) {
            $this->data[$key] = $value; 
        }


        protected function dropTable($table) {
            $this->query('DROP TABLE IF EXISTS '.$table); 
            echo "\n\r Table ".$table." successfully dropped \n\r";
        }
        

        /**
         * Insère des données dans la table spécifiée.
         * ex: $this->insertData('users');
         * @param string $table Le nom de la table dans laquelle les données doivent être insérées.
         * @return void
         */
        protected function insertData($table) {
            if(!empty($this->data)){
                $keys = array_keys($this->data);
                $query = 'INSERT INTO '.$table.' ('.implode(",", $keys) . ") VALUES (:" . implode(",:", $keys) . ")";
    
                $this->query($query, $this->data);
                // Remise à zéro des valeures
                $this->data   = [];
    
                echo "\n\r Data successfully inserted into ".$table." \n\r";
            } else {
                echo "\n\r No data to insert into ".$table." \n\r";
            }
        }
    }