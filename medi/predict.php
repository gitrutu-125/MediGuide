<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>AI Disease Predictor</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Google Font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">

<style>

body {
    font-family: 'Poppins', sans-serif;
    background: linear-gradient(135deg, #1d2671, #c33764);
    min-height: 100vh;
    color: white;
}

/* Glass Card */
.card {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(15px);
    border-radius: 20px;
    padding: 25px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.4);
}

/* Search */
#searchBox {
    border-radius: 10px;
    border: none;
    padding: 12px;
}

/* Symptom Chips */
.symptom-box {
    max-height: 250px;
    overflow-y: auto;
}

.symptom {
    display: inline-block;
    padding: 8px 15px;
    margin: 5px;
    border-radius: 20px;
    background: rgba(255,255,255,0.2);
    cursor: pointer;
    transition: 0.3s;
}

.symptom:hover {
    background: #00c6ff;
    transform: scale(1.05);
}

.symptom.active {
    background: #00ff9d;
    color: black;
}

/* Button */
.btn-custom {
    background: linear-gradient(45deg, #00c6ff, #0072ff);
    border: none;
    border-radius: 10px;
    padding: 12px;
    width: 100%;
    font-weight: bold;
}

.btn-custom:hover {
    transform: scale(1.05);
}

/* Loading */
.loading {
    display: none;
}

/* Result Card */
.result-box {
    display: none;
    background: rgba(0,0,0,0.4);
    border-radius: 15px;
    padding: 20px;
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {opacity: 0; transform: translateY(10px);}
    to {opacity: 1; transform: translateY(0);}
}

.badge-custom {
    background: #00ff9d;
    color: black;
    padding: 6px 10px;
    border-radius: 8px;
    margin: 3px;
    display: inline-block;
}

</style>
</head>

<body>

<div class="container mt-5">
    <div class="card">

        <h2 class="text-center mb-4">🧠 AI Disease Predictor</h2>

        <!-- Search -->
        <input type="text" id="searchBox" class="form-control mb-3" placeholder="🔍 Search symptoms...">

        <!-- Symptoms -->
        <div class="symptom-box" id="symptomList">
            <span class="symptom" data-value="fever">Fever</span>
            <span class="symptom" data-value="cough">Cough</span>
            <span class="symptom" data-value="headache">Headache</span>
            <span class="symptom" data-value="vomiting">Vomiting</span>
            <span class="symptom" data-value="fatigue">Fatigue</span>
            <span class="symptom" data-value="dizziness">Dizziness</span>
        </div>

        <p class="mt-2">Selected: <span id="count">0</span></p>

        <!-- Button -->
        <button class="btn btn-custom mt-3" id="predictBtn" >🚀 Predict Disease</button>

        <!-- Loading -->
        <div class="text-center mt-4 loading" id="loading">
            <div class="spinner-border text-light"></div>
            <p>Analyzing symptoms using AI...</p>
        </div>

        <!-- Result -->
        <div class="result-box mt-4" id="resultBox">
            <h4 style="color: white">🩺 Prediction Result</h4>
            <p id="disease" style="color: white"></p>

            <h5 style="color: white">💊 Medicines:</h5>
            <div id="medicines"></div>
        </div>

    </div>
</div>

<script>

// Selected symptoms
let selected = [];

// Toggle symptom selection
document.querySelectorAll(".symptom").forEach(el => {
    el.addEventListener("click", function(){
        let value = this.getAttribute("data-value");

        if(selected.includes(value)){
            selected = selected.filter(v => v !== value);
            this.classList.remove("active");
        } else {
            selected.push(value);
            this.classList.add("active");
        }

        document.getElementById("count").innerText = selected.length;
    });
});

// 🔍 Search filter
document.getElementById("searchBox").addEventListener("keyup", function() {
    let value = this.value.toLowerCase();

    document.querySelectorAll(".symptom").forEach(el => {
        el.style.display = el.innerText.toLowerCase().includes(value) ? "inline-block" : "none";
    });
});

// 🚀 Predict
document.getElementById("predictBtn").addEventListener("click", function(){

    if(selected.length === 0){
        alert("⚠️ Please select at least one symptom!");
        return;
    }

    document.getElementById("loading").style.display = "block";
    document.getElementById("resultBox").style.display = "none";

    fetch("http://127.0.0.1:5000/predict", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ symptoms: selected })
    })
    .then(res => res.json())
    .then(data => {

        document.getElementById("loading").style.display = "none";
        document.getElementById("resultBox").style.display = "block";

        document.getElementById("disease").innerHTML =
            "<b>Disease:</b> " + data.disease;

        let meds = "";
        data.medicines.forEach(m => {
            meds += `<span class="badge-custom">${m}</span>`;
        });

        document.getElementById("medicines").innerHTML = meds;
    })
    .catch(err => {
        document.getElementById("loading").style.display = "none";
        alert("❌ ML server not connected!");
        console.error(err);
    });
});

</script>

</body>
</html>