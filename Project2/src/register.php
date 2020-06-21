<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="../css/register.css" rel="stylesheet" type="text/css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=0.5, maximum-scale=2.0, user-scalable=yes" />
</head>
<script>
    function register() {
        let register = true;
        let reg = /^([a-zA-Z]|[0-9])(\w|\-)+@[a-zA-Z0-9]+\.([a-zA-Z]{2,4})$/;
        let email = document.getElementById("email ").value;
        if(!reg.test(email)){
            alert("邮箱格式错误");
            register = false;
        }
        if(document.getElementById("pass1").value != document.getElementById("pass2").value){
            alert("两次密码不一致");
            register = false;
        }
        if(register){
            let pass = document.getElementById("pass1").value;
            let name = document.getElementById("username").value;
            let xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function () {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    answer = xmlhttp.responseText;
                }
            }
            let msg = "0|" + name + "|" +email + "|" + pass;
            xmlhttp.open("GET", "userfunc.php?q=" + msg, true);
            xmlhttp.send();
            setTimeout(function () {
                if(!answer){
                    alert("用户名重复")
                    register = false;
                }
                else{
                    alert("注册成功！");
                    window.location.href = "login.php";
                }
            }, 500);
        }





    }

</script>
<body>
<div id="content">
    <h3>Sign in for Fisher</h3>
    <div id="login">
        <p>Username:</p>
        <input id="username" type="text"><br>
        <p>E-mail:</p>
        <input id="email "type="text"><br>
        <p>Password:</p>
        <input id="pass1" type="password"><br>
        <p>Confirm Your Password:</p>
        <input id="pass2" type="password"><br>
        <button><a onclick="register()">Sign up</a></button>
    </div>
</div>
</body>
</body>
<footer>
    <p>联系方式：18017529810 备案号：19302010024Copyright © Webfundamental All rights reserved</p>
</footer>
</html>