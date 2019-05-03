const char* ssid = wifiname;
const char* password = wifipass;

void wifiInit()
{
    WiFi.mode(WIFI_STA);
    WiFi.begin(ssid, password);
    // Serial.println("\nConnecting to WiFi");
    while (WiFi.status() != WL_CONNECTED)
    {
        // Serial.print("|");
        delay(400);
    }
    delay(1000);
}