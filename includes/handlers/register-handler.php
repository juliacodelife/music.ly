<?php

function sanitizeFormUserName($inputText){
  $inputText = strip_tags($inputText); //funkcja usuwajaca wszelkie znaczniki HTML z ciągu
  $inputText = str_replace(" ","",$inputText); //usuwa wszystkie spacje  
  return $inputText;
}

function sanitizeFormString($inputText){
  $inputText = strip_tags($inputText);
  $inputText = str_replace(" ","",$inputText);
  $inputText = ucfirst(strtolower($inputText)); //pierwsza litera z duzej, dalej konwertuj na male
  return $inputText;
}

function sanitizeFormPassword($inputText){
  $inputText = strip_tags($inputText);
  return $inputText;
}

if(isset($_POST['registerButton'])){
  $username = sanitizeFormUsername($_POST['username']);
  $givenname = sanitizeFormString($_POST['givenname']);
  $lastname = sanitizeFormString($_POST['lastname']);
  $email = sanitizeFormString($_POST['email']);
  $email2 = sanitizeFormString($_POST['email2']);
  $password = sanitizeFormPassword($_POST['password']);
  $password2 = sanitizeFormPassword($_POST['password2']);  //jesli nacisnieto registerButton wywołaj poszczegolne funkcje

  $wasSuccessful = $account->register($username, $givenname, $lastname, $email, $email2, $password, $password2); //rejestracja

  if($wasSuccessful == true){
    $_SESSION['userLoggedIn'] = $username;
    header("Location: index.php");  //jesli rejestracja przebiegala poprawnie przekieruj do strony index.php
  }

}

?>
