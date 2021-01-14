<html>
    <head>
        <title>Mastery Tracker</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <?php
    if(!(isset($_GET["summonerName"])&&isset($_GET["region"])))
    {
        include ("form.html");
    }
    include("script.php");
    ?>
    </body>
</html>