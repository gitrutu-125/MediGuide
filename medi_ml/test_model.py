import pickle

model = pickle.load(open("model.pkl", "rb"))
columns = pickle.load(open("columns.pkl", "rb"))

print("Model loaded successfully")
print("Number of symptoms:", len(columns))