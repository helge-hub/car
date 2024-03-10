<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include('../server/connection.php');

if(!isset($_SESSION['admin_logged_in'])){
    header('location:login.php');
    exit;
}


if(isset($_GET['order_id'])){

    $order_id = $_GET['order_id'];
    $stmt2 = $conn->prepare("SELECT * from orders where order_id=? ");
    $stmt2->bind_param('i',$order_id);
    $stmt2->execute();
    $orders = $stmt2->get_result();
    while($row = $orders->fetch_assoc()){
       $order_status_dep = $row['order_status'] ;
    }

    $check_satuts = $order_status_dep;

switch ($check_satuts) {
  case "paid" || "cash":
    $order_status ="shipped";
    $stmt = $conn->prepare("UPDATE orders SET  order_status= ? where order_id = ?");
    $stmt->bind_param('si',$order_status,$order_id);
    if($stmt->execute()){

        // send mail

 require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';



    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'gabeyes241@gmail.com';
    $mail->Password = 'uyms aryw rqbm lxro';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;


    $mail->setFrom('gabeyes241@gmail.com');

    $mail->addAddress($email);

    $mail->isHTML(true);

    $mail->Subject = 'Order Update';
    $mail->Body = '<p>Dear user <span style="color:coral;">ExpressDXB</span> <br>  your order is #OR0023'. $order_id. ',</p><br>
    <p>We wanted to let you know that your order is now being shipped. </p><br>
    <p><span style="font-weight:blod;">Gabeyes</span> Team</p>';

    $mail->send();



        header('location:index.php?order_updated=Order has been updated successfully');
    }else{
        header('location:index.php?order_failed=Error occured, try again'); 
    }
    
    break;
  case "shipped":
    $order_status ="checking";
    $stmt = $conn->prepare("UPDATE orders SET  order_status= ? where order_id = ?");
    $stmt->bind_param('si',$order_status,$order_id);
    if($stmt->execute()){


        //sen mail

        require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';



    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'gabeyes241@gmail.com';
    $mail->Password = 'uyms aryw rqbm lxro';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;


    $mail->setFrom('gabeyes241@gmail.com');

    $mail->addAddress($email);

    $mail->isHTML(true);

    $mail->Subject = 'Order Update';
    $mail->Body = '<p>Dear user <span style="color:coral;">ExpressDXB</span> <br>  your order is #OR0023'. $order_id. ',</p><br>
    <p>We wanted to let you know that your order is now being checked. </p><br>
    <p><span style="font-weight:blod;">Gabeyes</span> Team</p>';

    $mail->send();


        header('location:index.php?order_updated=Order has been updated successfully');
    }else{
        header('location:index.php?order_failed=Error occured, try again'); 
    }
    
    break;
  case "checking":
    $order_status ="airport DXB";
    $stmt = $conn->prepare("UPDATE orders SET  order_status= ? where order_id = ?");
    $stmt->bind_param('si',$order_status,$order_id);
    if($stmt->execute()){

        //send mail

        require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';



    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'gabeyes241@gmail.com';
    $mail->Password = 'uyms aryw rqbm lxro';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;


    $mail->setFrom('gabeyes241@gmail.com');

    $mail->addAddress($email);

    $mail->isHTML(true);

    $mail->Subject = 'Order Update';
    $mail->Body = '<p>Dear user <span style="color:coral;">ExpressDXB</span> <br>  your order is #OR0023'. $order_id. ',</p><br>
    <p>We wanted to let you know that your order is now being dropped at Dubai airport. </p><br>
    <p><span style="font-weight:blod;">Gabeyes</span> Team</p>';

    $mail->send();


        header('location:index.php?order_updated=Order has been updated successfully');
    }else{
        header('location:index.php?order_failed=Error occured, try again'); 
    }
    break;
  case "airport DXB":
    $order_status ="airport LBV";
    $stmt = $conn->prepare("UPDATE orders SET  order_status= ? where order_id = ?");
    $stmt->bind_param('si',$order_status,$order_id);
    if($stmt->execute()){


        //send mail

        require '../phpmailer/src/Exception.php';
require '../phpmailer/src/PHPMailer.php';
require '../phpmailer/src/SMTP.php';



    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'gabeyes241@gmail.com';
    $mail->Password = 'uyms aryw rqbm lxro';
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;


    $mail->setFrom('gabeyes241@gmail.com');

    $mail->addAddress($email);

    $mail->isHTML(true);

    $mail->Subject = 'Order Update';
    $mail->Body = '<p>Dear user <span style="color:coral;">ExpressDXB</span> <br>  your order is #OR0023'. $order_id. ',</p><br>
    <p>We wanted to let you know that your order is now being delivered to LBV-Gabon airport. </p><br>
    <p><span style="font-weight:blod;">Gabeyes</span> Team</p>';

    $mail->send();


        header('location:index.php?order_updated=Order has been updated successfully');
    }else{
        header('location:index.php?order_failed=Error occured, try again'); 
    }
    break;
  default:
   
}

    
}else{
    header('location:index.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<meta charset="UTF-8">
<meta name="viewport"
    content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<link href="css/style.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

<title>Products</title>
</head>
<div class="container text-center position-absolute top-50 start-50 translate-middle" style=" border-radius: white;
  background: white;
  box-shadow: 0 0 5px rgba(0,0,0,0.5);">
    <div>
        <h4>TRAKING ORDER</h4>
        <hr style="border: 1px solid green;">
    </div>
    <div >
        <div>
            <img class="rounded" src="img/check.png" />
        </div>

    <div>
    <a href="index.php" class="btn tracking btn-primary " tabindex="-1" role="button"  style="background-color:coral;margin:20px;"> Continuer</a>
    </div>
</div>

<body>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" referrerpolicy="no-referrer">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="ajax/script.js"></script>

</body>

</html>