<html>
    <head>
        <title>Mastery Tracker</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <p id="logo">
            MASTERY TRACKER
        </p>
    <?php
    $currentDate = date("Y-m-d H:i:s");
    if(!(isset($_GET["summonerName"])&&isset($_GET["region"])))
    {
        include ("form.html");
    }
    include("mainScript.php");
    ?>
    <p id="refreshTime">
        <?php
        echo "Last refreshed in {$currentDate}";
        ?>
    </p>
    </body>
</html>