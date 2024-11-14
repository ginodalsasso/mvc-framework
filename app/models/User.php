<?php

    //$user = new Model\User;
    namespace Model;

    class User {

        use Model; // Utilisation du trait Model pour les méthodes de base de données

        // déterminer la table à utiliser
        protected $table = "users";
        protected $primaryKey = "id";

        // déterminer les colonnes autorisées à être modifiées
        protected $allowedColumns = [
            "email",
            "username",
            "password",
        ];

        // déterminer les règles de validation des données voir la méthode validate dans Model.php (l'ordre des règles est important)
        protected $validationRules = [
            "email" => [
                "email", 
                "unique" => "users",
                "required",
            ],
            "username" => [
                "alpha_numeric",
                "unique" => "users",
                "required",
            ],
            "password" => [
                "not_less_than_8_chars",
                "required",
            ]
        ];

        public function signup($data){

            if($this->validate($data)){
                
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT); // Hashage du mot de passe

                $this->insert($data); // Insertion dans la base de données
                redirect("login");
            }
        }

        public function login($data){

        }
    }
    