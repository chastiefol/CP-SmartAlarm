#include "smartAlarm.h"
// int speakerPin = D5;
// int Hour = server.arg("hh").toInt();
// int Min = server.arg("mm").toInt();

void setup() {
  pinMode(speakerPin,OUTPUT);
  pinMode(button,INPUT);

  // Serial.begin(115200);
  display("display");
  displayInit();
  display("WIFI");
  wifiInit();
  display("NTP");
  ntpInit();

  // Serial.print("IP address: ");
  // Serial.println(WiFi.localIP());

  // Configuration Web Service Start

  server.on("/", handleRoot);
  server.on("/device", handleDevice);
  server.on("/alarms", handleGetAlarms);
  server.on("/set/alarm", handleSetAlarm);
  server.on("/del/alarm", handleDelAlarm);

  server.onNotFound(handleNotFound);

  server.begin();

  // Configuration Web Service END

  display("Init OK");
  delay(1000);
  tft.fillScreen(ST77XX_BLACK);
}

void loop() {
  server.handleClient();

  time_t now = time(nullptr);
  struct tm* p_tm = localtime(&now);

  // Serial.println("get time");

  if (i > 500){
    tft.setCursor(20, 30);
    
    tft.setTextSize(2);
    tft.print((p_tm->tm_year) + 1900);
    tft.print("-");
    tft.print(p_tm->tm_mon);
    tft.print("-");
    tft.print(p_tm->tm_mday);
    tft.println();

    tft.println();
    tft.setTextSize(3);
    tft.print(p_tm->tm_hour);
    tft.print(":");
    tft.print(p_tm->tm_min);
    tft.print(":");
    tft.print(p_tm->tm_sec);
    tft.println();

    tft.setTextSize(1);
    tft.print("IP:");
    tft.println(WiFi.localIP());
    tft.fillScreen(ST77XX_BLACK);
    i = 0;
  }

  i++;
  alarmInit();
  // Serial.println(i);
}
