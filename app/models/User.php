<?php

    class User {

        use Model;

        // déterminer la table à utiliser
        protected $table = "users";

        // déterminer les colonnes autorisées à être modifiées
        protected $allowedColumns = [
            "email",
            "password"
        ];

        public function validate($data) {
            $this->errors = [];
    
            // Validation de l'email
            $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
            if (!$email) {
                $this->errors['email'] = "email is not valid";
            }
    
            // Validation du mot de passe
            $password = $data['password'];
            $password_regex = "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/";
            
            if (empty($password)) {
                $this->errors['password'] = "password is required";
            } elseif (!preg_match($password_regex, $password)) {
                $this->errors['password'] = "password must contain at least 8 characters, 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special character";
            }
    
            // Validation de la confirmation du mot de passe
            $password_confirm = $data['password_confirm'];
            if (empty($password_confirm)) {
                $this->errors['password_confirm'] = "password confirm is required";
            } elseif ($password !== $password_confirm) {
                $this->errors['password_confirm'] = "passwords do not match";
            }
    
            // Validation des conditions d'utilisation
            if (empty($data['terms']) || $data['terms'] != '1') {
                $this->errors['terms'] = "please accept terms and conditions";
            }
    
            // Retourner true si aucune erreur, sinon false
            return empty($this->errors);
        }

    }
    