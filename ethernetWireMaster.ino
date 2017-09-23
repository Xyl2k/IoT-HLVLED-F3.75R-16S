// ethernet wire (arduino master)
// public domain.

#include <SPI.h>
#include <Ethernet.h>
#include <Wire.h>

byte mac[] = { 0xDE, 0xAD, 0xBE, 0xEF, 0xFE, 0xED };
char server[] = "temari.fr"; // change to your host
String dataLocation = "/tmp/index.php HTTP/1.1"; // change to your PHP page
EthernetClient client;
String currentLine = "";
String currRates = "";
int lastStringLength = currRates.length();
boolean readingRates = false;
const int requestInterval = 60000;
char message[61];

boolean requested;
long lastAttemptTime = 0;

void setup() {
  Serial.begin(9600); // Open serial communications

  // start the Ethernet connection:
  if (Ethernet.begin(mac) == 0) {
    Serial.println("Failed to configure Ethernet using DHCP");
    // shit happened, check if you can access internet...
  }
  Wire.begin(); // join i2c bus (address optional for master)
}

void loop() {

  static int count = 0;

  currRates = "";  // Reset for reading
  connectToServer();
  delay(5000); // Wait 5 seconds for client initialization/connection to host
  GetCurrentLine(); // Fetch output from host when available
  for (int i = 0; i < 60; i++)
  {
    message[i] = ' '; 
  }
  currRates.toCharArray(message, 61);
  Serial.println(message); // Print the fetched result

  char messagePartOne[31];
  for (int i = 0; i < 30; i++)
  {
    messagePartOne[i] = message[i];
  }
  messagePartOne[30] = '\0';
  char messagePartTwo[31];
  for (int i = 0; i < 30; i++)
  {
    messagePartTwo[i] = message[i + 30];
  }
  messagePartTwo[30] = '\0';

  Serial.print("Message Part One: ");
  Serial.println(messagePartOne);
  Serial.print("Message Part Two: ");
  Serial.println(messagePartTwo);

  Wire.beginTransmission(8); // transmit to device #8
  Wire.write(messagePartOne); // sends the message to slave
  Wire.endTransmission(); // stop transmitting
  delay(500);
  Wire.beginTransmission(8); // transmit to device #8
  Wire.write(messagePartTwo); // sends the message to slave
  Wire.endTransmission(); // stop transmitting
  delay(60000); // Delay before next loop (one minute).
}

void connectToServer() {
  // attempt to connect, and wait a millisecond:
  if (client.connect(server, 80)) {

    // make HTTP GET request to dataLocation:
    client.println("GET " + dataLocation);
    client.println("Host: temari.fr");
    client.println();
  }
  // note the time of this connect attempt:
  lastAttemptTime = millis();
}

void GetCurrentLine() {
  while (client.available()) {
    char c = client.read();
    currentLine += c;
    if (c == '\n') {
      currentLine = "";
    }
    if (currentLine.endsWith("<MESSAGE>")) {
      readingRates = true;
    }
    else if (readingRates) {
      if (!currentLine.endsWith("</MESSAGE>")) {
        currRates += c;
      }
      else {
        readingRates = false;
        lastStringLength = currRates.length(); // Take the lenght
        lastStringLength = lastStringLength - 9; // We do this to remove "</MESSAGE" tag from the string
        currRates = currRates.substring(0, lastStringLength); // trim the string
        Serial.println(currRates);
        // We got our string from server now, time to disconnect !
        client.stop();
      }
    }
  }
  if (!client.connected()) {
    client.stop();
  }
  if (millis() - lastAttemptTime > requestInterval) {
    connectToServer();
  }
}

