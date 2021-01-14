<html>
    <head>
        <title>Mastery Tracker</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <form method="get">
        <label for="region">Region:</label>
        <select class="input" id="region" name="region" style="border-radius: 5px;" required>
        <option value="BR1">BR</option>
        <option value="EUN1" selected>EUNE</option>
        <option value="EUW1">EUW</option>
        <option value="JP1">JP</option>
        <option value="KR">KR</option>
        <option value="LA1">LAN</option>
        <option value="LA2">LAS</option>
        <option value="NA1">NA</option>
        <option value="OC1">OCE</option>
        <option value="RU">RU</option>
        <option value="TR1">TR</option>
        </select>
        <label for="summonerName">Summoner name:</label>
        <input type="text" class="input" id="summonerName" name="summonerName" style="border-radius: 5px;" required>
        <input type="submit" class="input" value="Summon!" style="border-radius: 5px;">
        </form>
    <?php include("script.php"); ?>
    </body>
</html>