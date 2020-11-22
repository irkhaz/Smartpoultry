<?php
    include_once '../include/koneksi.php';

    $id = $_POST['id_jadwal'];

    // username
    if (empty(trim($_POST['jadwal']))) {
        $jadwalerr = "Username Tidak Boleh Kosong";
    }else {
        $jadwal = trim($_POST['jadwal']);
    }

    // password
    if (empty(trim($_POST['waktu']))) {
        $waktuerr = "Password tidak boleh kosong";
    }else {
        $waktu = trim($_POST['waktu']);
    }

    $status = $_POST['status'];
 
    $sql = "UPDATE tbl_jadwal SET jadwal = '{$jadwal}', waktu = '{$waktu}', status = '{$status}' WHERE id_jadwal = '{$id}'";
    $result = mysqli_query($conn, $sql);

    header('location: view-data-schedule.php?pesan=edit');
?>