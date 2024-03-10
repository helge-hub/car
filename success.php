<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include('server/connection.php');
$car_id = $_GET['id'];
$reservation_id = $_GET['res_id'];
$date_drop =$_SESSION['car_date_drop'];
$status = "terminer";   


$stmt = $conn->prepare("UPDATE cars SET car_drop_date= ? where car_id = ?");
    $stmt->bind_param('si',$date_drop,$car_id);
    $stmt->execute();

$stmt7 = $conn->prepare("UPDATE reservation SET reservation_status= ? where reservation_id = ?");
    $stmt7->bind_param('si',$status,$reservation_id);
    $stmt7->execute();

    $stmt3 = $conn->prepare("SELECT * FROM `reservation` left join users on reservation.user_id=users.user_id left join cars on reservation.car_id=cars.car_id WHERE reservation.reservation_id=?");
    $stmt3->bind_param('i',$reservation_id);
    $stmt3->execute();
    $products1 = $stmt3->get_result();

    while($row = $products1->fetch_assoc()){
        $car_name = $row['car_name'];
        $duration = $row['duration'];
        $date_pickup = $row['reservation_pickup'];
        $date_valid =date("d/m/Y", strtotime($date_pickup));
        $mobile =$row['mobile'];
    }
//mail for customer

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';



$mail1 = new PHPMailer(true);

$mail1->isSMTP();
$mail1->Host = 'smtp.gmail.com';
$mail1->SMTPAuth = true;
$mail1->Username = 'gabeyes241@gmail.com';
$mail1->Password = 'uyms aryw rqbm lxro';
$mail1->SMTPSecure = 'ssl';
$mail1->Port = 465;


$mail1->setFrom($_SESSION['user_email'],$_SESSION['user_name']);

$mail1->addAddress('gabeyes241@gmail.com');

$mail1->isHTML(true);

    $mail1->Subject = 'Paiement success';
    $mail1->Body = '<p>Chers <span style="color:green;">TAMICARS</span> <br> le client Mr/Mme <span style="font-weight:blod;">'.$_SESSION['user_name']
    .'</span> a effectué le paiement via SingPay </p>
    <p>Informations supplémentaire :</p>
    <p>Nom du véhicule : '.$car_name.' </p>
    <p>Nombre de jours : '.$duration.' jour(s) </p>
    <p>Date de récuperation : '.$date_valid.' </p>


    <p>Information du client : </p>
    <p>Email : '.$_SESSION['user_email'].' </p>
    <p>Téléphone : '.$mobile.' </p><br>
    <p>Confirmer le moyen de paiement</p>
    <div class="row">
    <div class="col"><a href="https://www.cowbird-huge-monkfish.ngrok-free.app/mode.php?id=1&mode='.$reservation_id.'">Airtel Money</a></div>
    <div class="col"><a href="https://www.cowbird-huge-monkfish.ngrok-free.app/mode.php?id=2&mode='.$reservation_id.'">Moov Money</a></div>
    <div class="col"><a href="https://www.cowbird-huge-monkfish.ngrok-free.app/mode.php?id=3&mode='.$reservation_id.'">Carte de crédit</a></div>
    </div><br
    <p>Veuillez vous connecter pour plus information <a href="https://www.cowbird-huge-monkfish.ngrok-free.app>admin</a>. </p><br>
    <p> Equipe <span style="font-weight:blod;">Tomicars</span></p>';

    $mail1->send();
?>




<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->

 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<div class="container" >
    <div class="row text-center"  style="margin-top:70px;">
        <div class="col-sm-6 col-sm-offset-3 panel"  style="background-color:#ccdbdf85;">
            <img class="rounded-circle" src="tomicars.jpg" height="150" />
            <h3>Chers, <?php echo $_SESSION['user_name']; ?></h3>
            <p style="font-size:20px;color:#5C5C5C;">
                Merci pour votre Réservation.
            </p>
            <p style="font-size:20px;color:#5C5C5C;">
                Votre paiement a été efectué avec success.
            </p>
            <a href="index.php" class="btn btn-success">    Acceuil      </a>
            <br><br>
        </div>
    </div>
</div>