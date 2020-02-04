var currentPlaylist = [];
var shufflePlaylist = [];
var tempPlaylist = [];
var audioElement;
var mouseDown = false;
var currentIndex = 0;
var repeat = false;
var shuffle = false;
var userLoggedIn;
var timer;

$(document).click(function(click){
    var target = $(click.target);

    if(!target.hasClass("item") && !target.hasClass("optionButton")){
        hideOptionMenu();
    } //jesli klikniety element nie ma klasy "item" lub "optionButton" wykonaj funkcje hideOptionMenu 

}); //jesli zdarzenie klikniecia jest przekazywane 

$(window).scroll(function(){
    hideOptionMenu();
}); //przy skrolowaniu strony pasek opcji automatycznie znika

$(document).on("change", "select.playlist", function(){
    var select = $(this);
    var playlistId = select.val();
    var songId = select.prev(".songId").val();
    
    $.post("includes/handlers/ajax/addToPlaylist.php", { playlistId: playlistId, songId: songId })
    .done(function(error){

        if(error != ""){
            alert(error);
            return;
        }

        hideOptionMenu();
        select.val("");
    });
}); //gdy zawartosc zostala zmieniona, zmiana w playlist

function openPage(url){

    if(timer != null){
        clearTimeout(timer);
    }

    if(url.indexOf("?") == -1){
        url = url + "?";
    }
    var encodedUrl = encodeURI(url + "&userLoggedIn=" + userLoggedIn);
    $("#mainContent").load(encodedUrl);
    $("body").scrollTop(0); //jesli uzytkownik zmieni strone, automatycznie przewin do gory
    window.history.pushState(null, null, url);
}

function createPlaylist(){
    var popup = prompt("Nazwij swoją playlistę");
    if(popup != null){
        
        $.post("includes/handlers/ajax/createPlaylist.php", {name: popup, username: userLoggedIn}).done(function(error){
            if(error != ""){
                alert(error);
                return;
            }
            openPage("yourMusic.php")
        });
    }
}

function deletePlaylist(playlistId){
    var prompt = confirm("Jesteś pewny, że chcesz usunąć tę playlistę?");

    if(prompt == true){
        $.post("includes/handlers/ajax/deletePlaylist.php", {playlistId: playlistId}).done(function(error){
            if(error != ""){
                alert(error);
                return;
            }
            openPage("yourMusic.php");
        });
    }
}

function showOptionMenu(button){
    var songId = $(button).prevAll(".songId").val();
    var menu = $(".optionMenu");
    var menuWidth =menu.width();
    menu.find(".songId").val(songId); //bierze menu, znajduje element songId i jej ustawia,ustala wartość

    var scrollTop = $(window).scrollTop(); //odległość od góry okna dokumentu
    var elementOffset = $(button).offset().top; //odległość od góry dokumentu
    var top = elementOffset - scrollTop;
    var left = $(button).position().left;
    menu.css({"top": top + "px", "left": left - menuWidth + "px", "display": "inline"});
} //pokazywanie paska opcji 

function hideOptionMenu(){
    var menu = $(".optionMenu");
    if(menu.css("display") != "none"){
        menu.css("display", "none")
    }
} //jesli pasek opcji nie jest wyświetlany, nie wyświetli sie 

function removeFromPlaylist(button, playlistId){
    var songId = $(button).prevAll(".songId").val();

    $.post("includes/handlers/ajax/removeFromPlaylist.php", {playlistId: playlistId, songId: songId}).done(function(error){
        if(error != ""){
            alert(error);
            return;
        }
        openPage("playlist.php?id=" + playlistId);
    });
}

function formatTime(seconds){
    var time = Math.round(seconds); //zaokraglij do najbliszej liczby calkowitej, czas calkowity w sekundach
    var minutes = Math.floor(time/60); //zaokraglij w dol do najbliższej liczby całkowitej, minuty
    var seconds = time - (minutes * 60); //sekundy

    var extraZero;

    if(seconds < 10){
        extraZero = "0";
    } //jezeli jest mniej niz 10 sekund, wyswietl dodatkowe 0
    else{
        extraZero = "";
    } //w przeciwnym razie nic nie wyswietlaj
    return minutes + ":" + extraZero + seconds;
} //funkcja, ktora ma za zadanie formatowanie czasu odtwarzania

function updateTimeProgresBar(audio){
    $(".progressTime.current").text(formatTime(audio.currentTime)); //zaktualizuj aktualna wartosc czasu, zwroc biezaca pozycje odtwarzania
    $(".progressTime.remaining").text(formatTime(audio.duration - audio.currentTime)); //zaktualizuj pozostaly czas odtwarzania

    var progress = audio.currentTime / audio.duration * 100; //jaki procent stanowi aktualny czas odtwarzania do calkowitego czasu
    $(".playbackBar .progress").css("width", progress + "%"); //zaktualizuj pasek postepu
}

function updateVolumeProgressBar(audio){
    var volume = audio.volume * 100; //jaki procent paska glosnosci stanowi aktualna glosnosc odtwarzanego utworu
    $(".volumeBar .progress").css("width", volume + "%"); //zaktualizuj pasek glosnosci 
}

function playFirstSong(){
    setTrack(tempPlaylist[0], tempPlaylist, true);
}

function Audio(){
        this.currentlyPlaying;
        this.audio = document.createElement('audio');

        this.audio.addEventListener("ended", function(){
            nextSong();
        }); //jezeli wystapilo zdarzenie ended (gdy odtwarzana piosenka skonczyla sie), odtwarzaj kolejna piosenke

        this.audio.addEventListener("canplay", function(){
            var duration = formatTime(this.duration); //formatuj czas odtwarzania
            $(".progressTime.remaining").text(duration); 
        }); /*jezeli wystapilo zdarzenie canplay (gdy przegladarka moze rozpoczac odtwarzanie audio), 
            aktualizuj pozostaly czas odtwarzania
            this odnosi się to do obiektu, ktore wywolano zdarzenie*/

        this.audio.addEventListener("timeupdate", function(){
            if(this.duration){
                updateTimeProgresBar(this);
            } //aktualizuj pasek postepu
        }); //jezeli wystapilo zdarzenie timeupdate (pozycja odtwarzania audio ulegla zmianie)

        this.audio.addEventListener("volumechange" , function(){
            updateVolumeProgressBar(this); //aktualizuj pasek glosnosci
        }); //jezeli wystapilo zdarzenie volumechange (gdy zmienila sie glosnosc)

        this.setTrack = function (track) {
            this.currentlyPlaying = track;
            this.audio.src = track.path; //pobierz zrodlo pliku dzwiekowego
        }

        this.play = function () {
            this.audio.play();
        } //odtwarzaj element audio

        this.pause = function(){
            this.audio.pause();
        } //wstrzymaj odtwarzanie elementu audio

        this.setTime = function(seconds){
            this.audio.currentTime = seconds;
        } //ustaw aktualny czas odtwarzania 

}

function logout(){
    $.post("includes/handlers/ajax/logout.php", function(){
        location.reload(); //zaladuj ponownie biezacy dokument
    });
}

function updateEmail(emailClass){
    var emailValue = $("." + emailClass).val();

    $.post("includes/handlers/ajax/updateEmail.php", {email: emailValue, username: userLoggedIn})
    .done(function(response){
        $("." + emailClass).nextAll(".message").text(response); 
    });
}

function updatePassword(oldPasswordClass, newPasswordClass1, newPasswordClass2){
    var oldPassword = $("." + oldPasswordClass).val();
    var newPassword1 = $("." + newPasswordClass1).val();
    var newPassword2 = $("." + newPasswordClass2).val();

    $.post("includes/handlers/ajax/updatePassword.php", {oldPassword: oldPassword, newPassword1: newPassword1, newPassword2: newPassword2, username: userLoggedIn})
    .done(function(response){
        $("." + oldPasswordClass).nextAll(".message").text(response); 
    });
}

