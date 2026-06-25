<?php
session_start();
include('includes/connection.php');
include('expert_system.php');

$msg = strtolower($_POST['message']);

// Initialize session
if(!isset($_SESSION['symptoms'])){
    $_SESSION['symptoms'] = [];
}

// ============================
// 🤖 STEP 1: Detect Symptoms
// ============================
$all_symptoms = ["fever","cough","chest pain","injury","headache"];

foreach($all_symptoms as $sym){
    if(strpos($msg, $sym) !== false){
        if(!in_array(ucfirst($sym), $_SESSION['symptoms'])){
            $_SESSION['symptoms'][] = ucfirst($sym);
        }
    }
}

// ============================
// 🤖 STEP 2: Detect Severity
// ============================
if(strpos($msg, "high") !== false){
    $_SESSION['severity'] = "high";
} elseif(strpos($msg, "medium") !== false){
    $_SESSION['severity'] = "medium";
} elseif(strpos($msg, "low") !== false){
    $_SESSION['severity'] = "low";
}

// ============================
// 🤖 STEP 3: AI Prediction
// ============================
if(count($_SESSION['symptoms']) > 0 && isset($_SESSION['severity'])){

    $symptoms = $_SESSION['symptoms'];
    $severity = $_SESSION['severity'];

    // 🧠 Scoring system
    $score = [];

    foreach($symptoms as $symptom){
        $query = mysqli_query($con, "
            SELECT department FROM symptom_rules 
            WHERE symptom = '$symptom'
        ");

        while($row = mysqli_fetch_assoc($query)){
            $dept = $row['department'];

            if(isset($score[$dept])){
                $score[$dept]++;
            } else {
                $score[$dept] = 1;
            }
        }
    }

    arsort($score);
    $top_department = array_key_first($score);

    $type = getHospitalType($severity);

    $query = mysqli_query($con, "
        SELECT name FROM hospitals 
        WHERE specialization = '$top_department'
        OR type = '$type'
        LIMIT 3
    ");

    // 🤖 Smart AI Response
    $response = "🧠 Based on your symptoms, you may need <b>$top_department</b> care.<br><br>";
    $response .= "🏥 Recommended Hospitals:<br>";

    while($row = mysqli_fetch_assoc($query)){
        $response .= "👉 " . $row['name'] . "<br>";
    }

    $response .= "<br>💡 Tip: If symptoms worsen, please visit immediately.";

    // Reset session
    session_destroy();

    echo $response;

} else {

    echo "🤖 Please tell me your symptoms and severity (low/medium/high).";
}
?>