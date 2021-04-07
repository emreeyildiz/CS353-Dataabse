<?php
include ("config.php");
session_start();
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $inputCid = $_POST["inputCid"];
    $studentId = $_SESSION["sid"];
    $deleteQuery = "DELETE FROM apply WHERE sid = '$studentId' AND cid = '$inputCid'";
    if (isset($db)) {
        $query1 = mysqli_query($db, $deleteQuery);
    }
    $updateQuery = "UPDATE company SET quota = quota + 1 WHERE cid = '$inputCid'";
    if (isset($db)) {
        $query2 = mysqli_query($db, $updateQuery);
        if(!$query1 && !$query2){
            echo mysqli_error($db);
            exit();
        }
        echo "<script LANGUAGE = 'JavaScript'> window.alert('deleted'); window.location.href = 'welcome.php' </script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Accounts</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
        p { margin-bottom: 10px; }
        th, td { padding: 5px; text-align: left; }
    </style>
</head>
<body style="background-color: whitesmoke">
<div class="container">
    <nav class="navbar navbar-expand-md navbar-dark bg-success">
        <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Back Page</a>
                </li>
                <span class="nav-item disabled">
                    <a class="nav-link" href="logout.php">Logout</a>
            </span>
            </ul>

        </div>

    </nav>
    <div class="panel container-fluid">
        <h3 class="page-header" style="font-weight: bold;">Applied Internships</h3>
        <?php
        $query3 = "SELECT cid, cname,quota FROM student NATURAL JOIN apply NATURAL JOIN company WHERE sid=".$_SESSION['sid'];
        $queryOut = mysqli_query($db, $query3);
        if(!$queryOut){
            echo mysqli_error($db);
            exit();
        }

        echo "<table class=\"table table-lg table-striped\">
    <tr>
    <th>Company ID</th>
    <th>Company Name</th>
    <th>Quota</th>
    <th>Cancel</th>
    </tr>";


        while($tuple = mysqli_fetch_array($queryOut)) {
            echo "<tr>";
            echo "<td>" . $tuple['cid'] . "</td>";
            echo "<td>" . $tuple['cname'] . "</td>";
            echo "<td>" . $tuple['quota'] . "</td>";
            echo "<td> <form action=\"\" METHOD=\"POST\">
            <button type=\"submit\" name = \"inputCid\"class=\"btn btn-danger btn-sm\" value =".$tuple['cid'] .">X</button>
            </form>
             
          </td>";
            echo "</tr>";
        }
        echo "</table>";

        ?>
    </div>
    <p><a href="apply.php" class="btn btn-primary">Apply Internship</a></p>
</div>



</body>
</html>

