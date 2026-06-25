def clean_symptom(symptom):
    return symptom.strip().lower().replace(" ", "_")


def preprocess_input(symptoms, columns):
    input_data = [0] * len(columns)

    for symptom in symptoms:
        symptom = clean_symptom(symptom)
        if symptom in columns:
            index = columns.index(symptom)
            input_data[index] = 1

    return input_data