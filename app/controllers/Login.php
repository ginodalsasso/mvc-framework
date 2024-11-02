<?php 

    class Login {

        use Controller;

        public function index() {

            $data = [];

            if($_SERVER['REQUEST_METHOD'] == "POST"){

                $user = new User;
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
