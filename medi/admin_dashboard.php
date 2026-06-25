<?php
include('includes/connection.php');

// ===================== QUERIES ===================== //

// Total Sales per Yearly
$yearly_query = mysqli_query($con, "
SELECT YEAR(created_at) as year, 
SUM(CAST(medprice AS UNSIGNED) * medquantity) as total 
FROM medicineorder 
GROUP BY YEAR(created_at)
");

if(!$yearly_query){
    die("Yearly Query Error: " . mysqli_error($con));
}

// Top Medicines
$top_query = mysqli_query($con, "
SELECT medicinename, COUNT(*) as count 
FROM medicineorder 
GROUP BY medicinename 
ORDER BY count DESC LIMIT 10
");

// Orders per City
$city_query = mysqli_query($con, "
SELECT orderregion as city, COUNT(*) as total 
FROM medicineorder 
GROUP BY orderregion
");

// Ambulance Usage
$ambulance_query = mysqli_query($con, "
SELECT city, COUNT(*) as total 
FROM ambulance_orders 
GROUP BY city
");

// ===================== FETCH DATA ===================== //

$years = $sales = [];
while($row = mysqli_fetch_assoc($yearly_query)){
    $years[] = $row['year'];
    $sales[] = $row['total'];
}

$med_names = $med_count = [];
while($row = mysqli_fetch_assoc($top_query)){
    $med_names[] = $row['medicinename'];
    $med_count[] = $row['count'];
}

$cities = $city_total = [];
while($row = mysqli_fetch_assoc($city_query)){
    $cities[] = $row['city'];
    $city_total[] = $row['total'];
}

$ambu_city = $ambu_total = [];
while($row = mysqli_fetch_assoc($ambulance_query)){
    $ambu_city[] = $row['city'];
    $ambu_total[] = $row['total'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-light">

<div class="container mt-5">

    <h2 class="text-center mb-4">📊 Admin Dashboard</h2>

    <div class="row">

        <!-- Monthly Sales -->
        <div class="col-md-6 mb-4">
            <div class="card p-3 shadow">
                <h5>💰 Yearly Sales</h5>
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <!-- Top Medicines -->
        <div class="col-md-6 mb-4">
            <div class="card p-3 shadow">
                <h5>💊 Top Medicines</h5>
                <canvas id="medicineChart"></canvas>
            </div>
        </div>

        <!-- Orders per City -->
        <div class="col-md-6 mb-4">
            <div class="card p-3 shadow">
                <h5>🌍 Orders per City</h5>
                <canvas id="cityChart"></canvas>
            </div>
        </div>

        <!-- Ambulance Usage -->
        <div class="col-md-6 mb-4">
            <div class="card p-3 shadow">
                <h5>🚑 Ambulance Usage</h5>
                <canvas id="ambulanceChart"></canvas>
            </div>
        </div>

    </div>
</div>

<script>

// ================= SALES CHART ================= //
new Chart(document.getElementById("salesChart"), {
    type: 'line',
    data: {
        labels: <?php echo json_encode($years); ?>,
        datasets: [{
            label: "Sales",
            data: <?php echo json_encode($sales); ?>
        }]
    }
});

// ================= TOP MEDICINES ================= //
new Chart(document.getElementById("medicineChart"), {
    type: 'bar',
    data: {
        labels: <?php echo json_encode($med_names); ?>,
        datasets: [{
            label: "Orders",
            data: <?php echo json_encode($med_count); ?>
        }]
    }
});

// ================= CITY ORDERS ================= //
new Chart(document.getElementById("cityChart"), {
    type: 'pie',
    data: {
        labels: <?php echo json_encode($cities); ?>,
        datasets: [{
            data: <?php echo json_encode($city_total); ?>
        }]
    }
});

// ================= AMBULANCE ================= //
new Chart(document.getElementById("ambulanceChart"), {
    type: 'doughnut',
    data: {
        labels: <?php echo json_encode($ambu_city); ?>,
        datasets: [{
            data: <?php echo json_encode($ambu_total); ?>
        }]
    }
});

</script>

</body>
</html>