<?php
require_once('config.php');
// 允许上传的图片后缀
$upload = true;
$title = $_POST['title'];
$desc = $_POST['desc'];
$content = $_POST['content'];
$country = $_POST['country'];
$cityindex = $_POST['city'];
if($title == null  || $desc == null || $content === "--" || $country === "--"){
    $upload = false;
    echo 'please complete the form';
}else{
    $newPath = rand(1000000000000,time());
    $allowedExts = array("gif", "jpeg", "jpg", "png");
    $temp = explode(".", $_FILES["file"]["name"]);
    $extension = end($temp);     // 获取文件后缀名
    if ((($_FILES["file"]["type"] == "image")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/jpg")
            || ($_FILES["file"]["type"] == "image/pjpeg")
            || ($_FILES["file"]["type"] == "image/x-png")
            || ($_FILES["file"]["type"] == "image/png"))
        && in_array($extension, $allowedExts)) {
        if ($_FILES["file"]["error"] > 0) {
            echo "error: " . $_FILES["file"]["error"] . "<br>";
        }
        else {
            if (file_exists("../travel-images/upload" . $_FILES["file"]["name"])) {
                echo $_FILES["file"]["name"] . "image existed";
                $upload = false;
            }
            else {
                move_uploaded_file($_FILES["file"]["tmp_name"], "../travel-images/square-medium" . $_FILES["file"]["$newPath"]);
            }
        }
    }
    else
    {
        echo "illegal type";
        $upload = false;
    }
    if($upload){
        $json_login = file_get_contents("src/login.json");
        $login = json_decode($json_login , true);
        $uid = $login[2];
        $id = 0;
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select * from geocountries_regions";
        $city = array();
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                if($row['Country_RegionName'] == $country){
                   $iso = $row['ISO'];
                }
            }
            mysqli_free_result($result);
        }
        $sql = "select * from geocities";
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                if($row['Country_RegionCodeISO'] == $iso){
                    array_push($city , $row['GeoNameID']);
                }
            }
            mysqli_free_result($result);
        }
        $citycode = $city[$cityindex];
        $sql = "select * from travelimage";
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                if(row['ImageID'] >= $id){
                    $id = row['ImageID'];
                }
            }
            mysqli_free_result($result);
        }
        $id++;
        $sql = "INSERT INTO travelimage (ImageID, Title, Description ,CityCode , UID ,PATH,Content) //向数据库插入数据
                    VALUES ('$id', '$title','$desc'  , '$citycode' ,'$uid' , '$newPath' , '$content')";
        $judge = $connection->query($sql);
        if ($judge){
             echo 'Upload successfully';
        } else {
        echo 'INSERT attempt failed' ;
        }
            mysqli_close($connection);
            Header("Location: my_photo.php");
    }
}
?>
