import RPi.GPIO as GPIO
import time

GPIO.setmode(GPIO.BOARD)
GPIO.setup(12, GPIO.OUT)

p = GPIO.PWM(12, 50)
p.start(7.5)

try:
    p.ChangeDutyCycle(5.5)  # turn towards 0 degree
    time.sleep(1)
    p.ChangeDutyCycle(2.5)  # turn towards 90 degree
    time.sleep(1)
    p.ChangeDutyCycle(5.5)  # turn towards 0 degree
    
    p.stop()
    GPIO.cleanup()


except KeyboardInterrupt:
    p.stop()
    GPIO.cleanup()

