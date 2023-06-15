#-*-coding:utf-8-*-
import cv2
from picamera.array import PiRGBArray
from picamera import PiCamera
import time
import numpy as np
import pyzbar.pyzbar as pyzbar


import subprocess

#-*-coding:utf-8-*-
import socket

# 設定伺服器 IP 位址和 Port 號碼
server_ip = '192.168.50.19'
server_port = 8888

# 連接伺服器
client = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
client.connect((server_ip, server_port))



camera = PiCamera()
camera.resolution = (640, 480)
camera.framerate = 32
rawCapture = PiRGBArray(camera, size=(640, 480))
time.sleep(0.1)

# Set initial time for delay
delay_time = time.time()

for frame in camera.capture_continuous(rawCapture, format="bgr", use_video_port=True):
    image = frame.array
    gray = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
    
    # Check if delay time has passed
    if time.time() > delay_time:
        # 在灰度圖像上查找 QRcode
        decoded_objects = pyzbar.decode(gray)

        # 循環遍歷檢測到的 QRcode
        for obj in decoded_objects:
            # 獲取 QRcode的邊界框坐標
            (x, y, w, h) = obj.rect
            # 在圖像上繪制邊界框
            cv2.rectangle(image, (x, y), (x + w, y + h), (0, 255, 0), 2)
            # 獲取 QRcode的數據
            barcode_data = obj.data.decode("utf-8")
            barcode_type = obj.type
            # 在終端打印 QRcode數據和類型
            #print("Data:", barcode_data)
            #print("Type:", barcode_type)

            # 輸入要傳送的字串
            message = barcode_data

            # 將字串轉換為 bytes 格式
            message_bytes = message.encode('utf-8')

            # 傳送字串
            client.sendall(message_bytes)

            # 接收伺服器回傳的資料
            response_bytes = client.recv(1024)

            # 將回傳的 bytes 資料轉換為字串格式
            response = response_bytes.decode('utf-8')

            # 顯示伺服器回傳的資料
            #print(response)
            
            response = int(response)

            if (response == 1):
                subprocess.call(["python", "/home/pi/door1.py"])



            # Set delay time for 5 seconds
            delay_time = time.time() + 5

    # 顯示帶有 QRcode邊界框的圖像
    cv2.imshow("QR Detection", image)
    key = cv2.waitKey(1) & 0xFF
    rawCapture.truncate(0)
    
    # 如果按下 "q" 鍵，則退出循環
    if key == ord("q"):
        break
    



