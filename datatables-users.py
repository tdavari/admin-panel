import json
import os
import random
import string

def generate_random_string(length):
    letters = string.ascii_lowercase
    return ''.join(random.choice(letters) for i in range(length))

def generate_user_data():
    data = []
    for i in range(1, 51):
        random_id = random.randint(1000, 9999)
        random_name = generate_random_string(8)
        random_email = f"{generate_random_string(8)}@example.com"
        user = {
            "id": random_id,
            "name": random_name,
            "email": random_email
        }
        data.append(user)
    return {"data": data}

def save_to_json(data, output_file):
    with open(output_file, 'w') as json_file:
        json.dump(data, json_file, indent=4)

if __name__ == "__main__":
    #output_path = os.path.join("~","admin-panel","public", "assets", "json", "users.json")
    output_path = "/home/taha/admin-panel/public/assets/json/users.json"
    # Generate user data
    user_data = generate_user_data()

    # Save to JSON file
    save_to_json(user_data, output_path)

    print(f"JSON data saved to: {output_path}")

