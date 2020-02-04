<?php
if(isset($_POST['loginButton'])){
  //jesli nacisnieto loginButton
  $username = $_POST['loginUsername'];
  $password = $_POST['loginPassword'];

  //funkcja logowania
  $result = $account->login($username, $password);

  //jesli poprawne dane logowania przekieruj do strony index.php
  if($result == true){
    $_SESSION['userLoggedIn'] = $username;
    header("Location: index.php");
  }
}
?>