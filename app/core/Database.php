<?php

    namespace Model;

    defined('ROOTPATH') OR exit("Access Denied!");

    Trait Database {

        // connection à la base de données
        private function connect() {
            $string = "mysql:hostname=". DBHOST .";dbname=". DBNAME;
            $con = new \PDO($string, DBUSER, DBPASS);
            return $con;
        }


        /**
         * Insertion de données dans la base de données
         * @param string $table
         * @param array $data
         * @return bool
         */
        public function query($query, $data = []) {
            $con = $this->connect();
            $stmt = $con->prepare($query);
        
            try {
                $check = $stmt->execute($data);
                if ($check) {
                    $result = $stmt->fetchAll(\PDO::FETCH_OBJ); 
                    if (is_array($result) && count($result)) {
                        return $result;
                    }
                }
            } catch (\PDOException $e) {
                echo "SQL Error : " . $e->getMessage();
                echo "<br>Query : " . $query;
                echo "<br>Data : " . print_r($data, true);
                die();
            }
        
            return false;
        }

        /**
         * Récupère une ligne de résultat d'une requête SQL.
         *
         * @param string $query La requête SQL à exécuter.
         * @param array $data Les données à lier à la requête SQL (par défaut vide).
         * exemple : $data = [':id' => 1];
         * @return object|false Retourne un objet représentant la première ligne du résultat,
         *                      ou false si la requête échoue ou si aucun résultat n'est trouvé.
         */
        public function get_row($query, $data = []) {
            $con = $this->connect();
            $stmt = $con->prepare($query);

            $check = $stmt->execute($data);
            // si la requête est un succès
            if($check) {
                $result =  $stmt->fetchAll(\PDO::FETCH_OBJ);
                // si le résultat est un tableau et qu'il contient des données
                if(is_array($check) && count($check)){
                    return $result[0]; // on retourne la première ligne
                }
            }
            return false;
        }
    }
