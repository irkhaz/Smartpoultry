<?php
include_once '../include/koneksi.php';


$sql = "SELECT * FROM kontrol";

$query = mysqli_query($conn, $sql);
$count = mysqli_num_rows($query);

foreach ($query as $row) {
    $json[$row['device']] = $row['status'];
}

$result = json_encode($json);

echo $result;
?>