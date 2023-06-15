import socket
import mysql.connector
import datetime
import re
import datetime as dt1
import datetime as dt2


from web3 import Web3


# 連接到以太坊節點
w3 = Web3(Web3.HTTPProvider("HTTP://127.0.0.1:7545"))
# 載入智能合約 ABI 和地址
abi = [
 {
  "inputs": [],
  "stateMutability": "nonpayable",
  "type": "constructor"
 },
 {
  "inputs": [],
  "name": "getLastTransferTime",
  "outputs": [
   {
    "internalType": "uint256",
    "name": "hour",
    "type": "uint256"
   },
   {
    "internalType": "uint256",
    "name": "minute",
    "type": "uint256"
   },
   {
    "internalType": "uint256",
    "name": "second",
    "type": "uint256"
   }
  ],
  "stateMutability": "view",
  "type": "function"
 },
 {
  "inputs": [
   {
    "internalType": "uint256",
    "name": "index",
    "type": "uint256"
   }
  ],
  "name": "getTransfer",
  "outputs": [
   {
    "internalType": "address",
    "name": "from",
    "type": "address"
   },
   {
    "internalType": "address",
    "name": "to",
    "type": "address"
   },
   {
    "internalType": "uint256",
    "name": "value",
    "type": "uint256"
   },
   {
    "internalType": "uint256",
    "name": "time",
    "type": "uint256"
   }
  ],
  "stateMutability": "view",
  "type": "function"
 },
 {
  "inputs": [],
  "name": "lastTransfer",
  "outputs": [
   {
    "internalType": "address",
    "name": "",
    "type": "address"
   }
  ],
  "stateMutability": "view",
  "type": "function"
 },
 {
  "inputs": [],
  "name": "lastTransferTime",
  "outputs": [
   {
    "internalType": "uint256",
    "name": "",
    "type": "uint256"
   }
  ],
  "stateMutability": "view",
  "type": "function"
 },
 {
  "inputs": [],
  "name": "lastValue",
  "outputs": [
   {
    "internalType": "uint256",
    "name": "",
    "type": "uint256"
   }
  ],
  "stateMutability": "view",
  "type": "function"
 },
 {
  "inputs": [],
  "name": "owner",
  "outputs": [
   {
    "internalType": "address",
    "name": "",
    "type": "address"
   }
  ],
  "stateMutability": "view",
  "type": "function"
 },
 {
  "inputs": [
   {
    "internalType": "address",
    "name": "_to",
    "type": "address"
   },
   {
    "internalType": "uint256",
    "name": "_value",
    "type": "uint256"
   }
  ],
  "name": "transfer",
  "outputs": [],
  "stateMutability": "nonpayable",
  "type": "function"
 },
 {
  "inputs": [],
  "name": "transferCount",
  "outputs": [
   {
    "internalType": "uint256",
    "name": "",
    "type": "uint256"
   }
  ],
  "stateMutability": "view",
  "type": "function"
 },
 {
  "inputs": [
   {
    "internalType": "uint256",
    "name": "",
    "type": "uint256"
   }
  ],
  "name": "transfers",
  "outputs": [
   {
    "internalType": "address",
    "name": "to",
    "type": "address"
   },
   {
    "internalType": "uint256",
    "name": "value",
    "type": "uint256"
   },
   {
    "internalType": "uint256",
    "name": "time",
    "type": "uint256"
   }
  ],
  "stateMutability": "view",
  "type": "function"
 }
]
# 填入 MeritBox 合約的 ABI
abi_address = '0xd1CB4DcDf777d7d0493C35491b9A495A55Cd9fee'  # 填入 MeritBox 合約的地址

#設定伺服器 IP 位址和 Port 號碼
server_ip = '192.168.50.19'
server_port = 8888



# 連接 MySQL 資料庫
connection = mysql.connector.connect(
    host = "127.0.0.1",
    user = "root",
    password = "123456789",
    auth_plugin='mysql_native_password',
    database = "maxdb",
)

# 取得現在的日期和時間
now = datetime.datetime.now()

while True:
# 建立伺服器
    server = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    server.bind((server_ip, server_port))
    server.listen(1)

    print('等待樹莓派端連線中...')

    # 接受樹莓派端連線
    client, address = server.accept()
    print('已連線，來自樹莓派端 IP 位址：', address)

    while True:
        # 接收樹莓派端傳送的資料
        message_bytes = client.recv(1024)

        # 將接收的 bytes 資料轉換為字串格式
        message = message_bytes.decode('utf-8')

        # 顯示接收到的資料
        #print('樹莓派端傳送的資料：', message)
        try:
            eth_address = re.search('^ethereum:([a-zA-Z0-9]*)@', message).group(1)
            print(eth_address)
        except AttributeError:
            print('未找到ethereum地址')
            rmessage = str(0)
            response =  rmessage
            response_bytes = response.encode('utf-8')
            client.sendall(response_bytes)
            continue
            
        # 萬能鑰匙
        if (eth_address == '0xe7Ef0909A242cb5C52E22E09910627cEB083d75B'):
            # 根據 ABI 和地址載入智能合約
            contract = w3.eth.contract(address=abi_address, abi=abi)
            x = str(eth_address)
            #呼叫 transfer 函數
            tx_hash = contract.functions.transfer(x, 1).transact({'from': '0x217cEcd35B9c37019e9A1Fd7D455f4C1209111F4'})

            # 等待交易被打包
            w3.eth.wait_for_transaction_receipt(tx_hash)
            # 呼叫 transferCount 函數取得轉賬次數
            transfer_count = contract.functions.transferCount().call()
            transfer_time = []
            # 輸出轉帳記錄
            if transfer_count > 0:
                print(f"共有 {transfer_count} 次解鎖紀錄")
                print("萬能鑰匙解鎖成功")
                transfer_times = []
                for i in range(transfer_count-10 , transfer_count):
                    transfer_from, transfer_to, transfer_value, transfer_time = contract.functions.getTransfer(i).call()
                    transfer_datetime = dt2.datetime.fromtimestamp(transfer_time)
                    #transfer_datetime = dt2.fromtimestamp(transfer_time)
                    transfer_times.append(transfer_datetime)
                    print(f"第 {i+1} 次解鎖紀錄：{transfer_to}，時間：{transfer_datetime}")
            else:
                print("沒有解鎖紀錄")
            # 回傳資料給樹莓派端
            rmessage = str(1)
            response =  rmessage
            response_bytes = response.encode('utf-8')
            client.sendall(response_bytes)
            continue


        # 從資料庫檢索日期
        cursor = connection.cursor()
        cursor.execute("SELECT date_column FROM Test_table11 WHERE address = %s", (eth_address,))
        results = cursor.fetchall()

        if len(results) == 0:
            print("未登記帳號")
            # 回傳資料給樹莓派端
            rmessage = str(0)
            response =  rmessage
            response_bytes = response.encode('utf-8')
            client.sendall(response_bytes)
        else:
            all_outside = True
            for result in results:
                date_column = result[0]
                # 設定時間區間
                start_time = datetime.datetime(date_column.year, date_column.month, date_column.day, 12, 0, 0)
                end_time = datetime.datetime(date_column.year, date_column.month, date_column.day + 1, 11, 0, 0)
                if start_time <= now <= end_time:
                    all_outside = False
                    break
            if all_outside:
                print("現在的時間不在登記時間內")
                # 回傳資料給樹莓派端
                rmessage = str(0)
                response =  rmessage
                response_bytes = response.encode('utf-8')
                client.sendall(response_bytes)
            else:
                print("現在的時間在登記時間內")
                # 根據 ABI 和地址載入智能合約
                contract = w3.eth.contract(address=abi_address, abi=abi)
                x = str(eth_address)
                #呼叫 transfer 函數
                tx_hash = contract.functions.transfer(x, 1).transact({'from': '0x217cEcd35B9c37019e9A1Fd7D455f4C1209111F4'})

                # 等待交易被打包
                w3.eth.wait_for_transaction_receipt(tx_hash)
                # 呼叫 transferCount 函數取得轉帳紀錄
                transfer_count = contract.functions.transferCount().call()
                transfer_time = []
                # 輸出轉帳記錄
                if transfer_count > 0:
                    print(f"共有 {transfer_count} 次解鎖紀錄")
                    print("解鎖成功")
                    transfer_times = []
                    for i in range(transfer_count-10 , transfer_count):
                        transfer_from, transfer_to, transfer_value, transfer_time = contract.functions.getTransfer(i).call()
                        transfer_datetime = dt2.datetime.fromtimestamp(transfer_time)
                        #transfer_datetime = dt2.fromtimestamp(transfer_time)
                        transfer_times.append(transfer_datetime)
                        print(f"第 {i+1} 次解鎖紀錄：{transfer_to}，時間：{transfer_datetime}")
                else:
                    print("沒有解鎖紀錄")
                # 回傳資料給樹莓派端
                rmessage = str(1)
                response =  rmessage
                response_bytes = response.encode('utf-8')
                client.sendall(response_bytes)

    # 關閉資料庫連接
    cursor.close()
    connection.close()
    print('資料庫連接已關閉')

        
        # 如果回傳字串為 "quit"，則關閉連線
        #if rmessage == "quit":
        #    break

# 關閉連線
client.close()
server.close()