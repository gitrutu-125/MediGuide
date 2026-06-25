<?php
include('includes/connection.php');

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

if(isset($_POST['submit'])){
    $token = md5(uniqid());

    // ✅ USER INPUT
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $email = $_POST['email'];

    // ✅ INSERT INTO DATABASE
    $insert = mysqli_query($con, "
    INSERT INTO ambulance_orders (name, mobile, address, city, email, token, status)
    VALUES ('$name', '$mobile', '$address', '$city', '$email', '$token', 'Pending')
    ");

    if($insert){

        // ✅ SEND EMAIL TO ADMIN
        $mail = new PHPMailer(true);

        try{
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'itsrutu1250@gmail.com'; // CHANGE
            $mail->Password = 'cbiktratphhjerrl';    // CHANGE
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom($_POST['email'], 'Ambulance Service');
            $mail->addAddress('itsrutu1250@gmail.com', 'Miss. Rutu'); // ADMIN EMAIL

            $mail->isHTML(true);
            $mail->Subject = "🚑 New Ambulance Request";

           $confirm_link = "http://localhost/medi/confirm_booking.php?token=$token";
           //$confirm_link = "http://192.168.1.42/medi/confirm_booking.php?token=".$token;

            $mail->Body = "
            <h3>🚑 New Ambulance Booking</h3>

            <b>Name:</b> $name <br>
            <b>Mobile:</b> $mobile <br>
            <b>Address:</b> $address <br>
            <b>City:</b> $city <br><br>

            <a href='$confirm_link' 
            style='padding:10px 20px; background:green; color:white; text-decoration:none;'>
            ✅ Confirm Booking
            </a>
            ";

            $mail->send();

        } catch (Exception $e){
            echo "Mailer Error: " . $mail->ErrorInfo;
        }

        echo "<script>alert('🚑 Request Sent! Waiting for admin confirmation');</script>";
    } else {
        echo "Error: " . mysqli_error($con);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Book Ambulance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <!-- FORM -->
    <div class="card p-4 shadow col-md-6 mx-auto">
        <h3 class="text-center mb-3">🚑 Book Ambulance</h3>

        <form method="POST">

            <div class="mb-2">
                <label>Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Mobile</label>
                <input type="text" name="mobile" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Address</label>
                <input type="text" name="address" class="form-control" required>
            </div>

            <div class="mb-2">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Select City</label>
                <select name="city" class="form-control" required>
                    <option value="">-- Select City --</option>
                    <option value="Pune">Pune</option>
                    <option value="Mumbai">Mumbai</option>
                    <option value="Delhi">Delhi</option>
                </select>
            </div>

            <button type="submit" name="submit" class="btn btn-danger w-100">
                Book Ambulance
            </button>

        </form>
    </div>

    <!-- STATUS -->
    <div class="card mt-4 p-3 shadow col-md-6 mx-auto">
        <h5>📊 Booking Status</h5>

        <?php
        if(isset($_POST['mobile'])){
            $mobile = $_POST['mobile'];

            $result = mysqli_query($con, "
            SELECT * FROM ambulance_orders 
            WHERE mobile='$mobile'
            ORDER BY id DESC LIMIT 1
            ");

            if(mysqli_num_rows($result) > 0){
                $row = mysqli_fetch_assoc($result);

                if($row['status'] == 'Pending'){
                    echo "<b style='color:orange;'>⏳ Waiting for Confirmation...</b>";
                }
                else if($row['status'] == 'Confirmed'){
                    echo "<b style='color:green;'>🚑 Ambulance is coming in 30 minutes!</b>";
                }
            } else {
                echo "No booking yet.";
            }
        } else {
            echo "Enter mobile and book to see status.";
        }
        ?>
    </div>

</div>

</body>
</html>