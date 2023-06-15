import mysql.connector

# 連接 MySQL 資料庫
connection = mysql.connector.connect(
    host="127.0.0.1",
    user="root",
    password="123456789",
    auth_plugin='mysql_native_password',
    database="maxdb",
)

# 新增使用者
cursor = connection.cursor()
sql = "INSERT INTO user_accounts (username, password) VALUES (%s, %s)"
values = ("root", "123456789")
cursor.execute(sql, values)

# 提交更改
connection.commit()
print(cursor.rowcount, "筆記錄已插入")

# 關閉資料庫連接
cursor.close()
connection.close()
print("資料庫連接已關閉")

