<?php 

    ob_start(); //uruchom buforowanie danych
    session_start(); //uruchom sesja

    $timezone = date_default_timezone_set("Europe/Warsaw"); //ustaw domyslna strefe czasowa 

    $con = mysqli_connect("localhost", "root", "root", "schema"); //ustaw zmienna, ktora tworzy nowe poloczenie z baza danych

    if(mysqli_connect_errno()){
        echo "Failed to connect: " . mysqli_connect_errno(); //sprawdz polaczenie
    }

?>