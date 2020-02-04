<?php

    include("includes/includedFiles.php");

?>

<div class="userDetails">
    <div class="container borderBottom">
        <h2>ZMIEŃ E-MAIL</h2>
        <input type="text" class="email" name="email" placeholder="Adres e-mail..." value="<?php echo $userLoggedIn->getEmail(); ?>">
        <span class="message"></span>
        <button class="button" onclick="updateEmail('email')">ZAPISZ</button>
    </div>
    <div class="container">
        <h2>ZMIEŃ HASŁO</h2>
        <input type="password" class="oldPassword" name="oldPassword" placeholder="Obecne hasło">
        <input type="password" class="newPassword1" name="newPassword1" placeholder="Nowe hasło">
        <input type="password" class="newPassword2" name="newPassword2" placeholder="Potwierdź nowe hasło">
        <span class="message"></span>
        <button class="button" onclick="updatePassword('oldPassword', 'newPassword1', 'newPassword2')">ZAPISZ</button>
    </div>
</div>