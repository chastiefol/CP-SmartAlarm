#include <time.h>
int timezone = timezoneOffset * 3600;
int dst = 0;

void ntpInit(){
    configTime(timezone, dst, ntpServer0, ntpServer1, ntpServer2);
    // Serial.println("\nWaiting for time");
    while (!time(nullptr))
    {
        // Serial.print(".");
        delay(1000);
    }
    time_t now = time(nullptr);
    struct tm* p_tm = localtime(&now);
    // Serial.println("\nget time");
    delay(1000);
}