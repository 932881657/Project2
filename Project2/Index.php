<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link href="/Project2/css/Home.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes" />
</head>
<?php
        require_once('src/config.php');
        $logged = false;
        $username = "";
        $uid = "";
        $json_login = file_get_contents("src/login.json");
        $login = json_decode($json_login , true);
        $logged = $login[0];
        $username = $login[1];
        $uid = $login[2];

        //获取收藏数量最多图片的imageID
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select * from travelimagefavor";
        $pics = array();
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                array_push($pics,$row['ImageID']);
            }
            mysqli_free_result($result);
        }
        $map = array_count_values($pics);
        $mostpic = array(0,0);
        foreach($map as $key=>$value){
            if($value > $mostpic[0]){
                $mostpic[0] = $value;
                $mostpic[1] = $key;
            }
        }
        //获取收藏最多图片的位置
        $sql = "select * from travelimage";
        $picsrc = "";
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                if($row['ImageID'] == $mostpic[1]){
                    $picsrc = "/Project2/travel-images/large/" . $row['PATH'];
                    break;
                }
            }
        }
        mysqli_close($connection);

        //随机读取一组图片，更新到主页
        function refresh(){
            //将所有imageid重新排序，去掉没有path的错误项
            $random = array();
            $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
            $sql = "select * from travelimage";
            if ($result = mysqli_query($connection, $sql)) {
                while($row = mysqli_fetch_assoc($result)) {
                    if($row['PATH'] != null){
                        array_push($random,$row['ImageID']);
                    }
                }
            }
            mysqli_close($connection);
            shuffle($random);
            //获取前六个imageid对应的信息
            $picsrcs = array();
            $titles = array();
            $descriptions = array();
            for($a = 0 ; $a < 6 ; $a++){
                $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
                if ($result = mysqli_query($connection, $sql)) {
                    while($row = mysqli_fetch_assoc($result)) {
                        if($row['ImageID'] === $random[$a]){
                            $picsrcs[$a] = "/Project2/travel-images/square-medium/" . $row['PATH'];
                            $titles[$a] = $row['Title'];
                            $descriptions[$a] = $row['Description'];
                            break;
                        }
                    }
                }
                mysqli_close($connection);
            }
            return array($picsrcs , $titles , $descriptions);
        }
        $picinf = refresh();
?>

<header>
    <h3>
        <nav>
            <a id="highlight" href="Index.php">Home</a>
            <a href="/Project2/src/Browser.php">Browse</a>
            <a href="/Project2/src/Search.php">Search</a>
            <div class="menu" id="menu">
                <?php
                if($logged == true){
                    echo "<span>My account</span>";
                    echo "<div class=\"menu_item\">";
                    echo "<img src=\"/images/upload.png\" height=\"18px\" width=\"18px\"><a href=\"src/Upload.php\">Upload</a><br>";
                    echo "<img src=\"/images/my_photo.png\" height=\"18px\" width=\"18px\"><a href=\"src/my_photo.php\">My_photo</a><br>";
                    echo "<img src=\"/images/favor.png\" height=\"18px\" width=\"18px\"><a href=\"src/my_photo.php\">Favor</a><br>";
                    echo "<img src=\"/images/logout.png\" height=\"18px\" width=\"18px\"><a onclick='logout()'>Logout</a><br>";
                    echo "</div>";
                }
                else{
                    echo "<a href='/Project2/src/login.php'><span >Login</span></a>";
                }
                ?>
            </div>
        </nav>
    </h3>
</header>
<body>
<img id="homepage" src= <?php echo $picsrc;?> alt="homepage">
<table>
    <tr>
        <td><div><a href="src/Details.php"><img id="pic1" class="pic" onclick="storepicsrc('pic1')" src=<?php echo $picinf[0][0];?>></a><h4><?php echo $picinf[1][0];?></h4><p><?php echo $picinf[2][0];?></p></div></td>
        <td><div><a href="src/Details.php"><img id="pic2" class="pic" onclick="storepicsrc('pic2')" src=<?php echo $picinf[0][1];?>></a><h4><?php echo $picinf[1][1];?></h4><p><?php echo $picinf[2][1];?></p></div></td>
        <td><div><a href="src/Details.php"><img id="pic3" class="pic" onclick="storepicsrc('pic3')" src=<?php echo $picinf[0][2];?>></a><h4><?php echo $picinf[1][2];?></h4><p><?php echo $picinf[2][2];?></p></div></td>
    </tr>
    <tr>
        <td><div><a href="src/Details.php"><img id="pic4" class="pic" onclick="storepicsrc('pic4')" src=<?php echo $picinf[0][3];?>></a><h4><?php echo $picinf[1][3];?></h4><p><?php echo $picinf[2][3];?></p></div></td>
        <td><div><a href="src/Details.php"><img id="pic5" class="pic" onclick="storepicsrc('pic5')" src=<?php echo $picinf[0][4];?>></a><h4><?php echo $picinf[1][4];?></h4><p><?php echo $picinf[2][4];?></p></div></td>
        <td><div><a href="src/Details.php"><img id="pic6" class="pic" onclick="storepicsrc('pic6')" src=<?php echo $picinf[0][5];?>></a><h4><?php echo $picinf[1][5];?></h4><p><?php echo $picinf[2][5];?></p></div></td>
    </tr>
</table>
<div class="float">
    <button><a href="#top"><img src="/Project2/images/top.png"></a></button><br>
    <button><a href="javascript:location.reload();"><img src="/Project2/images/refresh.png"></a></button>
</div>
</body>
<footer>
    <img src="/Project2/images/wechat.jpg">
    <p>联系方式：18017529810 备案号：19302010024</p>
    <p>Copyright © Webfundamental All rights reserved</p>
</footer>
<script>
    function logout() {
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.open("GET", "src/userfunc.php?q=4" , true);
        xmlhttp.send();
        setTimeout(function () {
            location.reload();
        }, 1000);
    }
    function storepicsrc(id){
        var src = document.getElementById(id).src.toString();
        localStorage.setItem("src",src);
    }

</script>
</html>
