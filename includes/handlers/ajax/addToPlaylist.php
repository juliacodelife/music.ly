<?php

    include("../../config.php"); 

    if(isset($_POST['playlistId']) && isset($_POST['songId'])){
        $playlistId = $_POST['playlistId'];
        $songId = $_POST['songId'];

        $orderQuery = mysqli_query($con, "SELECT IFNULL(MAX(playlistOrder) + 1, 1) AS playlistOrder FROM playlistsongs WHERE playlistId='$playlistId'");
        $row = mysqli_fetch_array($orderQuery);
        $order = $row['playlistOrder'];
        $query = mysqli_query($con, "INSERT INTO playlistsongs VALUES('', '$songId', '$playlistId', '$order')");

    } //jesli playlistId i songId jest ustwione dodaj piosenke do danej playlisty
    else{
        echo "PlaylistId or songId was not passed into addToPlaylist.php";
    } //w przeciwnym razie wyswietl komunikat


?>