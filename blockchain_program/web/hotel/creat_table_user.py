#創建一個日期的table
import mysql.connector

# 連接 MySQL 資料庫
connection = mysql.connector.connect(
    host = "127.0.0.1",
    user = "root",
    password = "123456789",
    auth_plugin='mysql_native_password',
    database = "maxdb",
)

# 創建 table_date 資料表
cursor = connection.cursor()
cursor.execute("""
    CREATE TABLE user_accounts (
    id INTEGER AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255),
    password VARCHAR(255)
)
""")
print('user_accounts 資料表已創建')
# 關閉資料庫連接
cursor.close()
connection.close()
print('資料庫連接已關閉')
