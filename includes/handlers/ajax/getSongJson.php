<?php
    include("../../config.php");
    if(isset($_POST['songId'])) { 
        $songId = $_POST['songId']; 
        $query = mysqli_query($con, "SELECT * FROM songs WHERE id='$songId'"); //wybierz wszystko z tabeli songs gdzie id rowna sie $songId
        $resultArray = mysqli_fetch_array($query); //przeksztalc uzyskany wynik w tablice
        echo json_encode($resultArray); //przekonwertuj do formatu JSON i zwroc piosenke z wywolania AJAX
    } //jesli songId zostal ustawiony
?>