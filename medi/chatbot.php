<!DOCTYPE html>
<html>
<head>
<title>MediGuide AI Chatbot</title>

<style>
body {
    margin: 0;
    font-family: Arial;
    background: #343541;
}

.chat-container {
    width: 100%;
    max-width: 600px;
    margin: auto;
    height: 100vh;
    display: flex;
    flex-direction: column;
}

.chat-header {
    background: #202123;
    color: white;
    padding: 15px;
    text-align: center;
}

.messages {
    flex: 1;
    padding: 15px;
    overflow-y: auto;
}

.message {
    margin: 10px 0;
    padding: 10px;
    border-radius: 10px;
    max-width: 75%;
}

.user {
    background: #0084ff;
    color: white;
    margin-left: auto;
}

.bot {
    background: #444654;
    color: white;
}

.input-box {
    display: flex;
    padding: 10px;
    background: #202123;
}

input {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 5px;
}

button {
    margin-left: 10px;
    padding: 10px;
    background: #19c37d;
    border: none;
    border-radius: 5px;
    color: white;
    cursor: pointer;
}

.quick-buttons {
    padding: 10px;
    text-align: center;
}

.quick-buttons button {
    margin: 5px;
    background: #555;
}
</style>
</head>

<body>

<div class="chat-container">
    <div class="chat-header">🤖 MediGuide AI Assistant</div>

    <div class="messages" id="chat"></div>

    <!-- Quick Buttons -->
    <div class="quick-buttons">
        <button onclick="quickMsg('fever')">Fever</button>
        <button onclick="quickMsg('cough')">Cough</button>
        <button onclick="quickMsg('chest pain')">Chest Pain</button>
        <button onclick="quickMsg('high severity')">High</button>
    </div>

    <div class="input-box">
        <input type="text" id="msg" placeholder="Type your symptoms...">
        <button onclick="sendMessage()">Send</button>
    </div>
</div>

<script>
function addMessage(text, type){
    let chat = document.getElementById("chat");
    let div = document.createElement("div");
    div.className = "message " + type;
    div.innerHTML = text;
    chat.appendChild(div);
    chat.scrollTop = chat.scrollHeight;
}

function sendMessage(){
    let msg = document.getElementById("msg").value;
    if(msg === "") return;

    addMessage(msg, "user");

    // Typing effect
    addMessage("Typing...", "bot");

    fetch("chatbot_backend.php", {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "message=" + msg
    })
    .then(res => res.text())
    .then(data => {
        let bots = document.querySelectorAll(".bot");
        bots[bots.length - 1].remove(); // remove typing
        addMessage(data, "bot");
    });

    document.getElementById("msg").value = "";
}

function quickMsg(text){
    document.getElementById("msg").value = text;
    sendMessage();
}

// Initial greeting
window.onload = function(){
    addMessage("👋 Hello! Tell me your symptoms (fever, cough, etc.) and severity (low/medium/high).", "bot");
}
</script>

</body>
</html>