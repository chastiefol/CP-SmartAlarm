#include <DS3231.h>
#include <LiquidCrystal.h>
#include <Wire.h>

RTClib rtc;

void rtcInit() {
  Wire.begin();

  DateTime now = rtc.now();
  
  // Serial.print(now.hour(), DEC);
  // Serial.print(':');
  // Serial.print(now.minute(), DEC);
  // Serial.print(':');
  // Serial.print(now.second(), DEC);
  // Serial.println();
  
  delay (1000);
};