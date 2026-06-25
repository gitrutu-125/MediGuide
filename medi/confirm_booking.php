<?php
include('includes/connection.php');

// 🔥 SHOW ERRORS (for debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'src/Exception.php';
require 'src/PHPMailer.php';
require 'src/SMTP.php';

if(isset($_GET['token'])){
    $token = $_GET['token'];

    // ✅ GET USER DATA
    $result = mysqli_query($con, "SELECT * FROM ambulance_orders WHERE token='$token'");

    if(mysqli_num_rows($result) > 0){

        $row = mysqli_fetch_assoc($result);

        $name = $row['name'];
        $email = $row['email'];
        $city = $row['city'];

        // ✅ UPDATE STATUS
        $update = mysqli_query($con, "
        UPDATE ambulance_orders 
        SET status='Confirmed' 
        WHERE token='$token'
        ");

        if($update){

            // ✅ SEND EMAIL TO USER
            $mail = new PHPMailer(true);

            try{
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'itsrutu1250@gmail.com'; // ✅ your email
                $mail->Password = 'cbiktratphhjerrl';            // ✅ app password
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;

                // ✅ FIXED HERE
                $mail->setFrom('itsrutu1250@gmail.com', 'Ambulance Service');

                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = "🚑 Booking Confirmed";

                $mail->Body = "
                Hello $name,<br><br>
                Your ambulance booking for <b>$city</b> is confirmed.<br><br>
                🚑 Ambulance is coming in <b>30 minutes</b>.<br><br>
                Thank you!
                ";

                if($mail->send()){
                    echo "<h2 style='color:green;'>✅ Booking Confirmed & Email Sent</h2>";
                } else {
                    echo "Mail Error: " . $mail->ErrorInfo;
                }

            } catch (Exception $e){
                echo "Mailer Exception: " . $mail->ErrorInfo;
            }

        } else {
            echo "Update Error: " . mysqli_error($con);
        }

    } else {
        echo "❌ Invalid Token";
    }

} else {
    echo "❌ Invalid Request";
}
?>