<?php 

    namespace Controller;

    defined('ROOTPATH') OR exit("Access Denied!");

    // use Model\User; // Importe le modèle User pour l'instancier $user = new User;

    class Login {

        use MainController;

        public function index() {

            $data = [];

            if($_SERVER['REQUEST_METHOD'] == "POST"){

                $user = new \Model\User; // Instancie le modèle User sans le use
                $arr = [
                    "email" => $_POST['email']
                ];

                $row = $user->findOneBy($arr);

                
                if($row){
                    if($row && password_verify($_POST['password'], $row->password)){
                        $_SESSION['USER'] = $row;
                        redirect("home");
                    }
                }
                $user->errors['email'] = "email or password is incorrect";

                $data['errors']= $user->errors;
            }

            $this->view("login", $data);
        }

    }
