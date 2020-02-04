<div id="navBarContainer">
    <nav class="navBar">
        <span role="link" tabindex="0" onclick="openPage('index.php')" class="logo">
            <img src="assets/images/icons/logo.png" alt="logo">
            <span class="appName">music.ly</span>
        </span>
        

        <div class="group">
            <div class="navIten">
                <span role="link" tabindex="0" onclick="openPage('search.php')" class="navItenLink"> Szukaj
                    <img src="assets/images/icons/search.png" class="icon" alt="Search">
                </span>
            </div>
        </div>

        <div class="group">

            <div class="navIten">
            <span role="link" tabindex="0" onclick="openPage('home.php')" class="navItenLink">Home</span>
            </div>

            <div class="navIten">
            <span role="link" tabindex="0" onclick="openPage('yourMusic.php')" class="navItenLink">Biblioteka</span>
            </div>

            <div class="navIten">
            <span role="link" tabindex="0" onclick="openPage('updateDetails.php')" class="navItenLink"><?php echo $userLoggedIn->getFirstAndLastName();  ?></span>
            </div>

            <div class="navIten">
            <span role="link" tabindex="0" onclick="logout()" class="navItenLink">Wyloguj się</span>
            </div>

        </div>

    </nav>
</div>