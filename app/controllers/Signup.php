<?php 
    defined('ROOTPATH') OR exit("Access Denied!");

    class Signup {

        use Controller;

        public function index() {

            $data = [];

            if($_SERVER['REQUEST_METHOD'] == "POST"){
                $user = new User;
                show($_POST);
                if($user->validate($_POST)){
                     // Crée un tableau filtré des données pour l'insertion
                    $filteredData = [
                        'email' => $_POST['email'],
                        'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                    ];

                    // Insertion dans la base de données
                    $user->insert($filteredData);
                    
                    redirect("login");
                }
    
                $data['errors']= $user->errors;
            }

            $this->view("signup", $data);
        }

    }
