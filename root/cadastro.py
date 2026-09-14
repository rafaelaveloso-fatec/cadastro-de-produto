import mysql.connector
conexao = mysql.connector.connect (
    host="localhost",
    user="root",
    password="usbw",
    database='cadastro_clientes'
)

cursor = conexao.cursor()

#CRUD 



cursor.close()
conexao.close()