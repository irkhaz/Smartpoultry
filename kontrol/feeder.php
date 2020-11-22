<?php
include_once '../include/koneksi.php';


$sql = "SELECT * FROM kontrol where device='feeder'";

$query = mysqli_query($conn, $sql);
$count = mysqli_num_rows($query);

if ($count > 0){ //jika data ditemukan
    //create JSON data
    foreach ($query as $row) {
    	$json[$row['device']] = $row['status'];
	}

	$result = json_encode($json);

	echo $result;

}else{  //Jika data tidak ditemukan
  	
	$json['Tidak ada device'] = '0';
  
    //encode array to JSON
    $result = json_encode($json);

    echo $result;

}

?>