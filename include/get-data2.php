<?php
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


    $urlstock = 'http://things.ubidots.com/api/v1.6/devices/nodemcu/feed_tank/lv?token=BBFF-rkhjqHTaCUPWjrH1V2ZjwixZKbdxro&page_size=1';
    $session = curl_init();
    curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($session, CURLOPT_URL, $urlstock);
    $stock_str = curl_exec($session); // will return true or false
    curl_close($session);
    $stock = intval($stock_str); 
    //echo $stock . "<br>";

    $urlam = 'http://things.ubidots.com/api/v1.6/devices/raspberry-pi/auto-mode/lv?token=BBFF-rkhjqHTaCUPWjrH1V2ZjwixZKbdxro&page_size=1';
    $session = curl_init();
    curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($session, CURLOPT_URL, $urlam);
    $auto_mode = curl_exec($session); // will return true or false
    curl_close($session);
    //echo $auto_mode . "<br>";

    $urlfan1 = 'http://things.ubidots.com/api/v1.6/devices/raspberry-pi/fan-1/lv?token=BBFF-rkhjqHTaCUPWjrH1V2ZjwixZKbdxro&page_size=1';
    $session = curl_init();
    curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($session, CURLOPT_URL, $urlfan1);
    $fan1 = curl_exec($session); // will return true or false
    curl_close($session);
    //echo $fan1 . "<br>";

    $urlfan2 = 'http://things.ubidots.com/api/v1.6/devices/raspberry-pi/fan-2/lv?token=BBFF-rkhjqHTaCUPWjrH1V2ZjwixZKbdxro&page_size=1';
    $session = curl_init();
    curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($session, CURLOPT_URL, $urlfan2);
    $fan2 = curl_exec($session); // will return true or false
    curl_close($session);
    //echo $fan2 . "<br>";

    $urllamp = 'http://things.ubidots.com/api/v1.6/devices/raspberry-pi/heater-lamp/lv?token=BBFF-rkhjqHTaCUPWjrH1V2ZjwixZKbdxro&page_size=1';
    $session = curl_init();
    curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($session, CURLOPT_URL, $urllamp);
    $lamp = curl_exec($session); // will return true or false
    curl_close($session);
    //echo $lamp . "<br>";

    /*
    $urlfeeder = 'http://things.ubidots.com/api/v1.6/devices/raspberry-pi/' + a_var + '/lv?token=BBFF-rkhjqHTaCUPWjrH1V2ZjwixZKbdxro&page_size=1';
    $session = curl_init();
    curl_setopt($session, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($session, CURLOPT_URL, $urlfeeder);
    $feeder = curl_exec($session); // will return true or false
    curl_close($session);
    echo $feeder . "<br>"; */

    
?>