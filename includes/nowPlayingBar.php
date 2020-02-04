<?php
  $songQuery = mysqli_query($con, "SELECT id FROM songs ORDER BY RAND() LIMIT 10"); //wybierz z bd 10 losowych piosenke z tablei songs

  $resultArray = array(); //deklaracja pustej tablicy

  while($row = mysqli_fetch_array($songQuery)){
      array_push($resultArray, $row['id']);
  } //wypchnij/dodaj do tablicy kazdy rekord z zapytania z bd

  $jsonArray = json_encode($resultArray); //przekonwertuj tablice $resultArray do formatu JSON

?>

<script>
    $(document).ready(function() {
        var newPlaylist = <?php echo $jsonArray;?>;
        audioElement = new Audio();
        setTrack(newPlaylist[0], newPlaylist, false); //przekaz nowa playlisty do funkcji setTrack i ustaw sciezke 
        updateVolumeProgressBar(audioElement.audio); //gdy strona sie zaladuje, ustaw szerokosc paska glosnosci 100%  

        $("#nowPlaingBarContainer").on("mousedown touchstart mousemove touchmove", function(e){
            e.preventDefault();
        }); //anuluj zdarzenia, jesli mozna je anulowac (zapobiega wykonaniu domyślnej akcji)

        $(".playbackBar .progress").mousedown(function(){
            mouseDown = true;
        }); //gdy mysz jest wcisnieta, ustaw zmienna myszy na true

        $(".playbackBar .progressBar").mousemove(function(e){
            if(mouseDown == true){
                timeFromOffset(e, this); 
            } // jesli zmienna mouseDown jest true, ustaw czas utworu, w zaleznosci od pozycji myszy
        }); //gdy wskaznik myszy jest aktywny w obrebie paska postepu

        $(".playbackBar .progressBar").mouseup(function(e){
                timeFromOffset(e, this);
        }); //gdy wskaznik myszy zostanie zwolniony z paska postepu


        $(".volumeBar .progressBar").mousedown(function(){
            mouseDown = true;
        }); //gdy mysz jest wcisnieta, ustaw zmienna myszy na true

        $(".volumeBar .progressBar").mousemove(function(e){
            if(mouseDown == true){
                var percentage = e.offsetX / $(this).width(); //pozycji wskaznika myszy na pasku glosnosci wzgledem calej szerokosci paska
                if(percentage >= 0 && percentage <= 1){
                    audioElement.audio.volume = percentage;
                } //jesli jest wieksze badz rowne zero i mniejsze badz rowne 1, ustaw biezaca gloscnosc 
            } // jesli zmienna mouseDown jest true
        }); //gdy wskaznik myszy jest aktywny w obrebie paska glosnosci

        $(".volumeBar .progressBar").mouseup(function(e){
            var percentage = e.offsetX / $(this).width(); //pozycji wskaznika myszy na pasku glosnosci wzgledem calej szerokosci paska
                if(percentage >= 0 && percentage <=1){
                    audioElement.audio.volume = percentage;
                } //jesli jest wieksze badz rowne zero i mniejsze badz rowne 1, ustaw biezaca gloscnosc 
        }); //gdy wskaznik myszy zostanie zwolniony z paska glosnosci

        $(document).mouseup(function(){
            mouseDown = false;
        }); //gdy mysz nie jest wcisnieta, ustaw zmienna mouseDown na false

    });

    function timeFromOffset(mouse, progressBar){
        var percentage = mouse.offsetX / $(progressBar).width() *100; //jaki procent stanowi pozycja wskaznika myszy na pasku postepu do calej szerokosci
        var seconds = audioElement.audio.duration * (percentage /100); //ustal czas odtwarzania wzgledem ustawienia myszy na pasku postepu
        audioElement.setTime(seconds); 
    } //aktualizuj czas odtwarzanego utworu wzgledem przesuniecia myszy na pasku postepu

    function prevSong(){
        if(currentIndex ==0){
            audioElement.setTime(0);
        } //jesli obecny indeks rowna sie zero, ustaw czas od nowa
        else{
            currentIndex = currentIndex -1;
            setTrack(currentPlaylist[currentIndex], currentPlaylist, true);
        } //w przeciwnym razie odtworz poprzednia piosenke
    }

    function nextSong(){

        if(repeat == true){
            audioElement.setTime(0);
            playSong();
            return;
        } //jesli zmienna repeat rowna sie true, ustaw aktualny czas na zero

        if(currentIndex == currentPlaylist.length - 1){
            currentIndex = 0;
        } //jesli aktualny indeks jest rowny ostatniemu elementowi w tablicy to wroc do pierwszego utworu
        else{
            currentIndex++;
        } //w przeciwnym razie odtwarzaj kolejne piosenki

        var trackToPlay = shuffle ? shufflePlaylist[currentIndex] : currentPlaylist[currentIndex]; //jesli losowanie jest wlaczane wybierz piosenke z listy losowej, w przeciwnym razie z normalnej playlisty
        setTrack(trackToPlay, currentPlaylist, true);
    }

    function setRepeat(){
        repeat = !repeat;
        var imageName = repeat ? "repeat-active.png" : "repeat.png";
        $(".controlButton.repeat img").attr("src", "assets/images/icons/" + imageName);
    } //ustaw powtarzanie

    function setMute(){
        audioElement.audio.muted = !audioElement.audio.muted;
        var imageName = audioElement.audio.muted ? "volume-mute.png" : "volume.png";
        $(".controlButton.volume img").attr("src", "assets/images/icons/" + imageName);
    } //ustaw wyciszenie

    function setShuffle(){
        shuffle = !shuffle;
        var imageName = shuffle ? "shuffle-active.png" : "shuffle.png";
        $(".controlButton.shuffle img").attr("src", "assets/images/icons/" + imageName);

        if(shuffle == true){
            shuffleArray(shufflePlaylist); //sortuj losowo liste odtwarzania
            currentIndex = shufflePlaylist.indexOf(audioElement.currentlyPlaying.id); //ustaw indeks odtwarzanej piosenki w liscie losowej
        } //jesli zmienna shuffle rowna sie true, losuj listę odtwarzania
        else{
            currentIndex = currentPlaylist.indexOf(audioElement.currentlyPlaying.id);
        } //w przeciwnym razie odtwarzanie losowe zostanie wyłączone, wróć do zwykłej/biezacej listy odtwarzania
        
    }

    function shuffleArray(a) {
        var j, x, i;
        for (i = a.length - 1; i > 0; i--) {
            j = Math.floor(Math.random() * (i + 1));
            x = a[i];
            a[i] = a[j];
            a[j] = x;
        }
        return a;
    }  //przetasuj tablice elementow

    function setTrack(trackId, newPlaylist, play){

        if(newPlaylist != currentPlaylist){
            currentPlaylist = newPlaylist; //zapisz nowa playliste jak biezaca
            shufflePlaylist = currentPlaylist.slice(); //stworz kopie biezacej listy
            shuffleArray(shufflePlaylist); //przetasuj losowo
        } //jesli nowa playlista nie jest rowna biezacej liscie odtwarzania

        if(shuffle == true){
            currentIndex = shufflePlaylist.indexOf(trackId);
        } //jesli wlaczono losowanie, wez utwor z playlisty losowej i ustaw indeks
        else{
            currentIndex = currentPlaylist.indexOf(trackId);
        }  //w przeciwnym razie, gdy ustawiamy ścieżkę, wez utwor z biezacej playlisty i ustaw indeks odtwarzanej piosenki
        
        pauseSong();  //przed zmiana piosenki zatrzymaj odtwarzanie

        $.post("includes/handlers/ajax/getSongJson.php", { songId: trackId }, function(data){

            var track = JSON.parse(data); //przekonwertuj na obiekt JavaScript i tam umiesc dane zwrocone z wywolania AJAX
            $(".trackName span").text(track.title); //ustaw tytuł piosenki 

            $.post("includes/handlers/ajax/getArtistJson.php", { artistId: track.artist }, function(data){
                var artist = JSON.parse(data);
                $(".trackInfo .artistName span").text(artist.name); //ustaw artyste
                $(".trackInfo .artistName span").attr("onclick", "openPage('artist.php?id=" + artist.id + "')"); //ustaw przkierowanie do strony artysty po kliknieciu na nazwe artysty
            });

            $.post("includes/handlers/ajax/getAlbumJson.php", { albumId: track.album }, function(data){
                var album = JSON.parse(data);
                $(".content .albumLink img").attr("src", album.artworkPath); //ustaw do img albumu odpowiadajaca mu grafike
                $(".content .albumLink img").attr("onclick", "openPage('album.php?id=" + album.id + "')"); //ustaw przkierowanie do strony albumu po kliknieciu na grafike albumu
                $(".trackInfo .trackName span").attr("onclick", "openPage('album.php?id=" + album.id + "')"); 
            });

            audioElement.setTrack(track);

            if(play == true){
                playSong();
            } //jesli play jest true odtworz piosenke
        }); //gdy powraca wywolanie AJAX wykonaj ten kod
    }

    function playSong(){
        if(audioElement.audio.currentTime == 0){
            $.post("includes/handlers/ajax/updatePlays.php", {songId: audioElement.currentlyPlaying.id});
        } //jesli aktualny czas odtwarzania piosenki wynosi 0, zaktualizuj liczbe odtwarzania

        $(".controlButton.play").hide(); //jesli nacisniesz przycisk play ukryj go 
        $(".controlButton.pause").show(); //pokarz przycisk pause
        audioElement.play(); //odtworz
    }

    function pauseSong(){
        $(".controlButton.pause").hide(); //jesli nacisniesz przycisk pause ukryj go 
        $(".controlButton.play").show();  //pokarz przycisk play
        audioElement.pause(); //wstrzymaj
    }
</script>


<div id="nowPlayingBarContainer">
    <div id="nowPlayingBar">
        <div id="nowPlayingLeft">
            <div class="content">

                <span class="albumLink">
                    <img role="link" tabindex="0" src="" class="albumArtwork" alt="Album">
                </span>

                <div class="trackInfo">

                    <span role="link" tabindex="0" class="trackName">
                        <span></span>
                    </span>

                    <span class="artistName">
                        <span role="link" tabindex="0"></span>
                    </span>

                </div>
            </div>
        </div>
        <div id="nowPlayingCenter">
            <div class="content playerControls">
                <div class="buttons">
                    <button class="controlButton shuffle" title="Shuffle button" onclick="setShuffle()">
                        <img src="assets/images/icons/shuffle.png" alt="Shuffle">
                    </button>

                    <button class="controlButton previous" title="Prevoius button" onclick="prevSong()">
                        <img src="assets/images/icons/previous.png" alt="Previous">
                    </button>

                    <button class="controlButton play" title="Play button" onclick="playSong()">
                        <img src="assets/images/icons/play.png" alt="Play">
                    </button>

                    <button class="controlButton pause" title="Pause button" style="display: none;" onclick="pauseSong()">
                        <img src="assets/images/icons/pause.png" alt="Pause">
                    </button>

                    <button class="controlButton next" title="Next button" onclick="nextSong()">
                        <img src="assets/images/icons/next.png" alt="Next">
                    </button>

                    <button class="controlButton repeat" title="Repeat button" onclick="setRepeat()" >
                        <img src="assets/images/icons/repeat.png" alt="Repeat">
                    </button>
                </div>
                <div class="playbackBar">
                    <span class="progressTime current">0:00</span>
                    <div class="progressBar">
                        <div class="progressBarBg">
                            <div class="progress"></div>
                        </div>
                    </div>
                    <span class="progressTime remaining">0:00</span>
                </div>
            </div>
        </div>
        <div id="nowPlayingRight">
            <div class="volumeBar">
                <button class="controlButton volume" title="Volume button" onclick="setMute()">
                    <img src="assets/images/icons/volume.png" alt="Volume">
                </button>

                <div class="progressBar">
                        <div class="progressBarBg">
                            <div class="progress"></div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>