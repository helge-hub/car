<?php

session_start();
$price =$_SESSION['sub'];
$trans =$_SESSION['trans'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>About - Mentor Bootstrap Template</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  
  <!-- Template Main CSS File -->
  

  <!-- =======================================================
  * Template Name: Mentor
  * Updated: Sep 18 2023 with Bootstrap v5.3.2
  * Template URL: https://bootstrapmade.com/mentor-free-education-bootstrap-theme/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>
<style>
      body {
        text-align: center;
        padding: 40px 0;
        background: #EBF0F5;
      }
        h1 {
          color: #88B04B;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-weight: 900;
          font-size: 40px;
          margin-bottom: 10px;
        }
        p {
          color: #404F5E;
          font-family: "Nunito Sans", "Helvetica Neue", sans-serif;
          font-size:20px;
          margin: 0;
        }
      i {
        color: #9ABC66;
        font-size: 100px;
        line-height: 200px;
        margin-left:-15px;
      }
      .card {
        background: white;
        padding: 60px;
        border-radius: 4px;
        box-shadow: 0 2px 3px #C8D0D8;
        display: inline-block;
        margin: 0 auto;
      }
    </style>
    <body>
      <div class="card">
      <div style="border-radius:200px; height:200px; width:200px; background: #F8FAF5; margin:0 auto;">
        <i class="checkmark">✓</i>
      </div>
        <h1>Procedure Paiement</h1> 
        <p>Vous allez être rédirigé vers SINGPAY;<br/> Veuillez suivre les instructions!</p>
        <button class= "btn btn-primary" id="btn_pay" >Continuer</button>
      </div>
      

           
        
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"
                referrerpolicy="no-referrer"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
                crossorigin="anonymous"></script>

        <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/dt-1.11.3/datatables.min.js"></script>
        <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
     $(document).on('click','#btn_pay',function(){
    try {
        const answer = axios({
            rejectUnauthorized: false,
            method: 'post',
            url: 'https://gateway.singpay.ga/v1/ext',
            headers: {
                "accept" : "*/*",
                "x-client-id" : "95d42f36-6ad0-42d0-ad53-d2a15f76a348",
                "x-client-secret" : "49568baee36ac7cd20d5697bedd98a920d51fe2640f339834b49642b90740fb0",
                "x-wallet" : "61a73eb501c4f0ef0eb63bd2",
                "Content-Type" : "application/json"
            },
            data: { "portefeuille": "61a73eb501c4f0ef0eb63bd2",
                "reference": "<?php echo $trans;?>",
                "redirect_success" :"http://localhost:8080/car/success.php?id=<?php echo $_SESSION['car_id'] ;?>&res_id=<?php  echo $_SESSION['reservation_id']; ?>",
                "redirect_error" :"http://localhost:8080/car/failed.php",
                "amount" :"<?php echo $price;?>",
                "logoURL": "https://www.expressdxb.com/assets/imgs/logo.png"
            }
        }).then(function(answer){
        console.log(answer);
         window.location.href=answer.data.link;
        
        })
        
    } catch(error) {
        console.log(error);
    }
   
})
    </script>
    
</body>
</html>