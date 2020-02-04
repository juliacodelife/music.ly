<?php
    class Account {

        private $con;
        private $errorArray;

        public function __construct($con){
            $this->con = $con;
            $this->errorArray = array();   
        }  

        public function login($un, $pw){
            $pw = md5($pw); //szyfrowanie hasla 
            $query = mysqli_query($this->con, "SELECT * FROM users WHERE username='$un' AND password='$pw'");
            if(mysqli_num_rows($query) == 1){
                return true;
            }
            else{
                array_push($this->errorArray, Constants::$loginFailed);
                return false;
            } 
        } //jezeli w bazie danych znajduje sie nazwa uzytkownika i haslo zwroc true i zaloguj, w przeciwnym razie zwroc blad 

        public function register($un, $gn, $ln, $em, $em2, $pw, $pw2){
            $this->valiadeUsername($un);
            $this->valiadeGivenName($gn);
            $this->valiadeLastName($ln);
            $this->valiadeEmails($em, $em2);
            $this->valiadePasswords($pw, $pw2); //wywoluje zadeklarowane funkcje walidacyjne

            if(empty($this->errorArray == true)){
                return $this->insertUserDetails($un, $gn, $ln, $em, $pw); //wstaw do bazy danych
            }
            else{
                return false;
            }
        } // jezeli wszystkie dane sa prawidlowe utworz nowego uzytkownika w bazie danych i zarejestruj 

        public function getError($error){
            if(!in_array($error, $this->errorArray)){
                $error = "";
            }
            return "<span class='errorMessage'>$error</span>";
        } //przeszukaj tablicę, jesli jest blad zwroc komunikat o bledzie 

        private function insertUserDetails($un, $gn, $ln, $em, $pw){
            $encryptedPw = md5($pw);
            $profilePic = "assets/images/profile-pics/head_emerald.png";
            $date = date("Y-m-d");

            $result = mysqli_query($this->con, "INSERT INTO users VALUES ('', '$un', '$gn', '$ln', '$em', '$encryptedPw', '$date', '$profilePic')");
            return $result;
        } //wstaw dane do bazy danych

        private function valiadeUsername($un){
            if(strlen($un) > 25 || strlen($un) < 5){
                array_push($this->errorArray, Constants::$usernameCharakters);
                return; //sprawdz, czy nazwa uzytkownika zawiera od 5 do 25 znakow
            }
            $checkUsernameQuery = mysqli_query($this->con, "SELECT username FROM users WHERE username='$un'");
            if(mysqli_num_rows($checkUsernameQuery) != 0){
                array_push($this->errorArray, Constants::$usernameTaken);
                return; //sprawdz, czy nazwa uzytkownika juz istnieje
            }
        }


        private function valiadeGivenName($gn){
            if(strlen($gn) > 25 || strlen($gn) < 2){
                array_push($this->errorArray, Constants::$givenNameCharakters);
                return; //sprawdz, czy imie zawiera od 2 do 25 znakow
            }
        }
        
        private function valiadeLastName($ln){
            if(strlen($ln) > 25 || strlen($ln) < 2){
                array_push($this->errorArray, Constants::$lastNameCharakters);
                return; //sprawdz, czy nazwisko zawiera od 2 do 25 znakow
            }  
        }
        
        private function valiadeEmails($em, $em2){
            if($em != $em2){
                array_push($this->errorArray, Constants::$emailDoNoMatch);
                return; //sprawdz, czy adresy email sa takie same
            }
             if(!filter_var($em, FILTER_VALIDATE_EMAIL)){
                array_push($this->errorArray, Constants::$emailInvalid);
                return; //sprawdz, czy adres email jest ma poprawna forme
             } 
             $checkEmailQuery = mysqli_query($this->con, "SELECT username FROM users WHERE email='$em'");
             if(mysqli_num_rows($checkEmailQuery) != 0){
                 array_push($this->errorArray, Constants::$emailTaken);
                 return; //sprawdz, czy adres emial juz istnieje
             }
        }

        private function valiadePasswords($pw, $pw2){
            if($pw != $pw2){
                array_push($this->errorArray, Constants::$passwordsDoNoMatch);
                return; //sprawdz, czy hasla sa takie same
            }

            if(preg_match('/[^A-Za-z0-9]/', $pw)){
                array_push($this->errorArray, Constants::$passwordNotAlphanumeric);
                return; //sprawdz, czy haslo zawiera tylko liczby i/lub cyfry
            }

            if(strlen($pw) > 30 || strlen($pw) < 5){
                array_push($this->errorArray, Constants::$passwordCharakters);
                return; //sprawdz, czy haslo zawiera od 5 do 30 znakow
            }

            
          
        }


    }


?>