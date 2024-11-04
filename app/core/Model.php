<?php 
    defined('ROOTPATH') OR exit("Access Denied!");

// Définition du trait Model qui contient des méthodes pour interagir avec la base de données.
Trait Model {

    use Database; // Utilisation du trait Database pour gérer la connexion à la base de données

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

    // Méthode pour récupérer des enregistrements avec des conditions WHERE
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

        // SELECT * 
        // FROM users 
        // WHERE email = :email AND status != :status 
        // LIMIT 1 OFFSET 0;
    // Méthode pour récupérer un seul enregistrement avec des conditions WHERE et LIMIT
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
        // Ajout de la limite de résultat (1 seul enregistrement) pour la méthode 'first'
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

    // Méthode pour insérer un nouvel enregistrement dans la table
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
        $keys = array_keys($data);

        // requête SQL INSERT
        $query= "INSERT INTO $this->table (".implode(",", $keys).") 
                    VALUES (:".implode(",:", $keys).")"; // VALUES (:name, :date)

        // Exécution de la requête
        $this->query($query, $data);
        
        return false;
    }

    // Méthode pour mettre à jour un enregistrement existant
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

    // Méthode pour supprimer un enregistrement
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
}
