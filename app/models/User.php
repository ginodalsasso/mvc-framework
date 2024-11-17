<?php

    //$user = new Model\User;
    namespace Model;

    class User {

        use Model; // Utilisation du trait Model pour les méthodes de base de données

        // déterminer la table à utiliser
        protected $table = "users";
        protected $primaryKey = "id";
        protected $loginUniqueColumn = "email"; // colonne unique pour la connexion

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


        public function signup($data){

            if($this->validate($data)){
                show($data);

                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT); // Hashage du mot de passe

                $this->insert($data); // Insertion dans la base de données

                redirect("login");
            } else {
                // Affichage des erreurs
                show($this->errors);
            }
        }


        public function login($data) {

            if (!$this->validate($data)) {
                show($this->errors);
                return;
            }
            // Rechercher l'utilisateur dans la base de données
            $row = $this->findOneBy([ // ex: findOneBy(['email' => $data['email']])
                $this->loginUniqueColumn => $data[$this->loginUniqueColumn]
            ]); 
        
            if ($row) {
                // Vérifier si le mot de passe est correct
                if (password_verify($data['password'], $row->password)) {
        
                    // Initialiser la session pour l'utilisateur
                    $session = new \Core\Session;
                    $session->auth($row);
                    
                    $userSessionToken = new \Model\Token;
                    $existingToken = $userSessionToken->findOneBy(['user_id' => $row->id]);

                    if ($existingToken && $userSessionToken->isTokenValid($existingToken->token)) {
                        // Token valide, rediriger directement
                        redirect("home");
                        return;
                    }             
                    // Générer un token pour l'utilisateur
                    $tokenData = [
                        'user_id' => $row->id
                    ];

                    $userSessionToken->storeToken($tokenData); 
        
                    // Rediriger vers la page d'accueil
                    redirect("home");

                } else {
                    // Si le mot de passe est incorrect
                    $this->errors[$this->loginUniqueColumn] = "Wrong $this->loginUniqueColumn or password";
                }
            } else {
                // Si l'utilisateur n'existe pas
                $this->errors[$this->loginUniqueColumn] = "Wrong $this->loginUniqueColumn or password";
            }
        }
    }