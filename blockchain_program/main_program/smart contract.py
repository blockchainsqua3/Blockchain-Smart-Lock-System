from web3 import Web3
from datetime import datetime
import time

# 連接到以太坊節點
w3 = Web3(Web3.HTTPProvider("HTTP://192.168.1.104:7545"))
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
address = '0xd1CB4DcDf777d7d0493C35491b9A495A55Cd9fee'  # 填入 MeritBox 合約的地址

# 根據 ABI 和地址載入智能合約
contract = w3.eth.contract(address=address, abi=abi)
x = input("請輸入地址：")
#呼叫 transfer 函數
tx_hash = contract.functions.transfer(x, 1).transact({'from': '0x217cEcd35B9c37019e9A1Fd7D455f4C1209111F4'})

# 等待交易被打包
w3.eth.wait_for_transaction_receipt(tx_hash)
# 呼叫 transferCount 函數取得转账次数
transfer_count = contract.functions.transferCount().call()
transfer_time = []
# 輸出轉帳記錄
if transfer_count > 0:
    print(f"共有 {transfer_count} 筆轉帳紀錄")
    transfer_times = []
    for i in range(transfer_count):
        transfer_from, transfer_to, transfer_value, transfer_time = contract.functions.getTransfer(i).call()
        transfer_datetime = datetime.fromtimestamp(transfer_time)
        transfer_times.append(transfer_datetime)
        print(f"第 {i+1} 筆轉帳：{transfer_to}，時間：{transfer_datetime}")
else:
    print("沒有轉帳紀錄")