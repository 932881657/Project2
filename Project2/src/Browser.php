 <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browser</title>
    <link href="../css/Browser.css" rel="stylesheet" type="text/css">
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

$country = array();
$connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
$sql = "select * from geocountries_regions";
if ($result = mysqli_query($connection, $sql)) {
    while($row = mysqli_fetch_assoc($result)) {
        array_push($country,$row);
    }
    mysqli_free_result($result);
}
mysqli_close($connection);
?>
<script defer="defer">
    function change() {
        let x = document.getElementById("country");
        let y = document.getElementById("city");
        if(x.selectedIndex != 0){
            y.options.length = 0;
            var city;
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    city = xmlhttp.responseText;
                }
            }
            let msg = "7" + x.selectedIndex;
            xmlhttp.open("GET", "Searchfunc.php?q=" + msg, true);
            xmlhttp.send();
            setTimeout(function () {
                for (let i = 0; i < city.split("|").length; i++) {
                    y.options.add(new Option(city.split("|")[i], i));
                }
            }, 500);
        }
        else{
            y.options.length = 0;
            y.options.add(new Option("--"), 0);
        }
    }
    var imgsrc;
    function searchmsg(x) {
        //从后端获取图片信息,并存储imgsrc中
        let msg;
        switch (x) {
            case 0 : {
                msg = new Array("0",document.getElementById("searchtxt").value);
                if (msg == "") {
                    document.getElementById("searchtxt").innerHTML = "No input";
                    return;
                }
                break;
            }
            case 1 : msg = new Array("1");break;
            case 2 : msg = new Array("2");break;
            case 3 : msg = new Array("3");break;
            case 4 : msg = new Array("4");break;
            case 5 : msg = new Array("5");break;
            case 6 : msg = new Array("6");break;
            case 8 :{
                let x = document.getElementById("country");
                let z = document.getElementById("conte");
                if(z.selectedIndex == 0){
                    if(x.selectedIndex == 0){
                        msg = new Array("z");
                    }
                    else{
                        msg = new Array("8" ,document.getElementById("city").options[document.getElementById("city").selectedIndex].text);
                    }
                }
                else if(x.selectedIndex == 0){
                    switch (z.selectedIndex) {
                        case 1 : msg = new Array("1");break;
                        case 2 : msg = new Array("2");break;
                        case 3 : msg = new Array("3");break;
                        case 4 : msg = new Array("4");break;
                        case 5 : msg = new Array("5");break;
                        case 6 : msg = new Array("6");break;
                    }
                }
                else{
                    switch (z.selectedIndex) {
                        case 1 : msg = new Array("a",document.getElementById("city").options[document.getElementById("city").selectedIndex].text);break;
                        case 2 : msg = new Array("b",document.getElementById("city").options[document.getElementById("city").selectedIndex].text);break;
                        case 3 : msg = new Array("c",document.getElementById("city").options[document.getElementById("city").selectedIndex].text);break;
                        case 4 : msg = new Array("d",document.getElementById("city").options[document.getElementById("city").selectedIndex].text);break;
                        case 5 : msg = new Array("e",document.getElementById("city").options[document.getElementById("city").selectedIndex].text);break;
                        case 6 : msg = new Array("f",document.getElementById("city").options[document.getElementById("city").selectedIndex].text);break;
                    }
                }
                break;
            }
            case "I" : msg = new Array("I");break;
            case "C" : msg = new Array("C");break;
            case "G" : msg = new Array("G");break;
            case "D" : msg = new Array("D");break;
            case "z" : msg = new Array("z"); //加载全部图片

        }
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                imgsrc = xmlhttp.responseText;
            }
        }
        xmlhttp.open("GET", "Searchfunc.php?q=" + msg, true);
        xmlhttp.send();
        setTimeout(function(){
            printtable(imgsrc.split("|"));
        }, 1000);
    }
    var currentpage = 0;
    var totalpage;
    var pagesize = 12;
    function printtable(imgsrc) {
        if (imgsrc.length == 0) {
            document.getElementById("table").innerHTML = null;
            alert("Nothing Found!")
        } else {
            var totalcount = imgsrc.length;
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
            for (let i = 1; i < 4; i++) {
                for (let j = 1; j < 5; j++) {
                    document.getElementById("tp" + i + j).style.display = "inline-block";
                }
            }
            for (let i = 1; i < 4; i++) {
                for (let j = 1; j < 5; j++) {
                    if (imgsrc[mark + a] != null) {
                        document.getElementById("tp" + i +""+ j).src = imgsrc[mark + a];
                    } else {
                        document.getElementById("tp" + i +""+ j).style.display = "none";
                    }
                    a++;
                }
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
        printtable(imgsrc.split("|"));
    }
    function nextpage() {
        currentpage = (totalpage - currentpage > 0)?currentpage+1 : totalpage;
        printtable(imgsrc.split("|"));
    }
    function page(id) {
        currentpage = parseInt(document.getElementById(id).innerText);
        printtable(imgsrc.split("|"));
    }
    function firstclick() {
        currentpage = 1;function storepicsrc(id){
            var src = document.getElementById(id).src.toString();
            localStorage.setItem("src",src);
        }
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
    searchmsg("z");
</script>
<header>
    <h3>
        <nav>
            <a href="../Index.php">Home</a>
            <a id="highlight" href="Browser.php">Browse</a>
            <a href="Search.php">Search</a>
            <div class="menu">
                <div class="menu" id="menu">
                    <?php
                    if($logged){
                        echo "<span style='white-space: nowrap'>My account</span>";
                        echo "<div class=\"menu_item\">";
                        echo "<img src=\"../images/upload.png\" height=\"18px\" width=\"18px\"><a href=\"Upload.php\">Upload</a><br>";
                        echo "<img src=\"../images/my_photo.png\" height=\"18px\" width=\"18px\"><a href=\"my_photo.php\">My_photo</a><br>";
                        echo "<img src=\"../images/favor.png\" height=\"18px\" width=\"18px\"><a href=\"my_photo.php\">Favor</a><br>";
                        echo "<img src=\"../images/login.png\" height=\"18px\" width=\"18px\"><a onclick='logout()'>Logout</a><br>";
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
<div id="body">
    <div id="left">
        <form class="content" action="">
            <div class="header"><h4>Search by Title</h4></div>
            <div id="a"><input id="searchtxt" type="text" placeholder="Please enter a title"><button  type="button" onclick="searchmsg(0);firstclick()"><img id="searchimg" src="../images/search.png"></button></div>
        </form>
        <div class="content">
            <div class="header"><h4>Hot Content</h4></div>
            <ul >
                <li><a onclick="searchmsg(1);firstclick();">Scenery</a></li>
                <li><a onclick="searchmsg(2);firstclick();">City</a></li>
                <li><a onclick="searchmsg(3);firstclick();">People</a></li>
                <li><a onclick="searchmsg(4);firstclick();">Animal</a></li>
                <li><a onclick="searchmsg(5);firstclick();">Building</a></li>
                <li><a onclick="searchmsg(6);firstclick();">Wonder</a></li>
            </ul>
        </div>
        <div class="content">
            <div class="header"><h4>Hot Country</h4></div>
            <ul >
                <li><p onclick="searchmsg('C');firstclick();">Canada</p></li>
                <li><p onclick="searchmsg('I');firstclick();">Italy</p></li>
                <li><p onclick="searchmsg('G');firstclick();">United Kingdom</p></li>
                <li><p onclick="searchmsg('D');firstclick();">Germany</p></li>
            </ul>
        </div>
    </div>
    <div id="right">
        <div class="header"><h4>Filter</h4></div>
        <div>
            <select id="conte">
                <option value="">--</option>
                <option value="scenery" >Scenery</option>
                <option value="city">City</option>
                <option value="people">People</option>
                <option value="animal">Animal</option>
                <option value="building">Building</option>
                <option value="wonder">Wonder</option>
            </select>
            <select id="country" onchange="change()">
                <option>--</option>
                <?php
                $num = count($country);
                for($i = 0 ; $i < $num ; $i++){
                    $a = $country[$i]['Country_RegionName'];
                    echo "<option>$a</option>";
                }
                ?>
            </select>
            <select id="city">
                <option>--</option>
            </select>
            <button onclick="searchmsg(8);firstclick();">Filter</button>
        </div>
        <div id="table">
            <table>
                <tr>
                    <td><a href="Details.php"><img id="tp11" onclick="storepicsrc('tp11')"></a></td>
                    <td><a href="Details.php"><img id="tp12" onclick="storepicsrc('tp12')"></a></td>
                    <td><a href="Details.php"><img id="tp13" onclick="storepicsrc('tp13')"></a></td>
                    <td><a href="Details.php"><img id="tp14" onclick="storepicsrc('tp14')"></a></td>
                </tr>
                <tr>
                    <td><a href="Details.php"><img id="tp21" onclick="storepicsrc('tp21')"></a></td>
                    <td><a href="Details.php"><img id="tp22" onclick="storepicsrc('tp22')"></a></td>
                    <td><a href="Details.php"><img id="tp23" onclick="storepicsrc('tp23')"></a></td>
                    <td><a href="Details.php"><img id="tp24" onclick="storepicsrc('tp24')"></a></td>
                </tr>
                <tr>
                    <td><a href="Details.php"><img id="tp31" onclick="storepicsrc('tp31')"></a></td>
                    <td><a href="Details.php"><img id="tp32" onclick="storepicsrc('tp32')"></a></td>
                    <td><a href="Details.php"><img id="tp33" onclick="storepicsrc('tp33')"></a></td>
                    <td><a href="Details.php"><img id="tp34" onclick="storepicsrc('tp34')"></a></td>
                </tr>
            </table>
        </div>
        <div id="page"><p class="page" id="prepage" onclick="prepage()"><</p><p class="page"id="page1" onclick="page('page1')">1</p><p class="page" id="page2"onclick="page('page2')">2</p><p class="page" id="page3" onclick="page('page3')">3</p><p class="page" id="page4" onclick="page('page4')">4</p><p class="page" id="page5" onclick="page('page5')">5</p><p id="nextpage"class="page" onclick="nextpage()">></p></div>
    </div>
</div>
</body>
<footer>
    <p>联系方式：18017529810 备案号：19302010024 Copyright © Webfundamental All rights reserved</p>
</footer>
</html>