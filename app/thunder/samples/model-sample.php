<?php

    //$user = new Model\{CLASSNAME};
    namespace Model;

    defined('ROOTPATH') OR exit('Access Denied!');

    class {CLASSNAME} {

        use Model; // Utilisation du trait Model pour les méthodes de base de données

        // déterminer la table à utiliser
        protected $table = "{table}";
        protected $primaryKey = "id";
        protected $loginUniqueColumn = "email"; // colonne unique pour la connexion

        // déterminer les colonnes autorisées à être modifiées
        protected $allowedColumns = [
            "email",
            "username",
            "password",
        ];


        // Règles de validation lors d'un insert des données voir la méthode validate dans Model.php (l'ordre des règles est important)
        protected $onInsertValidationRules = [
            "email" => [
                "email", 
                "required",
            ],
            "username" => [
                "alpha_numeric",
                "unique",
                "required",
            ],
            "password" => [
                "not_less_than_8_chars",
                // "password_regex",
                "required",
            ]
        ];

        // Règles de validation lors d'un update
        protected $onUpdateValidationRules = [
            "email" => [
                "email", 
                "required",
            ],
            "username" => [
                "alpha_numeric",
                "unique",
                "required",
            ],
            "password" => [
                "not_less_than_8_chars",
                // "password_regex",
                "required",
            ]
        ];
        
    }