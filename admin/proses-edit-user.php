<?php
    include_once '../include/koneksi.php';

    $id = $_POST['id'];

    // username
    if (empty(trim($_POST['username']))) {
        $usernameerr = "Username Tidak Boleh Kosong";
    }else {
        $username = trim($_POST['username']);
    }

    // password
    if (empty(trim($_POST['password']))) {
        $passworderr = "Password tidak boleh kosong";
    }else {
        $password = trim($_POST['password']);
    }

    $is_admin = $_POST['is_admin'];
 
    $sql = "UPDATE user SET username = '{$username}', password = '{$password}', is_admin = '{$is_admin}' WHERE id_user = '{$id}'";
    $result = mysqli_query($conn, $sql);

    header('location: view-data-user.php?pesan=edit');
?>