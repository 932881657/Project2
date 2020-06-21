<?php
require_once('config.php');
// 允许上传的图片后缀
$upload = true;
$title = $_POST['title'];
$desc = $_POST['desc'];
$content = $_POST['content'];
$country = $_POST['country'];
$cityindex = $_POST['city'];
global $picture;
$picture = $_POST['picname'];
if($title == null  || $desc == null || $content === "--" || $country === "--"){
    $upload = false;
    echo 'please complete the form';
}else{
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
            unlink("../travel-images/square-medium/" . $picture);
            move_uploaded_file($_FILES["file"]["tmp_name"], "../travel-images/square-medium" . $_FILES["file"]["$picture"]);
        }
    }
    else
    {
        echo "illegal type";
        $upload = false;
    }
    if($upload){
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
        $sql = "update travelimage set Title = '$title', Decription = '$desc' , CityCode = '$citycode' , Content = '$content' where PATH = '$picture'";
        if ($connection->query($sql) == TRUE) {
            echo 'Modify successfully';
        } else {
            echo 'INSERT attempt failed' ;
        }
        mysqli_close($connection);
        Header("Location: my_photo.php");
    }
}
?>
