<!DOCTYPE html>
<html>
<head>
<title>MediGuide AI</title>

<style>
body {
    font-family: Arial;
    background: #0f172a;
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.container {
    background: #1e293b;
    padding: 30px;
    border-radius: 12px;
    width: 400px;
    box-shadow: 0 0 20px rgba(0,0,0,0.5);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
}

label {
    display: block;
    margin: 10px 0;
}

button {
    width: 100%;
    padding: 12px;
    background: #22c55e;
    border: none;
    color: white;
    font-size: 16px;
    border-radius: 8px;
    cursor: pointer;
}

button:hover {
    background: #16a34a;
}

.loader {
    display: none;
    text-align: center;
    margin-top: 10px;
}
</style>

<script>
function showLoader(){
    document.getElementById("loader").style.display = "block";
}
</script>

</head>

<body>

<div class="container">
    <h2>🧠 MediGuide AI</h2>

    <form action="expert_result.php" method="POST" onsubmit="showLoader()">

        <h3>Select Symptoms:</h3>

        <label><input type="checkbox" name="symptoms[]" value="Fever"> Fever</label>
        <label><input type="checkbox" name="symptoms[]" value="Cough"> Cough</label>
        <label><input type="checkbox" name="symptoms[]" value="Chest Pain"> Chest Pain</label>
        <label><input type="checkbox" name="symptoms[]" value="Injury"> Injury</label>

        <h3>Severity:</h3>
        <select name="severity" style="width:100%; padding:10px; border-radius:6px;">
            <option value="low">Low</option>
            <option value="medium">Medium</option>
            <option value="high">High</option>
        </select>

        <br><br>
        <button type="submit">🔍 Get Recommendation</button>

        <div id="loader" class="loader">
            ⏳ Analyzing symptoms...
        </div>

    </form>
</div>

</body>
</html>