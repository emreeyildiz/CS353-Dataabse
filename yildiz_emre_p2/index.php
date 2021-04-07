<?php
include("config.php");
session_start();
$username = "";
$password = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if (isset($db)) {
        $username = mysqli_real_escape_string($db, $_POST["username"]);
        $password = mysqli_real_escape_string($db, $_POST["password"]);
        $sql = "SELECT sid FROM student WHERE sname = ? and sid = ?";

        if($stmt = mysqli_prepare($db, $sql)){
            mysqli_stmt_bind_param($stmt, "ss", $username, $password);
            if(mysqli_stmt_execute($stmt)){

                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1){
                    if(mysqli_stmt_fetch($stmt)){
                        session_start();
                        $_SESSION["sid"] = $password;
                        header("location: welcome.php");
                    }
                }
                else{
                    echo "<script type = 'text/javascript'>alert('Wrong Username or Password'); </script>";
                }

            }
        }

        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 50px sans-serif; }
        #centerwrapper { text-align: center; }
    </style>
</head>
<body style="background-color: whitesmoke">
<div class="container">
    <div id="centerwrapper">
        <div id="centerdiv">
            <br><br>
            <h1 style="font-size: 52px" >Summer Internship Application</h1>
            <h2>Login to Internship System</h2>

            <p>   </p>
            <form id="loginForm" action="" method="post" class="form-inline">
                <div class="form-group mb-2">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class = "col-sm-10">
                    <input type="text" name="username" class="form-control" id="username" placeholder="Username">
                    </div>
                </div>
                <div class="form-group mx-sm-3 mb-2">
                    <label class="sr-onl"></label>
                    <div class = "col-sm-10">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    </div>
                </div>
                <div class="form-group">
                    <input onclick="checkConditions()" class="btn btn-primary mb-2" value="Login">
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    function checkConditions(){
        if(document.getElementById("username").value === "" ||
            document.getElementById("password").value === ""){
            alert("Please fill all fields");
        }
        else{
            document.getElementById("loginForm").submit();
        }
    }
</script>
</body>
</html>