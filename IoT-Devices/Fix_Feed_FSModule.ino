#include <NTPClient.h>
#include <ESP8266WiFi.h>
#include <ESP8266HTTPClient.h>
#include <WiFiUdp.h>
#include <Arduino_JSON.h>
#include <Ubidots.h>
#include <Servo.h>

// Wlan Config
#define WIFISSID "candra"
#define PASSWORD "04121996"

//GPIO config
#define TRIGGER  14   //D5
#define ECHO     12   //D6

// Configure HTTP client
WiFiClient client;
HTTPClient http;

//Config ubidots
#define token "BBFF-rkhjqHTaCUPWjrH1V2ZjwixZKbdxro" //token ubidots
#define device "NodeMCU"
#define fs_var "feed_tank"

Ubidots ubidots(token, UBI_HTTP);

//server config
//const char* host = "192.168.1.9/smartpoultry2";
const char* host = "irkhaz.my.id";

const long utcOffsetInSeconds = 25200;
 
// Setting tanggal menjadi nama hari
char daysOfTheWeek[7][12] = {"Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"};

// Define NTP Client to get time
WiFiUDP ntpUDP;
NTPClient timeClient(ntpUDP, "id.pool.ntp.org", utcOffsetInSeconds);

Servo feeder;

int get_jam, get_menit;
String jam, menit;
String strID, val, data;
String urlman, urlset, postURL, getURLman, getURLjadwal, urljadwal;
String waktu;
String feed_man, feed_jad, last_feed_jad;
const char* stat;

unsigned long lastMillis = 0;
long duration, distance;
float feedstock, isi;

void setup() {
  // put your setup code here, to run once:
  Serial.begin(115200);
  pinMode(TRIGGER, OUTPUT);
  pinMode(ECHO, INPUT);
  feeder.attach(2); //D4
  feeder.write(0);
  setup_wifi();

  ubidots.setDeviceType(device);  
  timeClient.begin();
}

void setup_wifi() { //koneksi ke Wlan
  delay(10);
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(WIFISSID);

  WiFi.begin(WIFISSID, PASSWORD);

  while (WiFi.status() != WL_CONNECTED) {
    delay(50);
    Serial.print(".");
  }
  Serial.println("");
  Serial.println("Wifi connected");
  Serial.println("IP Address : ");
  Serial.println(WiFi.localIP());
}

void beri_pakan(){
  feeder.write(70);
  delay(3000);
  feeder.write(0);
}

void loop() {
  // put your main code here, to run repeatedly:

  //Mengambil data waktu server
  timeClient.update(); 
  get_jam = timeClient.getHours();
  get_menit = timeClient.getMinutes();

  if (get_jam < 10) {
    jam = "0" + String(get_jam);
  } else {
    jam = String(get_jam);
  }

  if (get_menit < 10) {
    menit = "0" + String(get_menit);
  } else {
    menit = String(get_menit);
  }

  waktu = jam + ":" + menit;
  Serial.println(waktu);
  
  if(client.connect(host, 80)){ 
  
    //baca kontrol data manual
    urlman = "/kontrol/feeder.php?";
    getURLman = "http://" + String(host) + urlman;

    //Serial.println("Connecting to " + getURL);
  
    http.begin(getURLman);                 //specify request destination

    int httpCode_man = http.GET();          //Send the request
  
    //Serial.println(httpCode);

    //decode JSON Object
    if(httpCode_man > 0){
      String payload = http.getString();  //Get the response payload

      JSONVar myObject = JSON.parse(payload);
      //Serial.print("JSON object = ");
      //Serial.println(myObject);

      JSONVar keys = myObject.keys();
      JSONVar man = myObject[keys[0]];
      //Serial.print(keys);
      //Serial.print(" - Status: ");
      //Serial.println(man);

      feed_man = man;
    }
  
    http.end();

    //baca kontrol jadwal
    urljadwal = "/kontrol/read-schedule.php?waktu=";
    urljadwal += waktu;
    getURLjadwal = "http://" + String(host) + urljadwal;

    http.begin(getURLjadwal);                 //specify request destination

    int httpCode_jad = http.GET();          //Send the request

    if(httpCode_jad > 0){
      String payload = http.getString();  //Get the response payload

      JSONVar myObject = JSON.parse(payload);
      //Serial.print("JSON object = ");
      //Serial.println(myObject);

      JSONVar keys = myObject.keys();
      JSONVar jad = myObject[keys[0]];
      //Serial.print(keys);
      //Serial.print(" - Status: ");
      //Serial.println(jad);

      feed_jad = jad;
    }

    http.end();
    
    if(feed_man=="1"){
      Serial.println("Waktunya beri pakan");
      beri_pakan();
      urlset = "/kontrol/set-data.php";
      postURL = "http://" + String(host) + urlset + "?feeder=0";

      String postData = "feeder=0";

      http.begin(postURL);
      //http.addHeader("Content-Type", "application/x-www-form-urlencoded");

      //auto httpCode = http.POST(postData);
      auto httpCode = http.GET();
      String notif = http.getString();
      
      //Serial.println(httpCode);
      //Serial.println(notif);
      
      http.end();
    }
    
    if (feed_jad != last_feed_jad) {
      if(feed_jad=="1"){
        Serial.println("Waktunya beri pakan"); 
        beri_pakan(); 
      }
    }
    last_feed_jad = feed_jad;
      
  }else{  
    Serial.println("Conection Failed");
    return;
  } 

  //membaca sensor ultrasonik
  digitalWrite(TRIGGER, LOW);
  delayMicroseconds(2);

  digitalWrite(TRIGGER, HIGH);
  delayMicroseconds(10);

  digitalWrite(TRIGGER, LOW);
  duration = pulseIn(ECHO, HIGH);
  distance = (duration / 2) / 29.1;

  isi = 20 - distance;
  
  feedstock = (isi / 20) * 100;
  
  Serial.print("Feedstock : ");
  Serial.print(feedstock);
  Serial.println(" %");

  //kirim data sensor ke ubidots
  if (millis() - lastMillis > 30000) { //tiap 30s
    lastMillis = millis();

    // Send values to ubidots
    ubidots.add(fs_var, feedstock);

    bool bufferSent = false;
    bufferSent = ubidots.send();

    if (bufferSent) {
      // Do something if values were sent properly
      Serial.println("Values sent by the device");
    }
   
  }
    
  delay(3000);
}
