<?php 
    include("includes/includedFiles.php"); 
?>

<div class="pageHeadingBig">
    <h1>Polecane dla Ciebie</h1>
</div>
<div class="gridViewContainer">
    <?php
        $albumQuery = mysqli_query($con, "SELECT * FROM albums ORDER BY RAND() LIMIT 8"); //wybierz 8 randamowych albumow
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
