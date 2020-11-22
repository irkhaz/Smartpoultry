<?php

session_start();
ob_start();
 
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: ../login.php");
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

error_reporting(E_ALL | E_WARNING | E_NOTICE);
ini_set('display_errors', TRUE);
include_once('../include/koneksi.php');

$title = 'Edit Schedule';

$idschedule = $jadwal = $waktu = $status = '';
$idscheduleerr = $jadwalerr = $waktuerr = $statuserr = '';

if (isset($_POST['submit'])) {
    $id = $_POST['id'];
    echo "id: ".$id;

    //id schedule
    if (empty(trim($_POST['id_jadwal']))) {
        $idscheduleerr = "ID jadwal Tidak Boleh Kosong";
      }else {
        $idschedule = trim($_POST['id_jadwal']);
      }

    // Nama jadwal
    if (empty(trim($_POST['jadwal']))) {
        $jadwalerr = "Nama Jadwal Tidak Boleh Kosong";
    }else {
        $jadwal = trim($_POST['jadwal']);
    }

    // waktu
    if (empty(trim($_POST['jadwal']))) {
        $waktuerr = "Jam Tidak Boleh Kosong";
    }else {
        $waktu = trim($_POST['waktu']);
    }

    //insert to table
    if (!empty($jadwal) && !empty($waktu)){
        $sql = 'UPDATE tbl_jadwal SET ';          
        $sql .= "jadwal = '{$jadwal}', waktu = '{$waktu}', status = '{$status}'"; 
        $sql .= " WHERE id_jadwal = '{$idschedule}'";

        $result = mysqli_query($conn, $sql);

        if (!$result) {
            die(mysqli_error($conn));
        }
        header('location: view-data-schedule.php');
    }
}

$id = $_GET['id'];
$sql = "SELECT tbl_jadwal.id_jadwal, tbl_jadwal.jadwal, time_format(tbl_jadwal.waktu, '%H:%i') as waktu, tbl_jadwal.status FROM tbl_jadwal WHERE id_jadwal = '{$id}'";
$result = mysqli_query($conn, $sql);
if (!$result) die('Error: Data not Available');

$data = mysqli_fetch_array($result);

function is_select($var, $val) {
    if ($var == $val) return 'selected="selected"';
    return false;
}

include_once '../include/header_user.php';
include_once '../include/nav_user.php';
?>

<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/bootstrap-datetimepicker.min.css"><script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.26.0/moment.min.js"></script>
    <script src="../assets/javascript/bootstrap-datetimepicker.min.js"></script>
</head>

<body>
    <div class="container cursive">
        <h2>Edit Schedule</h2>
        <p>Silahkan Masukan Data Jadwal</P>
        <form class="needs-validation" novalidate method="POST" action="proses-edit-schedule.php" enctype="multipart/form-data">

            <div class="form-group"> 
                <input class="form-control" type="hidden" name="id_jadwal" value="<?php echo $data['id_jadwal']; ?>">
            </div>

            <div class="form-group">
                <label>Jadwal :</label> 
                <input class="form-control" type="text" name="jadwal" value="<?php echo $data['jadwal']; ?>"placeholder="Jadwal" required>
                <span class="invalid-feedback">Nama Jadwal Tidak Boleh Kosong.</span>
            </div>
            
            <!-- time-picker -->
            <div class="form-group">
                <label>Jam :</label>
                <div class="input-group date" id="datetimepicker3">
                    <input name="waktu" placeholder="Masukan jam" type="text" class="form-control" value="<?php echo $data['waktu']; ?>"readonly>
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
            
            // Example starter JavaScript for disabling form submissions if there are invalid fields
            (function() {
                'use strict';
                window.addEventListener('load', function() {
                    // Fetch all the forms we want to apply custom Bootstrap validation styles to
                    var forms = document.getElementsByClassName('needs-validation');

                    // Loop over them and prevent submission
                    var validation = Array.prototype.filter.call(forms, function(form) {
                        form.addEventListener('submit', function(event) {
                            if (form.checkValidity() === false) {
                                event.preventDefault();
                                event.stopPropagation();
                            }
                            form.classList.add('was-validated');
                        }, false);
                    });
                }, false);
            })();
                
            </script>

            <div class="form-group">  
                <label>Status :</label>
                <select class="dropdown-toggle" name="status" style="width:100%; height:40px; margin-bottom:20px;" placeholder="pilih status">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>

            <input type="submit" name="submit" class="btn btn-primary" value="Simpan">
            <a value="Batal" class="btn btn-light" href="../user/view-data-schedule.php">Batal</a>
        </form>
    </div>
    <br>
</body>

<?php 
 	include_once '../include/footer.php'; 
  ?>