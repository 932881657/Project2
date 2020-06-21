<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Modify</title>
    <link href="../css/Upload.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes" />
</head>
<?php
require_once('config.php');
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
<header>
    <h3>
        <nav>
            <a href="../Index.php">Home</a>
            <a href="Browser.php">Browse</a>
            <a href="Search.php">Search</a>
            <div class="menu">
                <span>My account</span>
                <div class="menu_item">
                    <img src="../images/upload.png" height="18px" width="18px"><a href="Upload.php" >Upload</a><br>
                    <img src="../images/my_photo.png" height="18px" width="18px"><a href="my_photo.php">My_photo</a><br>
                    <img src="../images/favor.png" height="18px" width="18px"><a href="favor.php" >Favor</a><br>
                    <img src="../images/login.png" height="18px" width="18px"><a href="../Index.php" onclick="logout()">Logout</a><br>
                </div>
            </div>
        </nav>
    </h3>
</header>
<body>
<div id="content">
    <div><h4>Upload</h4></div>
    <div id="picture">
        <form action="modifyfunc.php" method="post" enctype="multipart/form-data">
            <label for="file">文件名：</label>
            <input type="file" name="file" id="file"><br>
            <div id="txt" style="text-align: left">
                <p>Title：</p>
                <input name="title">
                <p>Description：</p>
                <textarea name="desc" rows="15" id="desc"></textarea>
                <input name="picsrc" id="picname" style="display: none">
                <div>
                    <select id="cont" name="content">
                        <option value="">--</option>
                        <option value="scenery" >Scenery</option>
                        <option value="city">City</option>
                        <option value="people">People</option>
                        <option value="animal">Animal</option>
                        <option value="building">Building</option>
                        <option value="wonder">Wonder</option>
                        <option value="wonder">Other</option>
                    </select>
                    <select id="country" onchange="change()" name="country">
                        <option>--</option>
                        <?php
                        $num = count($country);
                        for($i = 0 ; $i < $num ; $i++){
                            $a = $country[$i]['Country_RegionName'];
                            echo "<option>$a</option>";
                        }
                        ?>
                    </select>
                    <select id="city" name="city">
                        <option>--</option>
                    </select>
                </div>
            </div>
            <input type="submit" name="submit"  value="Modify">
        </form>
    </div>
</div>
<script>
    var pic = localStorage.getItem("picsrc").split("/")[6];
    document.getElementById("picname").innerText = pic;
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
    function logout() {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.open("GET", "userfunc.php?q=4" , true);
        xmlhttp.send();
        setTimeout(function () {
            window.location.href = "/Project2/Index.php";
        }, 1000);
    }
</script>
</body>
<footer>
    <p>联系方式：18017529810 备案号：19302010024Copyright © Webfundamental All rights reserved</p>
</footer>
</html>