<?php 

    namespace Controller;

    defined('ROOTPATH') OR exit("Access Denied!");

    // use Model\User; // Importe le modèle User pour l'instancier $user = new User;

    class Signup {

        use MainController;

        public function index() {

            // $data = [];

            // if($_SERVER['REQUEST_METHOD'] == "POST"){
            //     $user = new \Model\User; // Instancie le modèle User sans le use
            //     show($user)
            //     if($user->validate($_POST)){
            //         //  // Crée un tableau filtré des données pour l'insertion
            //         // $filteredData = [
            //         //     'email' => $_POST['email'],
            //         //     'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            //         // ];

            //         // Insertion dans la base de données
            //         $user->insert($_POST);
                    
            //         redirect("login");
            //     }
    
            //     $data['errors']= $user->errors;
            // }

            // $this->view("signup", $data);

            $user = new \Model\User; // Instancie le modèle User sans le use
            // show($user['errors']);    
            if($_SERVER['REQUEST_METHOD'] == "POST"){
                if($user->validate($_POST)){
                    // Insertion dans la base de données
                    $user->insert($_POST);
                    
                    redirect("login");
                }
            }

            $data['user'] = $user;


            $this->view("signup", $data);
        }

    }
