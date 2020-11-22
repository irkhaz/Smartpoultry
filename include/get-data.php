<?php
include_once '../include/koneksi.php';


$sql = "SELECT * FROM kontrol";

$query = mysqli_query($conn, $sql);
$count = mysqli_num_rows($query);

foreach ($query as $row) {
    $json[$row['device']] = $row['status'];
}

$result = json_encode($json);

$stat = json_decode($result);

$urltemp = 'http://things.ubidots.com/api/v1.6/devices/raspberry-pi/temperature/lv?token=BBFF-rkhjqHTaCUPWjrH1V2ZjwixZKbdxro&page_size=1';
$session = curl_init();
curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($session, CURLOPT_URL, $urltemp);
$temp_str = curl_exec($session); // will return true or false
curl_close($session);
$temp = intval($temp_str);
//echo $temp . "<br>";

$urlhum = 'http://things.ubidots.com/api/v1.6/devices/raspberry-pi/humidity/lv?token=BBFF-rkhjqHTaCUPWjrH1V2ZjwixZKbdxro&page_size=1';
$session = curl_init();
curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($session, CURLOPT_URL, $urlhum);
$hum_str = curl_exec($session); // will return true or false
curl_close($session);
$hum = intval($hum_str);
//echo $hum . "<br>";


$urlstock = 'http://things.ubidots.com/api/v1.6/devices/807d3a2eddb9/feed_tank/lv?token=BBFF-rkhjqHTaCUPWjrH1V2ZjwixZKbdxro&page_size=1';
$session = curl_init();
curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($session, CURLOPT_URL, $urlstock);
$stock_str = curl_exec($session); // will return true or false
curl_close($session);
$stock = intval($stock_str); 
//echo $stock . "<br>";

$auto_mode = $stat->auto_mode;
$fan1 = $stat->fan1;
$fan2 = $stat->fan2;
$lamp = $stat->lamp;
$feeder = $stat->feeder;

?>