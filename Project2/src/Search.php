<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search</title>
    <link href="../css/Search.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes" />
</head>
<?php
require_once('config.php');
$logged = false;
$username = "";
$uid = "";
$json_login = file_get_contents("login.json");
$login = json_decode($json_login , true);
$logged = $login[0];
$username = $login[1];
$uid = $login[2];
?>
<script>
    function search() {
        let title = new Array();
        let desc = new Array();
        let imgsrc = new Array();
        let msg;
        let answer;
        if (document.getElementById("searchtitle").checked) {
            msg = "t" + document.getElementById("inputtitle").value;
        } else if (document.getElementById("searchdes").checked) {
            msg = "s" + document.getElementById("inputdes").value;
        }
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                answer = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "Searchfunc.php?q=" + msg, true);
        xmlhttp.send();
        setTimeout(function () {
            let ans = answer.split("|");
            for (let i = 0; i < ans.length / 3; i++) {
                title[i] = ans[3*i];
                desc[i] = ans[3*i+1];
                imgsrc[i] = ans[3*i+2];
            }
            print(title,desc,imgsrc);
        }, 1000);
    }
    var currentpage = 0;
    var totalpage;
    var pagesize = 4;
    function print(title,desc,imgsrc) {
        if (title.length == 0) {
            alert("Nothing Found!")
        } else {
            var totalcount = title.length;
            if (totalcount % pagesize == 0) {
                totalpage = Math.floor(totalcount / pagesize);
            } else {
                totalpage = Math.floor(totalcount / pagesize + 1);
            }
            if (currentpage == 0) {
                currentpage = 1;
            }
            var mark = (currentpage - 1) * pagesize;
            var a = 0;
            for (let i = 1; i < 5; i++) {
                document.getElementById(i+"").style.display = "block";
            }
            for (let i = 1; i < 5; i++) {
                if (title[mark + a] != null) {
                    document.getElementById(i +"1").src = imgsrc[mark + a];
                    document.getElementById(i +"2").innerText = title[mark + a];
                    document.getElementById(i +"3").innerText = desc[mark + a];
                } else {
                    document.getElementById(i+"").style.display = "none";
                }
                a++;
            }

            for (var i = 1; i < 6; i++) {
                document.getElementById("page" + i).innerText = currentpage - 3 + i + "";
                if (document.getElementById("page" + i).innerText <= 0 || document.getElementById("page" + i).innerText > totalpage) {
                    document.getElementById("page" + i).innerText = "";
                }
            }
        }
    }
    function prepage() {
        currentpage = (currentpage > 1)?currentpage-1 : 1;
        print();
    }
    function nextpage() {
        currentpage = (totalpage - currentpage > 0)?currentpage+1 : totalpage;
        print();
    }
    function page(id) {
        currentpage = parseInt(document.getElementById(id).innerText);
        print();
    }
    function firstclick() {
        currentpage = 1;
    }
    function storepicsrc(id){
        var src = document.getElementById(id).src.toString();
        localStorage.setItem("src",src);
    }
    function logout() {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.open("GET", "userfunc.php?q=4" , true);
        xmlhttp.send();
        setTimeout(function () {
            window.location.href = "/Project2/Index.php";
        }, 1000);
    }
</script>
<header>
    <h3>
        <nav>
            <a href="../Index.php">Home</a>
            <a href="Browser.php">Browse</a>
            <a href="Search.php" id="highlight">Search</a>
            <div class="menu">
                <?php
                if($logged){
                    echo "<span>My account</span>";
                    echo "<div class=\"menu_item\">";
                    echo "<img src=\"/images/upload.png\" height=\"18px\" width=\"18px\"><a href=\"Upload.php\">Upload</a><br>";
                    echo "<img src=\"/images/my_photo.png\" height=\"18px\" width=\"18px\"><a href=\"my_photo.php\">My_photo</a><br>";
                    echo "<img src=\"/images/favor.png\" height=\"18px\" width=\"18px\"><a href=\"my_photo.php\">Favor</a><br>";
                    echo "<img src=\"/images/logout.png\" height=\"18px\" width=\"18px\"><a onclick='logout()'>Logout</a><br>";
                    echo "</div>";
                }
                else{
                    echo "<a href='login.php'><span >Login</span></a>";
                }
                ?>
            </div>
        </nav>
    </h3>
</header>
<body>
<div id="Search">
    <div><h4>Search</h4></div>
    <input id="searchtitle"type="radio" name="radio" value="title">Filter by Title<br>
    <textarea rows="1" id="inputtitle"></textarea><br>
    <input id="searchdes" type="radio" name="radio" value="description">Filter by Description<br>
    <textarea rows="8" id="inputdes"></textarea><br>
    <button onclick="search();firstclick();">Filter</button>
</div>

<div id="Result">
    <div><h4>Result</h4></div>
    <div id="1"class="description">
        <a href="Details.php"><img id="11" onclick="storepicsrc('11')"></a>
        <div class="right">
            <h3 id="12"></h3>
            <p id="13"></p>
        </div>
    </div>
    <div id="2" class="description">
        <a href="Details.php"><img id="21" onclick="storepicsrc('21')"></a>
        <div class="right">
            <h3 id="22"></h3>
            <p id="23"></p>
        </div>
    </div>
    <div id="3" class="description">
        <a href="Details.php"><img id="31" onclick="storepicsrc('31')"></a>
        <div class="right">
            <h3 id="32"></h3>
            <p id="33"></p>
        </div>
    </div>
    <div id="4" class="description">
        <a href="Details.php"><img id="41" onclick="storepicsrc('41')"></a>
        <div class="right">
            <h3 id="42"></h3>
            <p id="43"></p>
        </div>
    </div>
    <div id="page"><p class="page" id="prepage" onclick="prepage()"><</p><p class="page"id="page1" onclick="page('page1')">1</p><p class="page" id="page2"onclick="page('page2')">2</p><p class="page" id="page3" onclick="page('page3')">3</p><p class="page" id="page4" onclick="page('page4')">4</p><p class="page" id="page5" onclick="page('page5')">5</p><p id="nextpage"class="page" onclick="nextpage()">></p></div>
</div>
</body>
<footer>
    <p>联系方式：18017529810 备案号：19302010024Copyright © Webfundamental All rights reserved</p>
</footer>
</html>
