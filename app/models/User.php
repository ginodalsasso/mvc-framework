<?php

    class User {

        use Model;

        // déterminer la table à utiliser
        protected $table = "users";

        // déterminer les colonnes autorisées à être modifiées
        protected $allowedColumns = [
            "name"
        ];

    }
    