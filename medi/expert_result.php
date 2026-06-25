<?php
include('includes/connection.php');
include('expert_system.php');

$symptoms = $_POST['symptoms'] ?? [];
$severity = $_POST['severity'] ?? 'low';

$score = [];

foreach($symptoms as $symptom){
    $query = mysqli_query($con, "
        SELECT department FROM symptom_rules 
        WHERE symptom = '$symptom'
    ");

    while($row = mysqli_fetch_assoc($query)){
        $dept = $row['department'];
        $score[$dept] = ($score[$dept] ?? 0) + 1;
    }
}

arsort($score);
$top_department = array_key_first($score);
$type = getHospitalType($severity);

$query = mysqli_query($con, "
    SELECT * FROM hospitals 
    WHERE specialization = '$top_department'
    OR type = '$type'
");
?>

<!DOCTYPE html>
<html>
<head>
<title>AI Result</title>

<style>
body {
    font-family: Arial;
    background: #020617;
    color: white;
    padding: 20px;
}

.header {
    text-align: center;
    margin-bottom: 30px;
}

.card {
    background: #1e293b;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    transition: 0.3s;
}

.card:hover {
    transform: scale(1.02);
    background: #334155;
}

.score-bar {
    background: #334155;
    border-radius: 8px;
    overflow: hidden;
    margin: 5px 0;
}

.score-fill {
    background: #22c55e;
    padding: 5px;
    text-align: right;
}

.badge {
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 12px;
}

.emergency { background: red; }
.specialist { background: orange; }
.normal { background: green; }

</style>

</head>

<body>

<div class="header">
    <h1>🏥 AI Hospital Recommendation</h1>
</div>

<!-- AI Analysis -->
<div class="card">
    <h2>🧠 AI Analysis</h2>

    <p><strong>Top Department:</strong> <?php echo $top_department; ?></p>
    <p><strong>Severity:</strong> <?php echo ucfirst($severity); ?></p>

    <h3>Confidence Scores:</h3>

    <?php foreach($score as $dept => $val){ ?>
        <div>
            <?php echo $dept; ?>
            <div class="score-bar">
                <div class="score-fill" style="width: <?php echo $val * 30; ?>%">
                    <?php echo $val; ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<!-- Hospital Results -->
<h2>🏥 Recommended Hospitals</h2>

<?php while($row = mysqli_fetch_assoc($query)) { ?>

<div class="card">
    <h3><?php echo $row['name']; ?></h3>

    <p>📍 Location: <?php echo $row['location']; ?></p>
    <p>🩺 Specialization: <?php echo $row['specialization']; ?></p>

    <?php
    $class = strtolower($row['type']);
    ?>

    <span class="badge <?php echo $class; ?>">
        <?php echo $row['type']; ?>
    </span>
</div>

<?php } ?>

</body>
</html>