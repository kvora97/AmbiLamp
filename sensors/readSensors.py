#!/usr/bin/python
# Copyright (c) 2014 Adafruit Industries
# Author: Tony DiCola

# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:

# The above copyright notice and this permission notice shall be included in all
# copies or substantial portions of the Software.

# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
# SOFTWARE.
import sys
import time

import Adafruit_DHT
from SoundSensor import SoundSensor
from MCP3008ADC import MCP3008ADC
import RPi.GPIO as GPIO

import pymongo
from datetime import datetime
from pytz import timezone


############ VARIABLES ################

duration = 2 # interval between 2 readings

# DHT Pins
sensorDHT = 22
pinDHT    = 26

# Sound Sensor pins and declarations
pinGate = 21
pinEnvelope = 1
pinAudio = 0

GPIO.setmode(GPIO.BCM)
GPIO.setup(pinGate, GPIO.IN)

myMCP = MCP3008ADC()
mySoundSensor = SoundSensor(pinGate, pinEnvelope, pinAudio)

#############  CONNECT TO MONGODB AND GRAB COLLECTIONS ##############

connection = pymongo.MongoClient("mongodb://AmbiLamp:admin@ds044689.mlab.com:44689/ambilamp")
db = connection.ambilamp
sounds = db.sound
humidities = db.hum
temperatures = db.temp

# Parse command line parameters.
#sensor_args = { '11': Adafruit_DHT.DHT11,
#                '22': Adafruit_DHT.DHT22,
#                '2302': Adafruit_DHT.AM2302 }
#if len(sys.argv) == 3 and sys.argv[1] in sensor_args:
#    sensor = sensor_args[sys.argv[1]]
#    pin = sys.argv[2]
#else:
#    print('usage: sudo ./Adafruit_DHT.py [11|22|2302] GPIOpin#')
#    print('example: sudo ./Adafruit_DHT.py 2302 4 - Read from an AM2302 connected to GPIO #4')
#    sys.exit(1)

# Try to grab a sensor reading.  Use the read_retry method which will retry up
# to 15 times to get a sensor reading (waiting 2 seconds between each retry).

########## SOUND SENSOR ##################


humidity, temperature = Adafruit_DHT.read_retry(sensorDHT, pinDHT)

# Un-comment the line below to convert the temperature to Fahrenheit.
temperature = temperature * 9/5.0 + 32

# taking time stamp
dto = datetime.now(timezone('UTC'))

dto_pacific = dto.astimezone(timezone('US/Pacific'))
#.localize(dto)
dts = datetime.strftime(dto_pacific,"%Y-%m-%d %H:%M:%S")


num_entries = sounds.count()

# Note that sometimes you won't get a reading and
# the results will be null (because Linux can't
# guarantee the timing of calls to read the sensor).
# If this happens try again!
while 1:
  gateVal = GPIO.input(mySoundSensor.get_gate())
  envelopeVal = myMCP.read(mySoundSensor.get_envelope())
  audioVal = myMCP.read(mySoundSensor.get_audio())
  
  if humidity is not None and temperature is not None:
    num_entries = num_entries + 1
    humidity_entry = {'entry':num_entries, 'time':dts, 'val':humidity}
    humidities.insert_one(humidity_entry)
    temperature_entry = {'entry':num_entries, 'time':dts, 'val':temperature}
    temperatures.insert_one(temperature_entry)
    sound_entry = {'entry':num_entries, 'time':dts, 'gate': gateVal, 'envelope':envelopeVal, 'audio':audioVal}
    sounds.insert_one(sound_entry)
#    print('Temp={0:0.1f}*  Humidity={1:0.1f}% '.format(temperature, humidity))

 #   print "NUM ENTRY:", num_entries, "DTS:", dts, "GATE:", gateVal, "ENVELOPE:", envelopeVal, "AUDIO:", audioVal 
    #print('Temp={0:0.1f}* Humidity={1:0.1f}%'.format(temperature,humidity))
    time.sleep(duration) # sleep till its time for next reading
    humidity, temperature = Adafruit_DHT.read_retry(sensorDHT, pinDHT)

#else
#   print('Failed to get reading. Try again!')
#   sys.exit(1)
