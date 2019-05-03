ESP8266WebServer server(80);

void route();

void handleRoot() {
  time_t now = time(nullptr);
  struct tm* p_tm = localtime(&now);

  char temp[1000];

  snprintf(temp, 1000,
  "[ { \"hour\" : %d, \"min\" : %d, \"sec\" : %d, \"name\": \"%s\" },"
  , p_tm->tm_hour, p_tm->tm_min, p_tm->tm_sec, deviceName );

  String message = String(temp);
  message += " { \"alarms\" : [";
  for (uint8_t i = 0; i < alarmCount; i++) {
    message += "{ \"hour\" : ";
    message += String(alarms[i][0]);
    message += ", \"minute\" : ";
    message += String(alarms[i][1]);
    message += "}";
    if( i < alarmCount - 1 ){
      message += ",";
    }
  }
  message += "], \"count\" : " + String(alarmCount) + " } ]";
  server.send(200, "application/json", message);
}

void handleDevice() {
  time_t now = time(nullptr);
  struct tm* p_tm = localtime(&now);

  char temp[1000];

  snprintf(temp, 1000,
  "[ { \"hour\" : %d, \"min\" : %d, \"sec\" : %d, \"name\": \"%s\" } ]"
  , p_tm->tm_hour, p_tm->tm_min, p_tm->tm_sec, deviceName );
  server.send(200, "application/json", temp);
}

void handleGetAlarms(){
  String message = "[ { \"alarms\" : [";
  for (uint8_t i = 0; i < alarmCount; i++) {
    message += "{ \"hour\" : ";
    message += String(alarms[i][0]);
    message += ", \"minute\" : ";
    message += String(alarms[i][1]);
    message += "}";
    if( i < alarmCount - 1 ){
      message += ",";
    }
  }
  message += "] } ]";
  server.send(200, "application/json", message);
}

void handleSetAlarm(){
  alarms[alarmCount][0] = server.arg(0).toInt();
  alarms[alarmCount][1] = server.arg(1).toInt();
  alarmCount++;
  
  handleGetAlarms();
}

void handleDelAlarm(){
  int index = server.arg(0).toInt();

  for (uint8_t i = index; i < alarmCount-1; i++) {
    alarms[i][0] = alarms[i+1][0];
    alarms[i][1] = alarms[i+1][1];
  }
  alarms[alarmCount][0] = -1;
  alarms[alarmCount][1] = -1;

  alarmCount--;
  handleGetAlarms();
}

void handleSetVolume () {
  Serial.println(server.arg(0).toInt());
}

void handleSetPitch () {
  Serial.println(server.arg(0).toInt());
}

void handleNotFound() {
  String message = "File Not Found\n\n";
  message += "URI: ";
  message += server.uri();
  message += "\nMethod: ";
  message += (server.method() == HTTP_GET) ? "GET" : "POST";
  message += "\nArguments: ";
  message += server.args();
  message += "\n";

  for (uint8_t i = 0; i < server.args(); i++) {
    message += " " + server.argName(i) + ": " + server.arg(i) + "\n";
  }

  server.send(404, "text/plain", message);
}

