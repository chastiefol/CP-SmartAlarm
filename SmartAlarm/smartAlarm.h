#include "setting.h"
#include <ESP8266WiFi.h>
#include <ESP8266WiFi.h>
#include <WiFiClient.h>
#include <ESP8266WebServer.h>
#include <ESP8266mDNS.h>

int alarms[10][2];
int alarmCount = 0;

#include "display.h"
#include "wifi.h"
#include "ntp.h"
// #include "rtc.h"
// #include "sdcard.h"
#include "web.h"
bool state;
int i = 0;
int count = 0;
void alarmInit()
{
    time_t now = time(nullptr);
    struct tm* p_tm = localtime(&now);

    for(int k = 0; k < 10; k++)
    {
        if (p_tm->tm_hour == alarms[k][0] && p_tm->tm_min == alarms[k][1]) {
            state = true;
        }
        if (state == 1) {
            tone(speakerPin,800);
            delay(500);
            tone(speakerPin,1000);
            delay(500);
            tone(speakerPin,1200);
            delay(500);
            noTone(speakerPin);
            count++;
        }
        if (digitalRead(button)==HIGH) {
            state = false;
        }
        if (count == 0) {
            state = false;
        }

    }
    if (count >= 10) {
        count = 0;
    }
    
    state = false;
}
void display(String text)
{
    tft.fillScreen(ST77XX_BLACK);
    tft.setCursor(10, 50);
    tft.println(text);
}
