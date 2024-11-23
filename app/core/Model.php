<?php 

    namespace Model;

    defined('ROOTPATH') OR exit("Access Denied!");

    // Définition du trait Model qui contient des méthodes pour interagir avec la base de données.
    Trait Model {

        use Database; // Utilisation de Database pour gérer la connexion à la base de données

        // Propriétés par défaut pour la pagination et l'ordre des résultats
        protected $limit        = 10; 
        protected $offset       = 0; // Décalage pour la pagination
        protected $order_type   = "desc"; 
        protected $order_column = "id"; // Colonne par défaut pour trier les résultats
        public $errors       = []; // Tableau pour stocker les erreurs de validation


        // Méthode pour récupérer tous les enregistrements de la table
        public function findAll() {
            // requête SQL pour récupérer tous les enregistrements
            $query = "SELECT * FROM $this->table 
                        ORDER BY $this->order_column $this->order_type 
                        LIMIT $this->limit 
                        OFFSET $this->offset"; 

            // Exécution de la requête
            return $this->query($query);
        }


        /**
         * Trouve toutes les entrées de la table correspondant aux conditions spécifiées.
         *
         * @param array $data Conditions pour lesquelles les entrées doivent correspondre (key = value).
         * @param array $data_not Conditions pour lesquelles les entrées ne doivent pas correspondre (key != value).
         * @return array Résultats de la requête.
         */
        public function findAllBy($data, $data_not = []) {

            // Récupération des clés des conditions 'data' et 'data_not'
            $keys = array_keys($data);
            $keys_not = array_keys($data_not);

            // requête SQL de base
            $query = "SELECT * FROM $this->table WHERE ";

            // Ajout des conditions 'key = value' pour chaque élément de 'data'
            foreach($keys as $key) {
                $query .= $key . " = :" . $key . " AND ";
            }

            // Ajout des conditions 'key != value' pour chaque élément de 'data_not'
            foreach($keys_not as $key) {
                $query .= $key . " != :" . $key . " AND ";
            }

            // Suppression du dernier 'AND' superflu de la requête
            $query = trim($query, " AND "); 
            // Ajout de l'ordre et de la limite de résultats
            $query .= " ORDER BY $this->order_column $this->order_type 
                        LIMIT $this->limit 
                        OFFSET $this->offset";

            // Fusion des tableaux de conditions pour l'exécution de la requête
            $data = array_merge($data, $data_not); 

            // Exécution de la requête et retour du résultat
            return $this->query($query, $data);
        }


        /**
         * Trouve un enregistrement dans la table en fonction des conditions spécifiées.
         *
         * @param array $data Conditions pour lesquelles les valeurs doivent correspondre (key = value).
         * @param array $data_not Conditions pour lesquelles les valeurs ne doivent pas correspondre (key != value).
         * ex: $user->findOneBy(['email' => 'gino@ex.com']);
         * @return array|false Retourne le premier enregistrement correspondant aux conditions, ou 'false' si aucun enregistrement n'est trouvé.
         */
        public function findOneBy($data, $data_not = []) {
            // Récupération des clés des conditions 'data' et 'data_not'
            $keys = array_keys($data);
            $keys_not = array_keys($data_not);

            // requête SQL de base
            $query= "SELECT * FROM $this->table WHERE ";

            // Ajout des conditions 'key = value' pour chaque élément de 'data'
            foreach($keys as $key) {
                $query .= $key . " = :" . $key . " AND ";
            }
            // Ajout des conditions 'key != value' pour chaque élément de 'data_not'
            foreach($keys_not as $key) {
                $query .= $key . " != :" . $key . " AND ";
            }

            // Suppression du dernier 'AND' superflu de la requête
            $query = trim($query, " AND ");
            // Ajout de la limite de résultat (1 seul enregistrement) pour la méthode 'findoneby'
            $query .= " LIMIT $this->limit 
                        OFFSET $this->offset"; 

            // Fusion des tableaux de conditions pour l'exécution de la requête
            $data = array_merge($data, $data_not); 

            // Exécution de la requête
            $result = $this->query($query, $data);

            // Retourne le premier enregistrement s'il existe, sinon retourne 'false'
            if ($result) 
                return $result[0];
            
            return false;
        }


        /**
         * Insère des données dans la table de la base de données associée à ce modèle.
         * 
         * @param array $data Tableau associatif contenant les données à insérer.
         *                    Les clés du tableau doivent correspondre aux noms des colonnes de la table.
         * ex: $user->insert(['name' => 'John Doe', 'date' => '2021-01-01']);
         * @return bool Retourne false après l'exécution de la requête.
         */        
        public function insert($data) {
            
            // Vérifie si la propriété allowedColumns est définie et non vide
            if (!empty($this->allowedColumns)) {
                // Parcourt chaque clé (colonne) et valeur du tableau $data
                foreach ($data as $key => $value) {
                    // Si la clé n'est pas dans allowedColumns, elle est supprimée de $data
                    if (!in_array($key, $this->allowedColumns)) {
                        unset($data[$key]);
                    }
                }
            }

            // Récupération des clés du tableau de données
            $keys = array_keys($data); // $keys = ['name', 'date']

            // requête SQL INSERT
            $query = "INSERT INTO $this->table (".implode(",", $keys).") 
                        VALUES (:".implode(",:", $keys).")";


            // Exécution de la requête
            $this->query($query, $data);
            
            return false;
        }


        /**
         * Méthode pour mettre à jour un enregistrement existant dans la base de données.
         *
         * @param int|string $id L'identifiant de l'enregistrement à mettre à jour.
         * @param array $data Les données à mettre à jour sous forme de tableau associatif (colonne => valeur).
         * @param string $id_column Le nom de la colonne de l'identifiant (par défaut 'id').
         * ex: $user->update(1, ['name' => 'John Doe', 'email' => '
         * @return bool Retourne false après l'exécution de la requête.
         */
        public function update($id, $data, $id_column = 'id') {
            
            // Vérifie si la propriété allowedColumns est définie et non vide
            if (!empty($this->allowedColumns)) {
                // Parcourt chaque clé (colonne) et valeur du tableau $data
                foreach ($data as $key => $value) {
                    // Si la clé n'est pas dans allowedColumns, elle est supprimée de $data
                    if (!in_array($key, $this->allowedColumns)) {
                        unset($data[$key]);
                    }
                }
            }

            // Récupération des clés du tableau de données
            $keys = array_keys($data);
            $query= "UPDATE $this->table SET ";

            // Construction de la requête SQL UPDATE pour chaque champ à mettre à jour
            foreach($keys as $key) {
                $query .= $key . " = :" . $key . ", ";
            }

            // Suppression de la dernière virgule de la requête
            $query = trim($query, ", "); 

            // Ajout de la condition WHERE pour identifier l'enregistrement à mettre à jour
            $query .= " WHERE $id_column = :$id_column"; 
            
            // Ajout de l'identifiant de l'enregistrement aux données
            $data[$id_column] = $id;

            // Exécution de la requête
            $this->query($query, $data);

            return false;
        }


        /**
         * Supprime un enregistrement de la base de données.
         *
         * @param int|string $id L'identifiant de l'enregistrement à supprimer.
         * @param string $id_column Le nom de la colonne de l'identifiant (par défaut 'id').
         * ex: $user->delete(1);
         * @return bool Retourne false après l'exécution de la requête.
         */
        public function delete($id, $id_column = 'id') {

            // Création de la condition de suppression
            $data[$id_column] = $id;

            // Construction de la requête SQL DELETE
            $query = "DELETE FROM $this->table 
                        WHERE $id_column = :$id_column";

            // Exécution de la requête
            $this->query($query, $data);

            return false;
        }


        // Retourne les erreurs de validation
        public function getError($key) {

            if(!empty($this->errors[$key])){
                return $this->errors[$key];
            }
            return "";
        }


        // Retourn "id" par défaut si la clé primaire n'est pas définie dans le modèle
        protected function getPrimaryKey() {
            return $this->primaryKey ?? "id";
        }

        /**
         * Valide les données en fonction des règles de validation définies dans le modèle.
         *
         * @param array $data Données à valider sous forme de tableau associatif (colonne => valeur).
         * @return bool Retourne true si les données sont valides, sinon false.
         */
        public function validate($data){

            $this->errors = [];

            // Vérifie si les règles de validation sont définies 
            if(!empty($this->primaryKey) && !empty($data[$this->primaryKey])) {
                // Si la clé primaire est définie, utilisez les règles d'update
                $validationRules = $this->onUpdateValidationRules;
            } else {

                $validationRules = $this->onInsertValidationRules;
            }
            
            if(!empty($validationRules)){
                foreach($validationRules as $column => $rules){ // Pour chaque $column = email, username, password...
                    
                    if (!isset($data[$column]))
                         continue; // Passe au champ suivant si la clé n'est pas définie
                    
                    foreach ($rules as $rule) { // $rule = required, email, unique, min, max, regex...

                        switch ($rule) { // Vérification de chaque règle
                            case 'email':
                                if(!filter_var($data[$column], FILTER_VALIDATE_EMAIL)){
                                    $this->errors[$column] = "Invalid email address";
                                }
                                break;
                            case 'alpha_numeric':
                                if(!ctype_alnum($data[$column])){ // Vérifie si la chaîne contient uniquement des caractères alphanumériques
                                    $this->errors[$column] = ucfirst($column) . " must be alphabet or numeric"; 
                                }
                                break;
                            case 'alpha':
                                if(!preg_match("/^[a-zA-Z ]*$/", $data[$column])){ // Vérifie si la chaîne contient uniquement des lettres
                                    $this->errors[$column] = ucfirst($column) . " must be alphabet"; 
                                }
                                break;
                            case 'alpha_symbol':
                                if(!preg_match("/^[a-zA-Z0-9 ]*$/", $data[$column])){ // Vérifie si la chaîne contient uniquement des lettres et des chiffres
                                    $this->errors[$column] = ucfirst($column) . " must be alphabet or numeric"; 
                                }
                                break;
                            case 'not_less_than_8_chars':
                                if(strlen($data[$column]) < 8){ // Vérifie si la chaîne contient au moins 8 caractères
                                    $this->errors[$column] = ucfirst($column) . " must be at least 8 characters"; 
                                }
                                break;
                            case 'password_regex':
                                if(!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/", $data[$column])){ // Vérifie si la chaîne contient au moins 8 caractères, une lettre minuscule, une lettre majuscule et un chiffre
                                    $this->errors[$column] = ucfirst($column) . " must contain at least 8 characters, one lowercase letter, one uppercase letter and one number"; 
                                }
                                break;
                            case 'unique':
                                $key = $this->getPrimaryKey();
                                if(!empty($data[$key])) {
                                    // edit mode 
                                    if($this->findOneBy([$column => $data[$column]], [$key => $data[$key]])){ // Vérifie si la valeur existe déjà dans la base de données et si l'id est différent
                                        $this->errors[$column] = ucfirst($column) . " already exists"; 
                                    }
                                }else{ // Si l'identifiant n'est pas défini, on vérifie si la valeur est unique
                                    // insert mode
                                    if($this->findOneBy([$column => $data[$column]])){ // Vérifie si la valeur existe déjà dans la base de données
                                        $this->errors[$column] = ucfirst($column) . " already exists"; 
                                    }
                                }
                                break;
                            case 'required':
                                if(empty($data[$column])){
                                    $this->errors[$column] = ucfirst($column) . " is required";
                                }
                                break;
                            default:
                                $this->errors["rule"] = "Invalid rule: " . $rule;
                                break;
                        }
                    }
                }
            }
            if(empty($this->errors)){
                return true;
            }

            return false;
        }
    }
