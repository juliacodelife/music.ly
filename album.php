<?php include("includes/includedFiles.php"); 

    if(isset($_GET['id'])){
        $albumId = $_GET['id']; //jezeli w adresie url podano id albumu to ustaw zmienna albumId 
    }
    else{
        header("Location: index.php"); //w przeciwnym razie przekieruj na strone index.php
    } 

    $album = new Album($con, $albumId); //utworz obiekt album
    $artist = $album->getArtist(); 
    $artistId = $artist->getId();
?>

<div class="entityInfo">
    <div class="leftSection">
        <img src="<?php echo $album->getArtworkPath(); ?>">
    </div>
    <div class="rightSection">
        <h2><?php echo $album->getTitle(); ?></h2>
        <p role="link" tabindex="0" onclick="openPage('artist.php?id=<?php echo $artistId; ?>')">Wykonawca: <?php echo $artist->getName(); ?></p>
        <p> <?php echo $album->getNumberOfSongs(); ?> piosenki </p>
    </div>
</div>

<div class="trackListContainer">
    <ul class="trackList">
        <?php 
            $songIdArray = $album->getSongIds();

            $i = 1;
            foreach($songIdArray as $songId){
                
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

<nav class="optionMenu">
    <input type="hidden" class="songId"> 
    <?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn->getUsername()); ?>
</nav>


