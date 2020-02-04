<?php

    include("../../config.php");

    if(!isset($_POST['username'])){
        echo "BŁĄD: Nie można ustalić nazwy użytkownika!";
        exit();
    }

    if(isset($_POST['email']) && $_POST['email'] != ""){
        $username = $_POST['username'];
        $email = $_POST['email'];
    }  //sprawdz, czy nazwa uzytkownika istnieje

    if(!isset($_POST['oldPassword']) || !isset($_POST['newPassword1']) || !isset($_POST['newPassword2'])){
        echo "Not all password have been set";
        exit();
    }  //sprawdz, czy hasla zostaly ustawione

    if($_POST['oldPassword'] == "" || $_POST['newPassword1'] == "" || $_POST['newPassword2'] == "" ){
        echo "Proszę uzupełnić wszystkie pola";
        exit();
    } //sprawdz, czy wszyskie pola zostaly wypelnione
 
    $username = $_POST['username'];
    $oldPassword = $_POST['oldPassword'];
    $newPassword1 = $_POST['newPassword1'];
    $newPassword2 = $_POST['newPassword2'];

    $oldMd5 =md5($oldPassword);

    $passwordCheck = mysqli_query($con, "SELECT * FROM users WHERE username='$username' AND password='$oldMd5'");
    if(mysqli_num_rows($passwordCheck) != 1){
        echo "Hasło jest niepoprawne";
        exit();
    } //sprawdz, czy stare haslo jest wprowadzone poprawnie, weryfikacja uzytkownika

    if($newPassword1 != $newPassword2){
        echo "Hasła nie są identyczne";
        exit(); 
    } //sprawdz, czy podane hasla sa takie same

    if(preg_match('/[^A-Za-z0-9]/', $newPassword1)){
        echo "Twoje hasło musi zawierać wyłącznie litery i/lub cyfry";
        exit();
    } //jesli haslo nie zawiera..

    if(strlen($newPassword1) > 30 || strlen($newPassword1) < 5){
        echo "Twoje hasło musi zawierać od 5 do 30 znaków";
        exit();
    }

    $newMd5 = md5($newPassword1); //szyfrowanie nowego hasla

    $query = mysqli_query($con, "UPDATE users SET password='$newMd5' WHERE username='$username'"); //aktualizacja hasla
    echo "Hasło zostało zmienione poprawnie!";
 
?>
