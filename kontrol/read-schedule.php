<?php


include_once '../include/koneksi.php';

//mysqli_set_charset($conn, 'utf8');

$waktu = $_GET['waktu'];
list ($jam, $menit) = split('[:.-]', $waktu);

$sql = "SELECT  time_format(tbl_jadwal.waktu, '%H:%i') as waktu, status FROM tbl_jadwal WHERE waktu like '%$jam%:%$menit%'";
$query = mysqli_query($conn, $sql);
$count = mysqli_num_rows($query);


if ($count > 0){ //jika data ditemukan
    foreach ($query as $row) {
        $json[$row['waktu']] = $row['status'];
    }
    
    $result = json_encode($json);

    echo $result;

}else{  //Jika data tidak ditemukan
    $json['Tidak ada jadwal'] = '0';

    //encode array to JSON
    $result = json_encode($json);

    echo $result;

}

?>