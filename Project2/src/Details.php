<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Details</title>
    <link href="../css/details.css" rel="stylesheet" type="text/css">
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
    var pic = localStorage.getItem("src").split("/")[6];
    let xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange=function(){
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            detail = xmlhttp.responseText;
        }
    }
    msg = new Array("9" , pic);
    xmlhttp.open("GET", "Searchfunc.php?q=" + msg, true);
    xmlhttp.send();
    setTimeout(function(){
        let a = detail.split("|");
        document.getElementById("title").innerText = a[0];
        document.getElementById("conte").innerText = "Content : " + a[1];
        document.getElementById("country").innerText = "country : " + a[2];
        document.getElementById("half").innerText = "City : " +  a[3];
        document.getElementById("derp").innerText = "Description : " + a[4];
        document.getElementById("pic").src = a[6];
        document.getElementById("like").innerText = a[5];
    }, 1000);
    function logout() {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.open("GET", "userfunc.php?q=4" , true);
        xmlhttp.send();
        setTimeout(function () {
            window.location.href = "/Project2/Index.php";
        }, 1000);
    }
    function favor() {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.open("GET", "userfunc.php?q=5" + pic , true);
        xmlhttp.send();
        setTimeout(function () {
            window.location.href = "/Project2/src/favor.php";
        }, 1000);
    }
</script>
<header>
    <h3>
        <nav>
            <a href="../Index.php">Home</a>
            <a href="Browser.php">Browse</a>
            <a href="Search.php">Search</a>
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
<div class="content">
    <div class="header"><h4>Details</h4></div>
    <div class="title" ><h3 id="title"></h3></div>
    <img id="pic" src="../images/details.jpg">
    <div id="right">
        <div class="likenumbers">
            <h4>Like number</h4>
            <em id="like"></em>
        </div>
        <div class="imgdetails">
            <h4>Image Details</h4>
            <p id="conte"></p>
            <p id="country"></p>
            <p id="half"></p>
        </div>
        <button onclick="favor()"><img src="../images/favor.png">收藏</button>
    </div>
    <p id="derp"></p>
</div>
</body>
<footer>
    <p>联系方式：18017529810 备案号：19302010024</p>
    <p>Copyright © Webfundamental All rights reserved</p>
</footer>
</html>