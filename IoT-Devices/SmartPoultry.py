import RPi.GPIO as GPIO
import requests
import time
import json
import os
import Adafruit_DHT
import urllib
from ubidots import ApiClient

#Config DHT11
#sensor = Adafruit_DHT.DHT11 #DHT11
sensor = Adafruit_DHT.DHT22 #DHT22
pindht = 4 #Mode BCM

#Config Ubidots
TOKEN = "BBFF-rkhjqHTaCUPWjrH1V2ZjwixZKbdxro"
device = "raspberry-pi"
api = ApiClient("BBFF-ac6026c88acdafc8412a71a601855ba6f75")
temp_key = api.get_variable("5ec6505d1d84724c640d30e8")
hum_key = api.get_variable("5ec650b11d84724d9734b03f")

#Config server
#server = "http://192.168.1.9"
server = "http://irkhaz.my.id"
URLgetdata = server + "/kontrol/get-data.php"
URLsenddata = server + "/kontrol/set-data.php"

#Setting up GPIO
relay1 = 29     #GPIO5
relay2 = 31     #GPIO6
relay3 = 33     #GPIO13
relay4 = 35     #GPIO19
relay5 = 37     #GPIO26

GPIO.setmode(GPIO.BOARD)
GPIO.setwarnings(False)
GPIO.cleanup()

GPIO.setup(relay1, GPIO.OUT)
GPIO.output(relay1, True)       #off relay
GPIO.setup(relay2, GPIO.OUT)
GPIO.output(relay2, True)       #off relay
GPIO.setup(relay3, GPIO.OUT)
GPIO.output(relay3, True)       #off relay
GPIO.setup(relay4, GPIO.OUT)
GPIO.output(relay4, True)       #off relay
GPIO.setup(relay5, GPIO.OUT)
GPIO.output(relay5, True)       #off relay

timestamp = int(time.time())


def read_sensor():              #Read sensor DHT22 Function
    global temperature
    global humidity
    humidity, temperature = Adafruit_DHT.read_retry(sensor, pindht)
    if humidity != None and temperature != None:
        str_temp = ' {0:0.2f} *C '.format(temperature)	
        str_hum  = ' {0:0.2f} %'.format(humidity)
        #print('Temp={0:0.1f}*C  Humidity={1:0.1f}%'.format(temperature, humidity))
    else:
        print('Failed to read sensor!!!')



while True:

    try:
        responCMD = requests.get(URLgetdata)
        #print(responCMD.text)

        try:
            responCMDjson = responCMD.json()
            #print(json.dumps(responCMDjson, indent=4, sort_keys=True))

            if responCMDjson['auto_mode'] == "1":
                am = 1
                print("Auto-mode aktif")
                read_sensor()

                if temperature <= 29:
                    fan1 = 0
                    fan2 = 0
                    lamp = 1
                elif temperature <= 32:
                    fan1 = 1
                    fan2 = 0
                    lamp = 0
                elif temperature > 32:
                    fan1 = 1
                    fan2 = 1
                    lamp = 0

            else:
                am = 0
                print ("Auto-mode mati")

                if responCMDjson['fan1'] == "1":
                    fan1 = 1
                else:
                    fan1 = 0

                if responCMDjson['fan2'] == "1":
                    fan2 = 1
                else:
                    fan2 = 0

                if responCMDjson['lamp'] == "1":
                    lamp = 1
                else:
                    lamp = 0


        except:
            print("Decode responCMD JSON error")
    
    except:
        print("Connection Error")

    #set GPIO value based by database
    if fan1 == 1:
        print("kipas 1 aktif")
        GPIO.output(relay1, False)   #On relay
        GPIO.output(relay2, False)   #On relay
    else:
        print ("kipas 1 mati")
        GPIO.output(relay1, True)   #Off relay
        GPIO.output(relay2, True)   #Off relay
    
    if fan2 == 1:
        print("kipas 2 aktif")
        GPIO.output(relay3, False)   #On relay
        GPIO.output(relay4, False)   #On relay
    else:
        print ("kipas 2 mati")
        GPIO.output(relay3, True)   #Off relay
        GPIO.output(relay4, True)   #Off relay

    if lamp == 1:
        print("lamp aktif")
        GPIO.output(relay5, False)   #On relay
    else:
        print ("lamp mati")
        GPIO.output(relay5, True)   #Off relay
        

    
    #upload to Ubidots every 30 Seconds

    if int(time.time() - timestamp) > 1*30:
        read_sensor()

        print("Temp = " + str(temperature))
        print("Hum = " + str(humidity))
        try:
            temp_key.save_value({'value':temperature})
            hum_key.save_value({'value':humidity})
            print("Data sent to ubidots")
        except:
            print("Can't upload data to Ubidots")
        
        timestamp = int(time.time())

    time.sleep(2)

GPIO.cleanup()
raise