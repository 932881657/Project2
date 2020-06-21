<?php
require_once('config.php');
$msg = $_GET["q"];
$name = substr($msg , 1);
switch ($msg[0]){
    case "0" : { //验证用户名是否重复并注册
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select * from traveluser";
        $repeat = true;
        $uid = 1;
        $user = explode("|" , $name);
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                if($row['UserName'] == $user[1]){
                    $repeat = false;
                }
                $uid++;
            }
            mysqli_free_result($result);
        }
        if($repeat){
            $sql = "INSERT INTO traveluser (UserName, Email, Pass , UID)
                    VALUES ($user[1], $user[2], $user[3] ,$uid)";
        }
        $connection->query($sql);
        mysqli_close($connection);
        echo $repeat;
        break;
    }
    case "1" : {//登录验证
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select * from traveluser";
        $user = explode("/" , $name);
        $login = false;
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                if($row['UserName'] === $user[1]){
                    if($row['Pass'] === $user[2]){
                        $login = true;
                        $logged = true;
                        $username = $user[1];
                        $sql = "select * from traveluser where UserName = '$username'";
                        if ($result = mysqli_query($connection, $sql)) {
                            while($row = mysqli_fetch_assoc($result)) {
                                $uid = $row['UID'];
                            }
                        }
                        $json_login = json_encode(array($logged , $username , $uid));
                        file_put_contents('login.json' , $json_login);
                    }
                }
               else if($row['Email'] === $user[1]){
                    if($row['Pass'] === $user[2]){
                        $login = true;
                        $logged = true;
                        $sql = "select * from traveluser where Email = '$user[1]'";
                        if ($result = mysqli_query($connection, $sql)) {
                            while($row = mysqli_fetch_assoc($result)) {
                                $uid = $row['UID'];
                                $username = $row['UserName'];
                            }
                        }
                        $json_login = json_encode(array($logged , $username , $uid));
                        file_put_contents('login.json' , $json_login);
                    }
               }
            }
        }
        mysqli_close($connection);
        echo $login;
        break;
    }
    case "2" : { //用户删除上传照片
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "delete from travelimage where PATH = '$name'";
        $judge = $connection->query($sql);
        mysqli_close($connection);
        if ($judge){
            echo 'Delete successfully';
        } else {
            echo 'Delete attempt failed' ;
        }
        break;
    }
    case "3" : { //删除喜欢照片
        $json_login = file_get_contents("src/login.json");
        $login = json_decode($json_login , true);
        $uid = $login[2];
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select * from travelimage";
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                if($row['PATH'] == $name){
                    $imageid = $row['ImageID'];
                }
            }
            mysqli_free_result($result);
        }
        $sql = "select * from travelimagefavor";
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                if($row['UID'] == $uid && $row['ImageID'] == $imageid){
                    $favorid = $row['FavorID'];
                }
            }
            mysqli_free_result($result);
        }
        $sql = "delete from travelimagefavor where FavorID = '$favorid' ";
        $judge = $connection->query($sql);
        mysqli_close($connection);
        if ($judge){
            echo 'Delete successfully';
        } else {
            echo 'Delete attempt failed' ;
        }
        break;
    }
    case "4" :{ //logout
        $logged = false;
        $uid= "";
        $username = "";
        $json_login = json_encode(array($logged , $username , $uid));
        file_put_contents('login.json' , $json_login);
        break;
    }
    case "5" :{//favor
        $json_login = file_get_contents("login.json");
        $login = json_decode($json_login , true);
        $uid = $login[2];
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select from travelimage where PATH = '$name' ";
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                $picid = $row['ImageID'];
            }
            mysqli_free_result($result);
        }
        $sql = "INSERT INTO travelimagefavor (UID, ImageID) //向数据库插入数据
                    VALUES ('$uid' ,$picid )";
        $judge = $connection->query($sql);
        mysqli_close($connection);
        break;
    }
}


