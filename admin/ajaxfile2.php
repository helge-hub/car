<?php

session_start();
include "../server/connection.php";
 
$userid = $_POST['userid'];

$stmt2 = $conn->prepare("SELECT * FROM `reservation`  WHERE reservation_id=? ");
$stmt2->bind_param('i',$userid);
$stmt2->execute();
$products = $stmt2->get_result();



while( $row = $products->fetch_assoc()){ 
?>

<div>
<img src="../assets/imgs/<?php echo $row['permis']; ?>" style="max-width: 100%;
height: auto;">
</div>

   
<?php } ?>



