<?php include("includes/includedFiles.php"); 

    if(isset($_GET['id'])){
        $playlistId = $_GET['id']; //jezeli w adresie url podano id playlisty to ustaw zmienna playlistId
    }
    else{
        header("Location: index.php"); //w przeciwnym razie przekieruj na strone index.php
    } 

    $playlist = new Playlist($con, $playlistId); //utworz obiekt playlista

    $owner = new User($con, $playlist->getOwner());

?>

<div class="entityInfo">
    <div class="leftSection">
        <div class="playlistImage">
            <img src="assets/images/icons/playlist.png">
        </div>
    </div>
    <div class="rightSection">
        <h2><?php echo $playlist->getName(); ?></h2>
        <p>
            Wykonawca: <?php echo $playlist->getOwner(); ?>
        </p>
        <p>
            <?php echo $playlist->getNumberOfSongs(); ?> piosenki
        </p>
        <button class="button" onclick="deletePlaylist('<?php echo $playlistId; ?>')">USUŃ PLAYLISTĘ</button>
    </div>
</div>

<div class="trackListContainer">
    <ul class="trackList">
        <?php 
            $songIdArray = $playlist->getSongIds();

            $i = 1;
            foreach($songIdArray as $songId){
                
                $playlistSong = new Song($con, $songId);
                $songArtist = $playlistSong->getArtist();

                echo "<li class='trackListRow'>
                        <div class='trackCount'>
                            <img class='play' src='assets/images/icons/play-white.png' onclick='setTrack(\"". $playlistSong->getId() ."\", tempPlaylist, true)'>
                            <span class='trackNumber'>$i</span>
                        </div>
                        <div class='trackInfo'>
                            <span class='trackName'>" . $playlistSong->getTitle() . "</span>
                            <span class='artistName'>" . $songArtist->getName() . "</span>
                        </div>
                        <div class='trackOptions'>
                            <input type='hidden' class='songId' value='" . $playlistSong->getId() . "'>
                            <img class='optionButton' src='assets/images/icons/more.png' onclick='showOptionMenu(this)'>
                        </div>
                        <div class='trackDuration'>
                        <span class='duration'>" . $playlistSong->getDuration() . "</span>
                        </div>
                        </li>";

                $i++;

            }
        ?>

        <script>
            var tempSongIds = '<?php echo json_encode($songIdArray); ?>'; //koduj $songIdArray do formatu JSON 
            tempPlaylist = JSON.parse(tempSongIds); //przekonwertuj tempSongId na obiekt JavaScript

        </script>

    </ul>
</div>

<nav class="optionMenu">
    <input type="hidden" class="songId"> 
    <?php echo Playlist::getPlaylistsDropdown($con, $userLoggedIn->getUsername()); ?>
    <div class="item" onclick="removeFromPlaylist(this, '<?php echo $playlistId; ?>')">Usuń z playlisty</div>
</nav>
