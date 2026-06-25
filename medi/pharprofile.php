<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>User Details</title>
    <link rel="stylesheet" type="text/css" href="css/font-awesome.min.css" media="all" />
    <link rel="stylesheet" type="text/css" href="css/normalize.css" media="all" />
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css" media="all" />
    <link rel="stylesheet" type="text/css" href="style.css" media="all" />
    <link rel="shortcut icon" href="img/Graphicloads-Medical-Health-Medicine-box-2.ico">
    <script type="text/javascript" src="js/modernizr.js"></script>
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.js"></script>
</head>

<body class="login">
    <div class="header-area">
        <div class="header-top">
            <div class="container">
                <a href="#"><img src="img/client-1295901_960_720.png" style="max-height: 5%;max-width: 5%;margin-left: 50%;opacity:1.0;"></a>
                <div class="menu col-md-5" style="margin-left: 20%;margin-top: 2%">
                    <ul class="list-unstyled list-inline pull-right">
                        <li><a href="#">Home</a></li>
                        <li><a href="index.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <?php
    // Establish database connection
    $con = mysqli_connect("localhost", "root", "", "medicineguide");
    
    // Check connection
    if (!$con) {
        die('Connection Failed: ' . mysqli_connect_error());
    }

    $puName = $phuMobile = $phuAdd = $puRegion = "";

    $query = "SELECT * FROM pharmacy WHERE pid = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $_SESSION["uname"]);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (!$result) {
        echo "Error: " . mysqli_error($con);
    } else {
 
        if ($row=mysqli_fetch_assoc($result)) {
            $puName = $row["pName"];
            $phuMobile = $row["phMobile"];
            $phuAdd = $row["phAddress"];
            $puRegion = $row["pRegion"];
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($con);
    ?>

    <div class="main-area">
        <div class="">
            <form class="col-md-6 col-sm-offset-3 text-center">
                <h2>Pharmacy Panel - User Details</h2>
                <div class="form-group center">
                    <label>Username: </label>
                    <label><?php echo htmlspecialchars($puName); ?></label>
                    <br>
                    <label>Mobile: </label>
                    <label><?php echo htmlspecialchars($phuMobile); ?></label>
                    <br>
                    <label>Address: </label>
                    <label><?php echo htmlspecialchars($phuAdd); ?></label>
                    <br>
                    <label>Region: </label>
                    <label><?php echo htmlspecialchars($puRegion); ?></label>
                    <br>
                </div>
                <br>
                <a href="changepass.php">Change Password</a>
                <br>
            </form>
            <br>
        </div>
    </div>
</body>

</html>
