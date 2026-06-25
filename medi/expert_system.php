<?php
include('includes/connection.php');

// STEP 2: Get Departments based on symptoms
function getDepartment($symptoms, $con) {
    $departments = [];

    foreach($symptoms as $symptom){
        $query = mysqli_query($con, "
            SELECT department FROM symptom_rules 
            WHERE symptom = '$symptom'
        ");
        
        while($row = mysqli_fetch_assoc($query)){
            $departments[] = $row['department'];
        }
    }

    return array_unique($departments);
}

// STEP 2: Get Hospitals based on departments
function getHospitals($departments, $con) {
    $dept_list = implode("','", $departments);

    $query = mysqli_query($con, "
        SELECT * FROM hospitals 
        WHERE specialization IN ('$dept_list')
    ");

    return $query;
}


// ===============================
// 🧠 STEP 3: AI Decision Logic
// ===============================

// Decide hospital type based on severity
function getHospitalType($severity){
    if($severity == "high"){
        return "Emergency";
    } elseif($severity == "medium"){
        return "Specialist";
    } else {
        return "Normal";
    }
}
?>