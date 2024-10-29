from pymongo import MongoClient
import mysql.connector
from mysql.connector import Error

# Connect to MongoDB
mongo_client = MongoClient("mongodb://localhost:27017/")
mongo_db = mongo_client['Faker_data']
mongo_collection = mongo_db['Personne']

# Connect to MySQL (phpMyAdmin)
try:
    mysql_conn = mysql.connector.connect(
        host='localhost',          
        user='root', 
        password='',  
        database='faker_sql'  
    )

    if mysql_conn.is_connected():
        print("Connected to MySQL database")

        # Fetch data from MongoDB
        mongo_data = mongo_collection.find()

        
        data_to_insert = [
            (
                person["nom"],
                person["prenom"],
                person["date_de_naissance"],
                person["ville"],
                person["adresse"],
                person["profession"]
            ) for person in mongo_data
        ]
        # MySQL table creation (if not already created)
        cursor = mysql_conn.cursor()
        create_table_query = '''
        CREATE TABLE IF NOT EXISTS Personne (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(255),
            prenom VARCHAR(255),
            date_de_naissance DATE,
            ville VARCHAR(255),
            adresse TEXT,
            profession VARCHAR(255)
        )
        '''
        cursor.execute(create_table_query)
        # Insert data in bulk
        insert_query = '''
        INSERT INTO Personne (nom, prenom, date_de_naissance, ville, adresse, profession)
        VALUES (%s, %s, %s, %s, %s, %s)
        '''

        batch_size = 1000 
        for i in range(0, len(data_to_insert), batch_size):
            batch = data_to_insert[i:i + batch_size]
            cursor.executemany(insert_query, batch)
            mysql_conn.commit()
            print(f"Inserted batch {i // batch_size + 1}")

        # print('encour')

        # Commit all changes to MySQL
        # mysql_conn.commit()
        # print(f"{cursor.rowcount} records inserted successfully!")

except Error as e:
    print("Error while connecting to MySQL", e)

finally:
    if mysql_conn.is_connected():
        cursor.close()
        mysql_conn.close()
        print("MySQL connection closed")
