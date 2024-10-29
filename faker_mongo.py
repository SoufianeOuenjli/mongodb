from pymongo import MongoClient
from faker import Faker
import datetime

# Initialize Faker
fake = Faker('fr_FR')

client = MongoClient("mongodb://localhost:27017/")
db = client['Faker_data']
collection = db['Personne']

# Create fake data
data = []
for i in range(90000):
    birth_date = fake.date_of_birth(minimum_age=18, maximum_age=90)

    # Convert to datetime.datetime for MongoDB compatibility
    birth_datetime = datetime.datetime(birth_date.year, birth_date.month, birth_date.day)

    person = {
        "nom": fake.last_name(),
        "prenom": fake.first_name(),
        "date_de_naissance": birth_datetime,
        "ville": fake.city(),
        "adresse": fake.address(),
        "profession": fake.job()
    }
    data.append(person)

collection.insert_many(data)
print("Data inserted successfully!")
