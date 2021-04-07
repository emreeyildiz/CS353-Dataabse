<?php
include ("config.php");
session_start();
$query = "SELECT COUNT(*) AS cnt FROM apply WHERE sid=".$_SESSION['sid'];
$queryOut = mysqli_query($db, $query);
if(!$queryOut){
    echo mysqli_error($db);
    exit();
}
$row = mysqli_fetch_array($queryOut);
$flag = true;
$num_of_application = $row['cnt'];
if($num_of_application == 3){
    $flag = false;
    echo "<script LANGUAGE = 'JavaScript'> window.alert('Already applied 3 internship'); window.location.href = 'welcome.php' </script>";

}
if($_SERVER["REQUEST_METHOD"] == "POST"){
    $flag = true;
    $inputCid = $_POST['cid'];
    $student_id = $_SESSION['sid'];

    $inputFail = "SELECT COUNT(*) AS cnt FROM company WHERE cid = '$inputCid'";
    $queryOut = mysqli_query($db, $inputFail);
    if(!$queryOut){
        echo mysqli_error($db);
        exit();
    }

    $count1 = mysqli_fetch_array($queryOut)['cnt'];
    if($count1 == 0){
        $flag = false;
        echo "<script LANGUAGE = 'JavaScript'> window.alert('No company'); window.location.href = 'apply.php' </script>";
    }

    $query1 = "SELECT quota FROM company WHERE cid = '$inputCid'";
    $queryOut = mysqli_query($db, $query1);
    if(!$queryOut){
        echo mysqli_error($db);
        exit();
    }
    $row = mysqli_fetch_array($queryOut);
    $quota_count = $row['quota'];

    if($quota_count < 1){
        $flag = false;
        echo "<script LANGUAGE = 'JavaScript'> window.alert('No available quota'); window.location.href = 'apply.php' </script>";

    }

    $query2 = "SELECT COUNT(*) as cnt FROM apply WHERE sid IN (SELECT sid FROM apply WHERE cid = '$inputCid' AND sid = '$student_id')";
    $queryOut = mysqli_query($db, $query2);
    if(!$queryOut){
        echo mysqli_error($db);
        exit();
    }
    $row = mysqli_fetch_array($queryOut);
    $numberOfApplication = $row['cnt'];
    if($numberOfApplication > 0){
        $flag = false;
        echo "<script LANGUAGE = 'JavaScript'> window.alert('Already applied company'); window.location.href = 'apply.php' </script>";
    }


    if($flag == true){
        $tableQuery = "UPDATE company SET quota = quota -1 WHERE cid = '$inputCid'";
        $queryOut = mysqli_query($db, $tableQuery);
        if(!$queryOut){
            echo mysqli_error($db);
            exit();
        }

        $query3 = "INSERT INTO apply VALUES ('$student_id', '$inputCid')";
        $queryOut = mysqli_query($db, $query3);
        if(!$queryOut){
            echo mysqli_error($db);
            exit();
        }
        echo "<script LANGUAGE = 'JavaScript'> window.alert('Successful'); window.location.href = 'welcome.php' </script>";

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
        #centerwrapper { text-align: center; margin-bottom: 10px; }
        #centerdiv { display: inline-block; }
    </style>
</head>\
<body style="background-color: whitesmoke">
<div class="container">
    <nav class="navbar navbar-expand-md navbar-dark bg-success">
        <div class="navbar-collapse collapse w-100 order-2 dual-collapse2">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="welcome.php">Back Page</a>
                </li>
                <li class="nav-item disabled">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>

    </nav>
    <div class="panel container-fluid">
        <h3 class="page-header" style="font-weight: bold;">Available Company List</h3>
        <?php
        echo "<table class=\"table table-lg table-striped\">
        <tr>
        <th> Company ID </th>
        <th class = 'text-center'> Company Name </th>
        <th class = 'text-center'> Available Quota </th>
        </tr>";
        $query4 = "SELECT * FROM company as com WHERE quota > 0 AND NOT EXISTS 
    (SELECT  * FROM apply WHERE com.cid = cid AND sid=" .$_SESSION['sid'].")";
        if(!$query4){
            echo mysqli_error($db);
            exit();
        }

        $queryOut = mysqli_query($db, $query4);
        while($tuple = mysqli_fetch_array($queryOut)) {
            echo "<tr>";
            echo "<td>" . $tuple['cid'] . "</td>";
            echo "<td class = 'text-center'>" . $tuple['cname'] . "</td>";
            echo "<td class='text-center'>" . $tuple['quota'] . "</td>";
            echo "<tr>";
        }
        echo "</table>";
        ?>
    </div>

    <form action="" METHOD="POST">
        <div class = "form-row">
            <input type="text"  class="form-control col-md-4" name="cid" placeholder="Company ID">
            <button type="submit" class="btn btn-primary btn-sm"> Apply </button>
        </div>
    </form>
</div>