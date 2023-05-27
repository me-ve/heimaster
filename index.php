<!DOCTYPE HTML>
<html>
    <head>
        <title>Heimaster</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="http://fonts.cdnfonts.com/css/helvetica-neue-9">
    </head>
    <body>
        <p id="logo">
            HEIMASTER
        </p>
    <?php
    $currentDate = date("Y-m-d H:i:s");
    if (!(isset($_GET["summonerName"], $_GET["region"]))) {
        include "form.html";
    }
    require "mainScript.php";
    ?>
    <p id="refreshTime">
        Last refreshed in <?= $currentDate ?>
    </p>
    </body>
</html>
