<?php

session_start();
ob_start();

if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
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

$title = 'Add Schedule';

error_reporting(E_ALL);
// memasukan koneksi sql, header admin dan nav admin
include_once '../include/koneksi.php';
include_once '../include/header_user.php';
include_once '../include/nav_user.php';


// Define variables and initialize with empty values
$jadwal = $waktu = $status = '';
$jadwalerr = $waktuerr = $statuserr = '';

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate username
    if(empty(trim($_POST["jadwal"]))){
        $jadwalerr = "input jadwal";
    } else{
        // Prepare a select statement
        $sql = "SELECT id_jadwal FROM tbl_jadwal WHERE jadwal = ?";

        if($stmt = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("s", $param_jadwal);
            
            // Set parameters
            $param_jadwal = trim($_POST["jadwal"]);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // store result
                $stmt->store_result();
                
                if($stmt->num_rows > 0){
                    $jadwalerr = "jadwal sudah ada";
                } else{
                    $jadwal = trim($_POST["jadwal"]);
                }
            } else{
                echo "Error";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($rfid_err)){
        $sql = "INSERT INTO tbl_jadwal (jadwal, waktu, status) VALUES (?, ?, ?)";

        if($stmt = $conn->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sss", $param_jadwal, $param_waktu, $param_status);
            
            // Set parameters
            $param_jadwal = $jadwal;
            $param_waktu = $_POST['waktu'];
            $param_status = $_POST['status'];

            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to view data schedule page
                header("location: view-data-schedule.php?pesan=tambah");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        $stmt->close();
    }

}


// Close connection
$conn->close();
?>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-datetimepicker.min.css"><script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
    <script src="../assets/javascript/bootstrap-datetimepicker.min.js"></script>
</head>

<body>
    <div class="container">
        <h2>Create New Schedule</h2>
        <p>Silahkan Masukan Data Jadwal Baru</P>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label>Jadwal</label> 
                <input class="form-control" type="text" name="jadwal" placeholder="Jadwal">
                <span class="error"><?php echo $jadwalerr; ?></span>
            </div>
            
            <!-- time-picker -->
            <div class="form-group">
                <label>Jam :</label>
                <div class="input-group date" id="datetimepicker3">
                    <input name="waktu" placeholder="Masukan jam" type="text" class="form-control" readonly>
                    <div class="input-group-addon input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-clock"></i></span>
                    </div>
                </div>
            </div>

            <script type="text/javascript">
                $(function () {
                $.extend(true, $.fn.datetimepicker.defaults, {
                    icons: {
                        time: 'far fa-clock',
                        date: 'far fa-calendar',
                        up: 'fas fa-arrow-up',
                        down: 'fas fa-arrow-down',
                        previous: 'fas fa-chevron-left',
                        next: 'fas fa-chevron-right',
                        today: 'far fa-calendar-check-o',
                        clear: 'far fa-trash',
                        close: 'far fa-times'
                    }
                });
            });
    	    $(function () {
                $('#datetimepicker3').datetimepicker({
                  format: 'HH:mm',
                  ignoreReadonly: true
                });
            });
            </script>

            <div class="form-group">  
                <label>Status :</label>
                <select class="dropdown-toggle" name="status" style="width:100%; height:40px; margin-bottom:20px;" placeholder="pilih status">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <input type="submit" name="sign-up" class="btn btn-primary" value="Simpan">
            <a value="Batal" class="btn btn-light" href="../user/view-data-schedule.php">Batal</a>
        </form>
    </div>
    <br>
</body>

<?php 
 	include_once '../include/footer.php'; 
  ?>