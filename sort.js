class Champion {
    constructor(position, image, name, level, points, avg_percent, tier_score, tier, progress, chest, tokens, last_played, date) {
        this.position = position;
        this.image = image;
        this.name = name;
        this.level = level;
        this.points = points;
        this.avg_percent = avg_percent;
        this.tier_score = tier_score;
        this.tier = tier;
        this.progress = progress;
        this.chest = chest;
        this.tokens = tokens;
        this.last_played = last_played;
        this.date = date;
    }
}
//TODO generate events for sorting in row0 for each column
function getCell(columnName, index) {
    let cell = document.getElementById(`${columnName}[${index}]`);
    return cell.valueOf();
}
function getTextContentFromCell(columnName, index) {
    let cell = getCell(columnName, index);
    return cell.textContent;
}
function getChampionFromRow(index) {
    return new Champion(
        getTextContentFromCell("position", index),
        getCell("image", index).children[0].src,
        getTextContentFromCell("name", index),
        getTextContentFromCell("level", index),
        getTextContentFromCell("points", index),
        getTextContentFromCell("partofavg", index),
        getTextContentFromCell("tierscore", index),
        getTextContentFromCell("tier", index),
        getTextContentFromCell("progress", index),
        getTextContentFromCell("chests", index),
        getTextContentFromCell("tokens", index),
        getCell("date", index).dataset.time,
        getTextContentFromCell("date", index)
    );
}
let championsCount = document.getElementById("champions").children[0].children.length - 1;

function getTierValue(tier) {
    switch (tier) {
        case "S+":
            return 4;
        case "S":
            return 3;
        case "S-":
            return 2;
        case "A":
            return 1;
        case "B":
            return 0;
        case "C":
            return -1;
        case "D+":
            return -2;
        case "D":
            return -3;
        case "D-":
            return -4;
    }
}

function SortBy(arg) {
    let Champions = [];
    for (let i = 1; i <= championsCount; i++) {
        Champions[i - 1] = getChampionFromRow(i);
    }
    //TODO make reversable
    switch (arg) {
        case "#":
            Champions.sort(function (a, b) {
                return a.position - b.position;
            });
            break;
        case "Champion":
        case "Icon":
            Champions.sort(function (a, b) {
                return a.name.localeCompare(b.name);
            });
            break;
        case "Level":
            Champions.sort(function (a, b) {
                return b.level - a.level;
            });
            break;
        case "Points":
        case "% of average":
            Champions.sort(function (a, b) {
                let a_pts = a.points.replace(',', '');
                let b_pts = b.points.replace(',', '');
                return b_pts - a_pts;
            });
            break;
        case "Tier Score":
            Champions.sort(function (a, b) {
                return b.tier_score - a.tier_score;
            });
            break;
        case "Tier":
            Champions.sort(function (a, b) {
                return getTierValue(b.tier) - getTierValue(a.tier);
            });
            break;
        case "Progress":
            Champions.sort(function (a, b) {
                a_pr = a.progress == "N/A" ? 101 : a.progress.replace('%', '');
                b_pr = b.progress == "N/A" ? 101 : b.progress.replace('%', '');
                return b_pr - a_pr;
            });
            break;
        case "Chest":
            Champions.sort(function (a, b) {
                let a_chest = a.chest == "yes" ? 1 : 0;
                let b_chest = b.chest == "yes" ? 1 : 0;
                return b_chest - a_chest;
            });
            break;
        case "Tokens":
            Champions.sort(function (a, b) {
                return b.tokens - a.tokens;
            });
            break;
        case "Last played":
            Champions.sort(function (a, b) {
                return b.last_played.localeCompare(a.last_played);
            });
            break;
    }
    return Champions;
};

function ReorganizeTable(arg) {
    let SortedChampions = SortBy(arg);
    for (let index = 1; index <= championsCount; index++) {
        let champion = SortedChampions[index - 1];
        document.getElementById(`position[${index}]`).innerText = champion.position;
        document.getElementById(`image[${index}]`).innerHTML =
            `<img src=\"${champion.image}\" class=\"championImage\" alt=\"${champion.name}\">`;
        document.getElementById(`name[${index}]`).innerText = champion.name;
        document.getElementById(`level[${index}]`).innerText = champion.level;
        if (champion.level >= 5) {
            document.getElementById(`level[${index}]`).style = 'color: #ceb572;';
        } else {
            document.getElementById(`level[${index}]`).style = '';
        }
        document.getElementById(`points[${index}]`).innerText = champion.points;
        document.getElementById(`partofavg[${index}]`).innerText = champion.avg_percent;
        document.getElementById(`tierscore[${index}]`).innerText = champion.tier_score;
        document.getElementById(`tier[${index}]`).innerText = champion.tier;
        document.getElementById(`progress[${index}]`).innerText = champion.progress;
        document.getElementById(`chests[${index}]`).innerText = champion.chest;
        if (champion.chest == "yes") {
            document.getElementById(`chests[${index}]`).style = 'background-color: #ceb572;';
        } else {
            document.getElementById(`chests[${index}]`).style = 'background-color: #6c7b8b;';
        }
        document.getElementById(`tokens[${index}]`).innerText = champion.tokens;
        document.getElementById(`date[${index}]`).innerText = champion.date;
        document.getElementById(`date[${index}]`).dataset.time = champion.last_played;
    }
}

row0 = document.getElementById("row[0]").children;
for (let cell of row0) {
    let text = (cell.innerText);
    if (text != null) {
        cell.addEventListener("click", function () {
            ReorganizeTable(text);
        });
    }
}
