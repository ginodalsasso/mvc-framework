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

        public function validate($data){
            $this->errors = [];

            if(empty($data['email'])){
                $this->errors['email'] = "email is required";
            } else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                $this->errors['email'] = "email is not valid";
            }

            if(empty($data['password'])){
                $this->errors['password'] = "password is required";
            } 

            if(empty($data['terms'])){
                $this->errors['terms'] = "please accept terms and conditions";
            } 

            if(empty($this->errors))
                return true;
            

            return false;
        }

    }
    