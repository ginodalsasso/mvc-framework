<?php

    Trait Database {

        // connection à la base de données
        private function connect() {
            $string = "mysql:hostname=". DBHOST .";dbname=". DBNAME;
            $con = new PDO($string, DBUSER, DBPASS);
            return $con;
        }

        // requête à la base de données
        public function query($query, $data = []) {
            $con = $this->connect();
            $stmt = $con->prepare($query);

            $check = $stmt->execute($data);
            // si la requête est un succès
            if($check) {
                $result = $stmt->fetchAll(PDO::FETCH_OBJ); 
                // si le résultat est un tableau et qu'il contient des données
                if(is_array($result) && count($result)) {
                    return $result;
                }
            }
            return false;
        }

        // récupérer une seule ligne de la base de données
        public function get_row($query, $data = []) {
            $con = $this->connect();
            $stmt = $con->prepare($query);

            $check = $stmt->execute($data);
            // si la requête est un succès
            if($check) {
                $result =  $stmt->fetchAll(PDO::FETCH_OBJ);
                // si le résultat est un tableau et qu'il contient des données
                if(is_array($check) && count($check)){
                    return $result[0]; // on retourne la première ligne
                }
            }
            return false;
        }
    }
