<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="../css/login.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes" />
</head>
<script>
    function login(){
        let pass = document.getElementById("pass").value;
        let name = document.getElementById("name").value;
        let xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                answer = xmlhttp.responseText;
            }
        }
        let msg = "1/" + name + "/" + pass;
        xmlhttp.open("GET", "userfunc.php?q=" + msg, true);
        xmlhttp.send();
        setTimeout(function () {
            if(!answer){
                alert("用户名或密码错误");
            }
            else{
                alert("登录成功");
                window.location.href = "/Project2/Index.php";
            }
        }, 1000);
    }
</script>
<body>
<div id="content">
    <h3>Sign in for Fisher</h3>
    <div id="login">
        <p >Username/Email:</p>
        <input id="name" type="text"><br>
        <p>Password:</p>
        <input id="pass" type="password"><br>
        <button onclick="login()"><a>Sign in</a></button>
    </div>
    <div id="register"><p>New to Fisher?</p><a href="register.php">Creat a new Account?</a></div>
</div>
</body>
<footer>
    <p>联系方式：18017529810 备案号：19302010024Copyright © Webfundamental All rights reserved</p>
</footer>
</html>