<?php
    include("../../config.php");

    if(!isset($_POST['username'])){
        echo "BŁĄD: Nie można ustalić nazwy użytkownika!";
        exit();
    } //jezeli nazwa uzytkownika nie jest ustawiona zwroc komunikat bledu

    if(isset($_POST['email']) && $_POST['email'] != ""){
        $username = $_POST['username'];
        $email = $_POST['email'];   

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            echo "E-mail jest nieprawidłowy";
            exit();
        } //sprawdz, czy email ma prawidlowa forme
        
        $emailCheck = mysqli_query($con, "SELECT email FROM users WHERE email = '$email' AND username != '$username'");
        if(mysqli_num_rows($emailCheck) > 0){
            echo "Ten adres e-mail jest już w użyciu";
            exit();
        } // sprawdz, czy email juz istnieje

        $updateQuery = mysqli_query($con, "UPDATE users SET email = '$email' WHERE username = '$username'");
        echo "Adres e-mail został zmieniony poprawnie!"; //zauktualizuj adres email w bd
    } //jezeli adres email jest ustawiony i nie jest pustym string'em
    else{
        echo "Musisz podać nowy adres e-mail";
    }
?>
