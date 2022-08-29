# Heimaster

The tracker for champions mastery in League of Legends.

Important note
--------------
I have not registered this site as a product in the Riot Developer Portal, so I cannot host this site directly using my API key. Because of that, you have to retrieve your own API key from [Riot Developer Portal](https://developer.riotgames.com/) if you want to use this site.
Then you need to create file .env file with line below:
```
API_KEY="(your API key)"
```
The development API key expires after 24 hours and has to be regenerated to use the site again.

What are these cryptic "Tier" and "Tier Score"?
-----------------------------------------------
Tier Score is a measure of how much points someone has on specified champion compared to other champions.
It is calculated using the formula:
```
f(x) = log3(x/avg) + ((level >= 6) + (level == 7))*0.5
```
where x is this champion's mastery and avg is average champions' mastery. For every level above 5 the bonus of 0.50 tier score is added.

Tier is set using the Tier Score. Tier Levels are specified as below:
| Level | Min   | Max   | ~Min % |
|-------|-------|-------|--------|
| S+    |  3.50 |       |  4,677 |
| S     |  2.50 |  3.49 |  1,559 |
| S-    |  1.50 |  2.49 |    520 |
| A     |  0.50 |  1.49 |    173 |
| B     | -0.49 |  0.49 |     58 |
| C     | -1.49 | -0.50 |     19 |
| D+    | -2.49 | -1.50 |      6 |
| D     | -3.49 | -2.50 |      2 |
| D-    |       | -3.50 |      0 |
