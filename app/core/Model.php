<?php 

    Trait Model {

        use Database; // on utilise le trait Database

        protected $limit = 10;
        protected $offset = 0;
        protected $order_type = "desc";
        protected $order_column = "id";

        public function findAll() {

            $query = "SELECT * FROM $this->table 
                        ORDER BY $this->order_column $this->order_type 
                        LIMIT $this->limit 
                        OFFSET $this->offset"; 

            return $this->query($query);
        }


        // requête SELECT sur la table spécifiée avec des conditions WHERE.
        public function where($data, $data_not = []) {

            $keys = array_keys($data);
            $keys_not = array_keys($data_not);

            $query= "SELECT * FROM $this->table WHERE ";

            // pour chaque clé dans le tableau de données
            foreach($keys as $key) {
                $query .= $key . " = :" . $key . " && ";
            }

            foreach($keys_not as $key) {
                $query .= $key . " != :" . $key . " && ";
            }

            $query = trim($query, " && "); // on enlève le dernier "&&"
            $query .= " ORDER BY $this->order_column $this->order_type 
                        LIMIT $this->limit 
                        OFFSET $this->offset";

            $data = array_merge($data, $data_not); // on fusionne les deux tableaux de données

            return $this->query($query, $data);
        }


        // requête SELECT sur la table spécifiée avec des conditions WHERE et LIMIT.
        public function first($data, $data_not = []) {

            $keys = array_keys($data);
            $keys_not = array_keys($data_not);

            $query= "SELECT * FROM $this->table WHERE ";

            // pour chaque clé dans le tableau de données
            foreach($keys as $key) {
                $query .= $key . " = :" . $key . " && ";
            }

            foreach($keys_not as $key) {
                $query .= $key . " != :" . $key . " && ";
            }

            $query = trim($query, " && "); // on enlève le dernier "&&"
            $query .= " LIMIT $this->limit 
                        OFFSET $this->offset"; 

            $data = array_merge($data, $data_not); // on fusionne les deux tableaux de données

            $result = $this->query($query, $data);

            if ($result) 
                return $result[0];
            
            return false;
        }


        public function insert($data) {

            $keys = array_keys($data);

            $query= "INSERT INTO $this->table (".implode(",", $keys).") 
                        VALUES (:".implode(",:", $keys).")"; //VALUES (:name, :date)

            $this->query($query, $data);
            
            return false;
        }


        public function update($id, $data, $id_column = 'id') {

            $keys = array_keys($data);
            $query= "UPDATE $this->table SET ";

            // pour chaque clé dans le tableau de données
            foreach($keys as $key) {
                $query .= $key . " = :" . $key . ", ";
            }

            $query = trim($query, ", "); // on enlève le dernier ", "

            $query .= " WHERE $id_column = :$id_column"; // on ajoute la condition WHERE
            
            $data[$id_column] = $id;

            $this->query($query, $data);

            return false;
        }


        public function delete($id, $id_column = 'id') {

            $data[$id_column] = $id;

            $query= "DELETE FROM $this->table 
                        WHERE $id_column = :$id_column";

            $this->query($query, $data);

            return false;
        }
    }