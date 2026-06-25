from flask import Flask, request, jsonify
from flask_cors import CORS
import pickle

app = Flask(__name__)
CORS(app)

# Load model and columns
model = pickle.load(open("model.pkl", "rb"))
columns = pickle.load(open("columns.pkl", "rb"))

# Optional: medicine suggestions
medicine_dict = {
    "flu": ["Paracetamol", "Ibuprofen"],
    "cold": ["Cetirizine", "Rest"],
    "malaria": ["Chloroquine"],
    "diabetes": ["Metformin"],
    "hypertension": ["Amlodipine"]
}

@app.route("/")
def home():
    return "Disease Prediction API Running"

@app.route("/predict", methods=["POST"])
def predict():
    data = request.json

    # Initialize input vector
    input_data = [0] * len(columns)

    for symptom in data.get("symptoms", []):
        if symptom in columns:
            index = columns.index(symptom)
            input_data[index] = 1

    prediction = model.predict([input_data])[0]

    return jsonify({
        "disease": prediction,
        "medicines": medicine_dict.get(prediction, ["Consult doctor"])
    })


@app.route("/symptoms", methods=["GET"])
def get_symptoms():
    return jsonify(columns)


if __name__ == "__main__":
    app.run(debug=True)