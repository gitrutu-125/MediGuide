import pandas as pd
from sklearn.naive_bayes import MultinomialNB
import pickle
import os

# Load dataset
 
dataset_path = os.path.join("dataset", "Final_Augmented_dataset_Diseases_and_Symptoms.csv")
df = pd.read_csv(dataset_path)

# Clean columns
df.columns = df.columns.str.strip().str.lower()

# Reduce dataset size (IMPORTANT)
df = df.sample(n=50000, random_state=42)

# Split data
X = df.drop("diseases", axis=1)
y = df["diseases"]

# Optimize memory
X = X.astype('int8')

print("Training lightweight model...")

# Train model
model = MultinomialNB()
model.fit(X, y)

# Save model
with open("model.pkl", "wb") as f:
    pickle.dump(model, f)

# Save columns
with open("columns.pkl", "wb") as f:
    pickle.dump(X.columns.tolist(), f)

print("✅ Model trained successfully!")