import serial
import time
import datetime
import sqlite3
arduino = serial.Serial("/dev/ttyACM0")
arduino.baudrate = 9600
temperature = arduino.readline()
print(temperature)

dt = datetime.datetime.now()
ts = dt.strftime("%s")
# ds = datetime.datetime.fromtimestamp(ts).strftime('%Y-%m-%d %H:%M:%S')
time.sleep(1)

# connect to the database

temp_float = float(temperature)
conn = sqlite3.connect('/home/pi/aquarium.db');
print('opened database successfully')
conn.execute('''INSERT INTO aquarium_temps (time_recorded,temperature) VALUES(?,?)''',(ts,temp_float))
conn.commit()
print('records created successfully')
conn.close()
