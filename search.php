<?php
    include("includes/includedFiles.php");

    if(isset($_GET['term'])){
        $term = urldecode($_GET['term']);
    } //jesli jest ustawiony term w adresie url to zdekoduj url i ustaw zmienna term
    else{
        $term = "";
    }

?>

<div class="searchContainer">
    <h4>Wyszukaj artystę, album lub piosenkę</h4>
    <input type="text" class="searchInput" value="<?php echo $term; ?>" placeholder="Zacznij pisać..." onfocus="this.selectionStart = this.selectionEnd = this.value.length;" spellcheck="false">
</div>

<script>

    $(".searchInput").focus(); //gdy element o klasie searchInput jest aktywny skup na nim uwage 

    $(function(){
        $(".searchInput").keyup(function(){
            clearTimeout(timer);

            timer = setTimeout(function(){
                var val = $(".searchInput").val();
                openPage("search.php?term=" + val);
            }, 2000); //kiedy uzytkownik przestanie wpisywac szukana wartosc, po 2 sekundach zauktualizuj stronę o wartosc wpisaną w input o klasie searchInput
        });
    });
    

</script>

<?php 
    if($term == ""){
        exit();
    }
?>

<div class="trackListContainer borderBottom">
    <h2>Kategoria: piosenka</h2>
    <ul class="trackList">
        <?php 
            $songsQuery = mysqli_query($con, "SELECT id FROM songs WHERE title LIKE '$term%' LIMIT 10");
            if(mysqli_num_rows($songsQuery) == 0){
                echo "<span class='noResults'>Nie znaleziono pasujących piosenek</span>";
            } //jesli liczba rekordow wyszukanych w bazie danych rowna sie zero wyswietl komunikat 

            $songIdArray = array();

            $i = 1;
            while($row = mysqli_fetch_array($songsQuery)){
                if($i > 10){
                    break;
                } //wyswietl max 10 rekordow wyszukanym w bd

                array_push($songIdArray, $row['id']); //wypchnij/dodaj do tablicy kazdy rekordz zapytania z bd
                
                $albumSong = new Song($con, $row['id']); //utworz nowy obiekt song
                $albumArtist = $albumSong->getArtist(); //dodaj artyste

                echo "<li class='trackListRow'>
                        <div class='trackCount'>
                            <img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"". $albumSong->getId() ."\", tempPlaylist, true)'>
                            <span class='trackNumber'>$i</span>
                        </div>
                        <div class='trackInfo'>
                            <span class='trackName'>" . $albumSong->getTitle() . "</span>
                            <span class='artistName'>" . $albumArtist->getName() . "</span>
                        </div>
                        <div class='trackOptions'>
                            <input type='hidden' class='songId' value='" . $albumSong->getId() . "'>
                            <img class='optionButton' src='assets/images/icons/more.png' onclick='showOptionMenu(this)'>
                        </div>
                        <div class='trackDuration'>
                        <span class='duration'>" . $albumSong->getDuration() . "</span>
                        </div>
                        </li>";

                $i++;

            } //petla przejdzie tyle razy ile rekordow zwrocilo zapytanie z bd
        ?>

        <script>
            var tempSongIds = '<?php echo json_encode($songIdArray); ?>'; //przekonwertuj tablice $songIdArray do formatu JSON
            tempPlaylist = JSON.parse(tempSongIds); //przekonwertuj tempSongIds na obiekt JavaScript
        </script>

    </ul>
</div>

<div class="artistsContainer borderBottom">
    <h2>Kategoria: artysta</h2>
    <?php
        $artistsQuery = mysqli_query($con, "SELECT id FROM artists WHERE name LIKE '$term%' LIMIT 10");

        if(mysqli_num_rows($artistsQuery) == 0){
            echo "<span class='noResults'>Nie znaleziono pasujących wykonawców</span>";
        }
            
        while($row = mysqli_fetch_array($artistsQuery)){
            $artistFound = new Artist($con, $row['id']);
            echo "<div class='searchResultRow'>
                    <div class='artistName'>
                        <span role='link' tabindex='0' onclick='openPage(\"artist.php?id=" . $artistFound->getId() . "\")'>
                            " . $artistFound->getName() . "
                        </span>
                    </div>
                    </div>";
        }
        
    ?>
</div>

<div class="gridViewContainer">
    <h2>Kategoria: album</h2>
    <?php
        $albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE title LIKE '$term%' LIMIT 10");

        if(mysqli_num_rows($albumQuery) == 0){
            echo "<span class='noResults'>Nie znaleziono pasujących albumów</span>";
        }

        while($row = mysqli_fetch_array($albumQuery)){

            echo "<div class='gridViewItem'>

                    <span role='link' tabindex='0' onclick='openPage(\"album.php?id=" . $row['id'] . "\")'>

                        <img src='" . $row['artworkPath']  . "'>

                        <div class='gridViewInfo'>"
                            . $row['title'] .
                        "</div>

                    </span>
                    
                   </div>";
        }
    ?>
</div>

<nav class="optionMenu">
    <input type="hidden" class="songId"> 
    <?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn->getUsername()); ?>
</nav>

