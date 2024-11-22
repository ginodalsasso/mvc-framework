<?php
    /**
     * Migration class
     * Classe principale de l'application
     */
    namespace Migration;

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
            $query = 'CREATE TABLE IF NOT EXISTS '.$table.' (';
            
            foreach($this->columns as $column) {
                $query .= $column.', ';
            }

            foreach($this->primaryKeys as $key) {
                $query .= 'PRIMARY KEY ('.$key.'), ';
            }

            foreach($this->foreignKeys as $key) {
                $query .= 'FOREIGN KEY ('.$key.'), ';
            }

            foreach($this->uniqueKeys as $key) {
                $query .= 'UNIQUE KEY ('.$key.'), ';
            }

            foreach($this->keys as $key) {
                $query .= 'KEY ('.$key.'), ';
            }

            $query = trim($query,",");
            $query .= ") ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

            $this->query($query);
            echo "\n\r Table ".$table." successfully created \n\r";
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

    }