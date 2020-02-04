<?php 
    include("includes/includedFiles.php");
    
    if(isset($_GET['id'])){ 
        $artistId = $_GET['id']; //jezeli w adresie url podano id artysty to ustaw zmienna artistId 
    }
    else{
        header("Location: index.php"); //w przeciwnym razie przekieruj na strone index.php
    } 

    $artist = new Artist($con, $artistId) //utworz obiekt artysta

?>

<div class="entityInfo borderBottom"> 
    <div class="centerSection">
        <div class="artistInfo">
            <h1 class="artistName"> <?php echo $artist->getName(); ?> </h1>
            <div class="headerButtons">
                <button class="button green" onclick="playFirstSong()">ODTWÓRZ</button>
            </div>
        </div>
    </div>
</div>

<div class="trackListContainer borderBottom">
    <h2>PIOSENKI</h2>
    <ul class="trackList">
        <?php 
            $songIdArray = $artist->getSongIds();

            $i = 1;
            foreach($songIdArray as $songId){

                if($i > 5){
                    break;
                }
                
                $albumSong = new Song($con, $songId);
                $albumArtist = $albumSong->getArtist();

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

            }
        ?>

        <script>
            var tempSongIds = '<?php echo json_encode($songIdArray); ?>'; //koduj $songIdArray do formatu JSON
            tempPlaylist = JSON.parse(tempSongIds); //przekonwertuj tempSongIds na obiekt JavaScript
        </script>

    </ul>
</div>

<div class="gridViewContainer">
    <h2>ALBUMY</h2>
    <?php
        $albumQuery = mysqli_query($con, "SELECT * FROM albums WHERE artist='$artistId'");
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