<?php
require_once('config.php');

$msg = $_GET["q"];
$picsrcs = array();
$des = substr($msg , 2);
$ind = substr($msg , 1);
switch ($msg[0]){
    case "z" : { //输出所有图片
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select * from travelimage";
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                if($row['PATH'] != null){
                    array_push($picsrcs,"../travel-images/square-medium/" . $row['PATH']);
                }
            }
            mysqli_free_result($result);
        }
        mysqli_close($connection);
        echo implode("|" , $picsrcs);
        break;
    }
    case "0" :{ //模糊搜索
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select * from travelimage where Title like '%$des%'";
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                if($row['PATH'] != null){
                    array_push($picsrcs,"../travel-images/square-medium/" . $row['PATH']);
                }
            }
            mysqli_free_result($result);
        }
        mysqli_close($connection);
        echo implode("|" , $picsrcs);
        break;
    }
    case "1" : searchcontent("scenery" , $picsrcs);break; //根据content搜索
    case "2" : searchcontent("city" , $picsrcs);break;
    case "3" : searchcontent("people", $picsrcs);break;
    case "4" : searchcontent("animal", $picsrcs);break;
    case "5" : searchcontent("building", $picsrcs);break;
    case "6" : searchcontent("wonder", $picsrcs);break;
    case "7" : {
        $country = array(); //搜索国家对应的城市
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select * from geocountries_regions";
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                array_push($country,$row['ISO']);
            }
            mysqli_free_result($result);
        }
        $city = array();
        $sql = "select * from geocities";
        $a = $country[(substr($msg , 1))-1];
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                if($row['Country_RegionCodeISO'] == $a){
                    array_push($city,$row['AsciiName']);
                }
            }
            mysqli_free_result($result);
        }
        mysqli_close($connection);
        echo implode("|" , $city);
        break;
    }
    case "8" : { //搜索城市
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select * from geocities";
        $citycode = "";
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                if($row['AsciiName'] == $des){
                    $citycode = $row['GeoNameID'];
                }
            }
            mysqli_free_result($result);
        }
        $sql = "select * from travelimage";
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                if($row['CityCode'] == $citycode){
                    if($row['PATH'] != null){
                        array_push($picsrcs,"../travel-images/square-medium/" . $row['PATH']);
                    }
                }
            }
            mysqli_free_result($result);
        }
        mysqli_close($connection);
        echo implode("|" , $picsrcs);
        break;
    }
    case "9" : {
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select * from travelimage";
        $detail = array();
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                if($row['PATH'] == $des){
                    array_push($detail , $row['Title']);
                    array_push($detail , $row['Content']);
                    array_push($detail , $row['Country_RegionCodeISO']);
                    array_push($detail , $row['CityCode']);
                    array_push($detail , $row['Description']);
                    array_push($detail , $row['ImageID']);
                    array_push($detail , "../travel-images/square-medium/" .$row['PATH']);
                }
            }
            mysqli_free_result($result);
        }
        $sql = "select * from geocountries_regions where ISO = '$detail[2]'";
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
               $detail[2] = $row['Country_RegionName'];
            }
            mysqli_free_result($result);
        }
        $sql = "select * from geocities where GeoNameID = '$detail[3]'";
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                $detail[3] = $row['AsciiName'];
            }
            mysqli_free_result($result);
        }
        $sql = "select * from travelimagefavor";
        $favor = 0;
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                if($row['ImageID'] == $detail[5]){
                    $favor++;
                }
            }
            mysqli_free_result($result);
        }
        $detail[5] = $favor;
        mysqli_close($connection);
        echo implode("|" , $detail);
        break;
    }
    case "a" : findall($des , "scenery");break; //考虑contenet搜索城市
    case "b" : findall($des , "city");break;
    case "c" : findall($des , "people");break;
    case "d" : findall($des , "animal");break;
    case "e" : findall($des , "building");break;
    case "f" : findall($des , "wonder");break;
    case "I" : {
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select * from travelimage where Country_RegionCodeISO = 'IT'";
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                array_push($picsrcs ,"../travel-images/square-medium/" . $row['PATH'] );
            }
            mysqli_free_result($result);
        }
        mysqli_close($connection);
        echo implode("|" , $picsrcs);
        break;
    }
    case "C" : {
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select * from travelimage where Country_RegionCodeISO = 'CA'";
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                array_push($picsrcs ,"../travel-images/square-medium/" . $row['PATH'] );
            }
            mysqli_free_result($result);
        }
        mysqli_close($connection);
        echo implode("|" , $picsrcs);
        break;
    }
    case "G" : {
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select * from travelimage where Country_RegionCodeISO = 'GB'";
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                array_push($picsrcs ,"../travel-images/square-medium/" . $row['PATH'] );
            }
            mysqli_free_result($result);
        }
        mysqli_close($connection);
        echo implode("|" , $picsrcs);
        break;
    }
    case "D" : {
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select * from travelimage where Country_RegionCodeISO = 'DE'";
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                array_push($picsrcs ,"../travel-images/square-medium/" . $row['PATH'] );
            }
            mysqli_free_result($result);
        }
        mysqli_close($connection);
        echo implode("|" , $picsrcs);
        break;
    }
    case "t" : {
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select * from travelimage where Title like '%$ind%'";
        $answer = array();
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                if($row['PATH'] != null){
                    array_push($answer,$row['Title']);
                    array_push($answer,$row['Description']);
                    array_push($answer,"../travel-images/square-medium/" . $row['PATH']);
                }
            }
            mysqli_free_result($result);
        }
        mysqli_close($connection);
        echo implode("|" , $answer);
        break;
    }
    case "s" : {
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select * from travelimage where Description like '%$ind%'";
        $answer = array();
        if ($result = mysqli_query($connection, $sql)) {
            while($row = mysqli_fetch_assoc($result)) {
                if($row['PATH'] != null){
                    array_push($answer,$row['Title']);
                    array_push($answer,$row['Description']);
                    array_push($answer,"../travel-images/square-medium/" . $row['PATH']);
                }
            }
            mysqli_free_result($result);
        }
        mysqli_close($connection);
        echo implode("|" , $answer);
        break;
    }
    case "m" : {
        $json_login = file_get_contents("src/login.json");
        $login = json_decode($json_login , true);
        $uid = $login[2];
        $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
        $sql = "select * from travelimage where UID = '$uid'";
        $answer = array();
        if ($result = mysqli_query($connection, $sql)) {
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($answer,$row['Title']);
                array_push($answer,$row['Description']);
                array_push($answer,"../travel-images/square-medium/" . $row['PATH']);
            }
            mysqli_free_result($result);
        }
        mysqli_close($connection);
        echo implode("|" , $answer);
        break;
    }
    default:{}
}
function searchcontent($content , $picsrcs){//判断要寻找的content
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    $sql = "select * from travelimage";
    if ($result = mysqli_query($connection, $sql)) {
        while($row = mysqli_fetch_assoc($result)) {
            if(($row['PATH'] != null) && ($row['Content'] === $content)){
                array_push($picsrcs,"../travel-images/square-medium/" . $row['PATH']);
            }
        }
        mysqli_free_result($result);
    }
    mysqli_close($connection);
    echo implode("|" , $picsrcs);
}
function findall($des ,$content){
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    $sql = "select * from geocities";
    $citycode = "";
    if ($result = mysqli_query($connection, $sql)) {
        while($row = mysqli_fetch_assoc($result)) {
            if($row['AsciiName'] == $des){
                $citycode = $row['GeoNameID'];
            }
        }
        mysqli_free_result($result);
    }
    $sql = "select * from travelimage";
    if ($result = mysqli_query($connection, $sql)) {
        while($row = mysqli_fetch_assoc($result)) {
            if($row['CityCode'] == $citycode && $row['Content'] == $content){
                if($row['PATH'] != null){
                    array_push($picsrcs,"../travel-images/square-medium/" . $row['PATH']);
                }
            }
        }
        mysqli_free_result($result);
    }
    mysqli_close($connection);
    echo implode("|" , $picsrcs);
}
function searchcountry($countryid){
    $connection = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
    $sql = "select * from travelimage where Country_RegionCodeISO = '$countryid'";
    if ($result = mysqli_query($connection, $sql)) {
        while($row = mysqli_fetch_assoc($result)) {
            array_push($picsrcs ,"../travel-images/square-medium/" . $row['PATH'] );
        }
        mysqli_free_result($result);
    }
    mysqli_close($connection);
    echo implode("|" , $picsrcs);
}

?>
