<?php


// syarat memulai sesi harus login dulu
session_start();
ob_start();
 
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['is_admin']) || empty($_SESSION['is_admin'])){
    header("location: ../index.php");
    exit;
}

if((time() - $_SESSION["last_login_time"]) > 360){
            
    // akan diarahkan kehalaman logout.php
    header("location: logout.php");
}

else {
    // jika ada aktivitas, maka update tambah waktu session
    $_SESSION["last_login_time"] = time();
}

$title = 'Dashboard';

include_once('../include/header_admin.php');
include_once('../include/nav_admin.php');

include_once '../include/get-data.php';
include_once '../include/koneksi.php';

?>

<style>
.gauge-title{
    text-align: center;
}
.kontrol {
    text-align: center;
    font-size: 20px;
}
.jumbotron {
    position: relative;
    background: #000 url("../assets/images/bg.jpg") center center;
    width: 100%;
    background-size: cover;
    overflow: hidden;
}

.monospace {
  font-family: "Lucida Console", Courier, monospace;
}

</style>
<body>
<div class="jumbotron text-center cursive" style="color: #ffffcc; text-shadow: 2px 2px #ff8000">
    <h1><b>Smart Poultry</b></h1>
    <p><b>Peternakan Ayam Berbasis Internet of Things.</b></p>
</div>
<div class="container cursive">
    <div class="col">
    <h5 style="text-align: center">Selamat datang <?php echo $_SESSION['username'] ?>, sekarang tanggal <?php echo date('d-m-Y');?> | jam <a id="jam"></a>:<a id="menit"></a>:<a id="detik"></a></h5>
        <br>
    </div>
    <div class="row">
        <div class="col-sm-4">
            <h5 class="gauge-title">Feed Stock</h5>
            <div id="stock-gauge"></div>
        </div>

        <div class="col-sm-4">
            <h5 class="gauge-title">Temperature</h5>
            <div id="temp-gauge"></div>
        </div>

        <div class="col-sm-4">
            <h5 class="gauge-title">Humidity</h5>
            <div id="hum-gauge"></div>
        </div>

    </div>
</div>

<div class="container cursive" style="padding-top: 50px">
    <div class="row">
            <div class="col-sm-6" id="temp-chart"></div>
            <div class="col-sm-6" id="hum-chart"></div>
    </div>
</div>

<div class="container cursive" style="padding-top: 30px">
    <p style="text-align: center">SmartPoultry Control System</p> 
    <table class="table table-hover">
        <thead style="text-align: center">
            <tr>
                <th>Device</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody style="text-align: center">
            <tr>
                <td><label for="relay1">Auto-Mode</label></td>
                <!-- creating button for the relay -->
                <td><input type="checkbox" name="am" id="am" <?php if ($auto_mode==1.0) echo "checked='true'" ?>></td>
            </tr>   
            <tr>
                <td><label for="relay2">Fan 1</label></td>
                <td><input type="checkbox" name="fan1" id="fan1" <?php if ($fan1==1.0) echo "checked='true'" ?>></td>
            </tr>
            <tr>
                <td><label for="relay3">Fan 2</label></td>
                <td><input type="checkbox" name="fan2" id="fan2" <?php if ($fan2==1.0) echo "checked='true'" ?>></td>
            </tr>
            <tr>
                <td><label for="relay4">Heater Lamp</label></td>
                <td><input type="checkbox" name="lamp" id="lamp" <?php if ($lamp==1.0) echo "checked='true'" ?>></td>
            </tr>
            <tr>
                <td><label>Feeder</label></td>
                <td><button class="btn btn-primary" id="feeder" onclick="send()">Beri Pakan</button>
            </tr>
        <t/body>
    </table>
</div>


    <script type="text/javascript">
    //ntp config
    time_server = "3.id.pool.ntp.org";

    //menampilkan waktu secara realtime
    window.setTimeout("waktu()", 1000);
 
    function waktu() {
        var waktu = new Date();
        setTimeout("waktu()", 1000);
        document.getElementById("jam").innerHTML = waktu.getHours();
        document.getElementById("menit").innerHTML = waktu.getMinutes();
        document.getElementById("detik").innerHTML = waktu.getSeconds();
    }

    // ubidots configuration
    TOKEN = 'BBFF-rkhjqHTaCUPWjrH1V2ZjwixZKbdxro';
    device = 'raspberry-pi';
    temp_id = '5ec6505d1d84724c640d30e8';
    hum_id = '5ec650b11d84724d9734b03f';
    baseurl = 'https://industrial.api.ubidots.com/api/v1.6/variables/';

    
    //create gauge
    hum = "<?php echo $hum?>";
    temp = "<?php echo $temp?>";
    stock = "<?php echo $stock?>";
    var tempgauge = humgauge = fsgauge ='';

    tempgauge = new JustGage({
        id: 'temp-gauge',
        value: 0,
        min: 0,
        max: 100,
        symbol: ' ℃',
        pointer: true,
        pointerOptions: {
            toplength: -15,
            bottomlength: 10,
            bottomwidth: 12,
            color: '#8e8e93',
            stroke: '#ffffff',
            stroke_width: 3,
            stroke_linecap: 'round'
        },
        gaugeWidthScale: 0.6,
        counter: true,
        relativeGaugeSize: true,
        donut: true,
        relativeGaugeSize: true
    });
    
    
    
    humgauge = new JustGage({
        id: 'hum-gauge',
        value: 0,
        min: 0,
        max: 100,
        symbol: ' %',
        pointer: true,
        pointerOptions: {
            toplength: -15,
            bottomlength: 10,
            bottomwidth: 12,
            color: '#8e8e93',
            stroke: '#ffffff',
            stroke_width: 3,
            stroke_linecap: 'round'
        },
        gaugeWidthScale: 0.6,
        counter: true,
        relativeGaugeSize: true,
        donut: true,
        relativeGaugeSize: true
    });

    feedgauge = new JustGage({
        id: 'stock-gauge',
        value: 0,
        min: 0,
        max: 100,
        symbol: ' %',
        pointer: true,
        pointerOptions: {
            toplength: -15,
            bottomlength: 10,
            bottomwidth: 12,
            color: '#8e8e93',
            stroke: '#ffffff',
            stroke_width: 3,
            stroke_linecap: 'round'
        },
        gaugeWidthScale: 0.6,
        counter: true,
        relativeGaugeSize: true,
        donut: true,
        relativeGaugeSize: true
    });

    tempgauge.refresh(temp);
    humgauge.refresh(hum); 
    feedgauge.refresh(stock);
   
    //get chart data
    function getDataFromVariable(variable, token, callback) {
        var url = baseurl + variable + '/values';
        var headers = {
            'X-Auth-Token': token,
            'Content-Type': 'application/json'
        };
  
        $.ajax({
            url: url,
            method: 'GET',
            headers: headers,
            success: function (res) {
                callback(res.results);
            }
        });
    }

    
    var chart1 = Highcharts.chart('temp-chart', {
        chart: {
            type: 'line',
            height: 250
        },
        title: {
            text: 'Temperature Chart'
        },
        xAxis: {
            type: 'datetime',
        },
        credits: {
            enabled: false
        },
        series: [{
        	data: []
        }]
    });

    var chart2 = Highcharts.chart('hum-chart', {
        chart: {
            type: 'line',
            height: 250
        },
        title: {
            text: 'Humidity Chart'
        },
        xAxis: {
            type: 'datetime',
        },
        credits: {
            enabled: false
        },
        series: [{
        	data: []
        }]
    });
    
    //create a chart
    getDataFromVariable(temp_id, TOKEN, function (values) {
        var data1 = values.map(function (value) {
            return [value.timestamp, value.value];
        });
  
        chart1.series[0].setData(data1);
    });

    getDataFromVariable(hum_id, TOKEN, function (values) {
        var data2 = values.map(function (value) {
            return [value.timestamp, value.value];
        });
  
        chart2.series[0].setData(data2);
    });


    //setting all buttons off state to be red color
    $.fn.bootstrapSwitch.defaults.offColor="danger";

    //inicalizing the switch buttons 
    $("[name='am']").bootstrapSwitch();
    $("[name='fan1']").bootstrapSwitch();
    $("[name='fan2']").bootstrapSwitch();
    $("[name='lamp']").bootstrapSwitch();

    // disabling switch 
    am = '<?php echo $auto_mode?>';

    if (am==1.0){
        //$("[name='am']").bootstrapSwitch('state', true);
        $("[name='fan1']").bootstrapSwitch('disabled',true);
        $("[name='fan2']").bootstrapSwitch('disabled',true);
        $("[name='lamp']").bootstrapSwitch('disabled',true);
        
    } else {
        $("[name='fan1']").bootstrapSwitch('disabled',false);
        $("[name='fan2']").bootstrapSwitch('disabled',false);
        $("[name='lamp']").bootstrapSwitch('disabled',false);
        
    }
    
    // event listener for checkbox value

    $('input[name="am"]').on('switchChange.bootstrapSwitch', function (event, state) { // for auto-mode switch
            if (state == true) {
                
                var client = new XMLHttpRequest();
		        client.open("POST", 'http://irkhaz.my.id/kontrol/set-data.php?auto-mode=1');
                client.send();
                client.onreadystatechange=(e)=>{
                    if(this.readyState==4 && this.status== 200) {
                        console.log(Http.responseText)
                    }
                }

                $("[name='fan1']").bootstrapSwitch('disabled',true);
                $("[name='fan2']").bootstrapSwitch('disabled',true);
                $("[name='lamp']").bootstrapSwitch('disabled',true);
                //document.getElementById("feeder").disabled = true;
            } else {
                
                var client = new XMLHttpRequest();
		        client.open("POST", 'http://irkhaz.my.id/kontrol/set-data.php?auto-mode=0');
                client.send();
                client.onreadystatechange=(e)=>{
                    if(this.readyState==4 && this.status== 200) {
                        console.log(Http.responseText)
                    }
                }

                $("[name='fan1']").bootstrapSwitch('disabled',false);
                $("[name='fan2']").bootstrapSwitch('disabled',false);
                $("[name='lamp']").bootstrapSwitch('disabled',false);
                //document.getElementById("feeder").disabled = false;
           }
    });

    $('input[name="fan1"]').on('switchChange.bootstrapSwitch', function (event, state) { // for fan-1 switch
            if (state == true) {
                
                var client = new XMLHttpRequest();
		        client.open("POST", 'http://irkhaz.my.id/kontrol/set-data.php?fan1=1');
                client.send();
                client.onreadystatechange=(e)=>{
                    if(this.readyState==4 && this.status== 200) {
                        console.log(Http.responseText)
                    }
                }

            } else {
                
                var client = new XMLHttpRequest();
		        client.open("POST", 'http://irkhaz.my.id/kontrol/set-data.php?fan1=0');
                client.send();
                client.onreadystatechange=(e)=>{
                    if(this.readyState==4 && this.status== 200) {
                        console.log(Http.responseText)
                    }
                }
           }
    });

    $('input[name="fan2"]').on('switchChange.bootstrapSwitch', function (event, state) { // for fan-2 switch
            if (state == true) {
 
                var client = new XMLHttpRequest();
		        client.open("POST", 'http://irkhaz.my.id/kontrol/set-data.php?fan2=1');
                client.send();
                client.onreadystatechange=(e)=>{
                    if(this.readyState==4 && this.status== 200) {
                        console.log(Http.responseText)
                    }
                }
            } else {

                
                var client = new XMLHttpRequest();
		        client.open("POST", 'http://irkhaz.my.id/kontrol/set-data.php?fan2=0');
                client.send();
                client.onreadystatechange=(e)=>{
                    if(this.readyState==4 && this.status== 200) {
                        console.log(Http.responseText)
                    }
                }
           }
    });

    $('input[name="lamp"]').on('switchChange.bootstrapSwitch', function (event, state) { // for lamp switch
            if (state == true) {
 
                
                var client = new XMLHttpRequest();
		        client.open("POST", 'http://irkhaz.my.id/kontrol/set-data.php?lamp=1');
                client.send();
                client.onreadystatechange=(e)=>{
                    if(this.readyState==4 && this.status== 200) {
                        console.log(Http.responseText)
                    }
                }
            } else {

                
                var client = new XMLHttpRequest();
		        client.open("POST", 'http://irkhaz.my.id/kontrol/set-data.php?lamp=0');
                client.send();
                client.onreadystatechange=(e)=>{
                    if(this.readyState==4 && this.status== 200) {
                        console.log(Http.responseText)
                    }
                }
            }
    });
    
    function send() {

        var client = new XMLHttpRequest();
		client.open("POST", 'http://irkhaz.my.id/kontrol/set-data.php?feeder=1');
        client.send();
        client.onreadystatechange=(e)=>{
            if(this.readyState==4 && this.status== 200) {
                console.log(Http.responseText)
            }
        }
    }
    
    
    </script>

    <div class="clear"></div>
<?php
// require('include/sidebar.php'); 
include_once('../include/footer.php'); 
 ?>
</body>