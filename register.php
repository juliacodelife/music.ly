<?php
  include("includes/config.php");
  include("includes/classes/Account.php");
  include("includes/classes/Constants.php");
  
  $account = new Account($con);

  include("includes/handlers/register-handler.php");
  include("includes/handlers/login-handler.php");

  function getInputValue($name){
    if(isset($_POST[$name])){
      echo $_POST[$name];
    } //jesli dane w formaularzu zostaly ustawione zapamietaj te wartosci
  }

?>

<html>
<head>
    <title>Witaj w music.ly!</title>
    <link rel="stylesheet" type="text/css" href="assets/css/register.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/js/register.js"></script>
</head>
<body>
  <?php
    if(isset($_POST['registerButton'])){
      echo '<script>
              $(document).ready(function(){
                $("#loginForm").hide();
                $("#registerForm").show();
               });
            </script>';
    } 
    else{
      echo '<script>
              $(document).ready(function(){
                $("#loginForm").show();
                $("#registerForm").hide();
              });
            </script>';
    }

  ?>

 <div id="background">
   <div id="loginContainer">
    <div id="inputContainer">
          <form  id="loginForm" action="register.php" method="POST">
            <h2>Zaloguj się na swoje konto</h2>
            <p>
              <?php echo $account->getError(Constants::$loginFailed); ?>
              <label for="loginUserName">Nazwa użytkownika: </label>
              <input type="text" id="loginUsername" name="loginUsername" value="<?php getInputValue('loginUsername')?>" placeholder="np. juliaJanik" required>
            </p>
            <p>
              <label for="loginPassword">Hasło: </label>
              <input type="password" id="loginPassword" name="loginPassword" required>
            </p>
            <button type="submit" name="loginButton">Zaloguj się</button>

            <div class="hasAccountText">
              <span id="hideLogin">Nie masz jeszcze konta? Zarejestruj się za darmo!</span>
            </div>

          </form>

          <form  id="registerForm" action="register.php" method="POST">
            <h2>Utwórz konto</h2>
            <p>
              <?php echo $account->getError(Constants::$usernameCharakters); ?>
              <?php echo $account->getError(Constants::$usernameTaken); ?>
              <label for="username">Nazwa użytkownika: </label>
              <input type="text" id="username" name="username" placeholder="np. juliaJanik" value="<?php getInputValue('username') ?>" required>
            </p>

            <p>
              <?php echo $account->getError(Constants::$givenNameCharakters); ?>
              <label for="givenname">Imię: </label>
              <input type="text" id="givenname" name="givenname" placeholder="np. Julia" value="<?php getInputValue('givenname') ?>" required>
            </p>

            <p>
              <?php echo $account->getError(Constants::$lastNameCharakters); ?>
              <label for="lastname">Nazwisko: </label>
              <input type="text" id="lastname" name="lastname" placeholder="np. Janik" value="<?php getInputValue('lastname') ?>" required>
            </p>

            <p>
              <?php echo $account->getError(Constants::$emailDoNoMatch); ?>
              <?php echo $account->getError(Constants::$emailInvalid); ?>
              <?php echo $account->getError(Constants::$emailTaken); ?>
              <label for="email">E-mail: </label>
              <input type="text" id="email" name="email" placeholder="np. julia@gmail.com" value="<?php getInputValue('email') ?>" required>
            </p>

            <p>
              <?php echo $account->getError("Your username must be between 5 and 25 charakters"); ?>
              <label for="email2">Potwierdź e-mail: </label>
              <input type="text" id="email2" name="email2" placeholder="np. julia@gmail.com" value="<?php getInputValue('email2') ?>" required>
            </p>

            <p>
              <?php echo $account->getError(Constants::$passwordsDoNoMatch); ?> 
              <?php echo $account->getError(Constants::$passwordNotAlphanumeric); ?> 
              <?php echo $account->getError(Constants::$passwordCharakters); ?> 
              <label for="password">Hasło: </label>
              <input type="password" id="password" name="password" required>
            </p>

            <p>
              <label for="password2">Potwierdź hasło: </label>
              <input type="password" id="password2" name="password2" required>
            </p>

            <button type="submit" name="registerButton">Zarejestruj się</button>

            <div class="hasAccountText">
              <span id="hideRegister">Masz już konto? Zaloguj się</span>
            </div>

          </form>  
      </div>

      <div id="loginText">
        <h1>Słuchaj swojej ulubionej muzyki!</h1>
        <ul>
          <li>Korzystaj zupełnie za darmo</li>
          <li>Wybierać to, czego chcesz słuchać</li>
          <li>Twórz własne zbiory muzyczne</li>
        </ul>
      </div>

     </div>
    </div>
</body>
</html>