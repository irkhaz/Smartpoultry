<?php
  // session_start();
  // ob_start();
  
  // include "include/koneksi.php";

  // if(empty($_SESSION['username']) or empty($_SESSION['password'])){
  //    echo "<br><br><br><br><br><br><br><br><h1 align='center'> Anda harus login terlebih dahulu!</h1></br>?";
  //    echo "<meta http-equiv='refresh' content='3; url=login.php'>";
  // }else{
  //   define('INDEX', true);

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

$q="";
if (isset($_GET['q']) && !empty($_GET['q'])) {
    $q = $_GET['q'];
    $sql_where = " WHERE username LIKE '{$q}%'";
}

$title = 'User Manager';

$image = '<img src = "../faviconnew.png" alt="">';
include_once('../include/header_admin.php');
include_once('../include/nav_admin.php');
include_once '../include/koneksi.php';

$no=0;
$sql = ("SELECT user.id_user, user.username, user.password, user.is_admin FROM user");

$sql_count = "SELECT COUNT(*) FROM user";
if (isset($sql_where)) {
    $sql .= $sql_where;
    $sql_count .= $sql_where;
}
$result_count = mysqli_query($conn, $sql_count);
$count = 0;
if ($result_count) {
    $r_data = mysqli_fetch_row($result_count);
    $count = $r_data[0];
}
$per_page = 10;
$num_page = ceil($count / $per_page);
$limit = $per_page;
if (isset($_GET['page'])) {
    $page = $_GET['page'];
    $offset = ($page - 1) * $per_page;
} else {
    $offset = 0;
    $page = 1;
}
$sql .= " LIMIT {$offset}, {$limit}";

$result = mysqli_query($conn, $sql);

?>
<div class="container cursive">
  	<?php 
    if(isset($_GET['pesan'])){
        if($_GET['pesan']=="tambah"){
            echo "<br> <div class='alert alert-success alert-dismissible fade show'><button type='button' class='close' data-dismiss='alert'>&times;</button>Data berhasil ditambah</div>";
        }elseif($_GET['pesan']=="edit"){
            echo "<br> <div class='alert alert-success alert-dismissible fade show'><button type='button' class='close' data-dismiss='alert'>&times;</button>Data berhasil diubah</div>";
        }elseif($_GET['pesan']=="hapus"){
            echo "<br> <div class='alert alert-success alert-dismissible fade show'><button type='button' class='close' data-dismiss='alert'>&times;</button>Data berhasil dihapus</div>";
        }
      }
    ?>
    <div class="col-sm-3">
        <?php 
            echo "<br>";
            echo "Sekarang tanggal ".date('d-m-Y'); 
        ?>
    </div>
	<br>
    <div class="col-sm-3">
    <a href="add-user.php" class="btn btn-success"><img src="../assets/images/add.png"  style = "width: 15px; padding-bottom: 5px" /> User</a>
    </div>
    <br>

	<form class="form-inline" action="" method="get">
			<label for="q" class="mb-2 mr-sm-2">Cari data : </label>
			<input type="text" id="q" name="q" class="form-control mb-2 mr-sm-2" >
			<!--<button type="submit" name="submit" value="Cari" class="btn btn-primary mb-2">Cari</button>-->
	</form>

	<table class="table table-striped" style="text-align: center">
        <thead>
            <tr>
 	 		    <th>No.</th>
 	 		    <th>Username</th>
			    <th>Password</th>
			    <th>Hak Akses</th>
                <th>Operasi</th>
 	 	    </tr>
        </thead>

 	 	<?php while($row = mysqli_fetch_array($result)): ?>

 	 	<?php $no++ ?>
        <tbody id="mytable">
 	 		<tr>
 	 			<td><?php echo $no; ?></td>                
 	 			<td><?php echo $row['username'];?></td>
 	 			<td><?php echo $row['password'];?></td>
 	 			<td><?php if($row['is_admin'] == 1){echo 'Admin';}else{echo 'User';};?></td>
 	 			<td>
                  <a class="btn btn-warning" href="edit-user.php?id=<?php echo $row['id_user'];?>">Edit</a>
                  <a class="btn btn-danger" onclick="return confirm('Yakin akan menghapus user ini?');" href="delete-user.php?id=<?php echo $row['id_user'];?>">Delete</a>
                </td>
 	 		</tr>
 	 		<?php endwhile; ?>
        </tbody>    
 	</table>	
 	 	
 	<ul class="pagination justify-content-end">
        <li class="page-item"><a class="page-link" href="?page=<?php if ($page > 1){
            $pagep = $page-1;
        }else{
            $pagep= $page;
        } 
        echo $pagep; ?>">&laquo;</a></li>
        <?php for ($i=1; $i <= $num_page; $i++) { 
            $link = "?page={$i}";
            if (!empty($q)) $link .= "&q={$q}";
                $class = ($page == $i ? 'active' : '');
                echo "<li class=\"page-item {$class}\"><a class=\"page-link\" href=\"{$link}\">{$i}</a></li>";
            } ?>
           <!--  <li>
                <a href="">...</a>
                <a href="">9</a>
                <a href="">10</a>
                <a href="">11</a>
            </li> -->
        <li class="page-item"><a class="page-link" href="?page=<?php if ($page < $num_page){
            $pagen = $page+1;
        }else{
            $pagen= $page;
        }
        echo $pagen; ?>">&raquo;</a></li>
    </ul>	
</div>

<script>
    $(document).ready(function(){
            $("#q").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#mytable tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
</script>

<div class="clear"></div>
<?php
// require('include/sidebar.php'); 
include_once('../include/footer.php'); 
 ?>
